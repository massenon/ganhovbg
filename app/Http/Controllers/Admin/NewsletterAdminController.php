<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Seosetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Administration de la newsletter : liste des abonné·es du pied de page, recherche, export CSV, retrait.
 * Données minimales : e-mail, date de consentement, état. Aucune adresse IP n'est conservée.
 */
class NewsletterAdminController extends Controller
{
    private function base(Request $request)
    {
        $q = DB::table('newsletter_subscribers');
        $term = trim((string) $request->query('q'));
        if ($term !== '') {
            $q->where('email', 'like', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%');
        }
        $state = $request->query('etat');
        if ($state === 'actifs') {
            $q->whereNull('unsubscribed_at');
        } elseif ($state === 'desinscrits') {
            $q->whereNotNull('unsubscribed_at');
        }
        return $q;
    }

    public function index(Request $request)
    {
        $this->authorize('Newsletter Access');

        $data['title'] = Apptitle::first();
        $data['footertext'] = Footertext::first();
        $data['seopage'] = Seosetting::first();
        $data['page'] = Pages::all();

        $data['subscribers'] = $this->base($request)->orderByDesc('created_at')->paginate(25)->withQueryString();
        $data['stats'] = [
            'actifs' => DB::table('newsletter_subscribers')->whereNull('unsubscribed_at')->count(),
            'desinscrits' => DB::table('newsletter_subscribers')->whereNotNull('unsubscribed_at')->count(),
            'nouveaux' => DB::table('newsletter_subscribers')->whereNull('unsubscribed_at')->where('created_at', '>=', now()->subDays(30))->count(),
        ];
        $data['canEdit'] = auth()->user()->can('Newsletter Edit');
        $data['campaigns'] = DB::table('newsletter_campaigns')->orderByDesc('id')->limit(15)->get();
        $data['maxPerCampaign'] = $this->maxPerCampaign();
        $data['filters'] = ['q' => (string) $request->query('q'), 'etat' => (string) $request->query('etat')];

        return view('admin.newsletter.index')->with($data);
    }

    public function export(Request $request)
    {
        $this->authorize('Newsletter Access');

        $rows = $this->base($request)->orderBy('created_at')->get(['email', 'consent_at', 'unsubscribed_at', 'created_at']);
        $name = 'abonnes-newsletter-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 avec BOM pour Excel
            fputcsv($out, ['E-mail', 'Inscrit le', 'Consentement le', 'Statut', 'Désinscrit le'], ';');
            foreach ($rows as $r) {
                // Neutralise l'injection de formules dans un tableur.
                $email = preg_match('/^[=+\-@]/', $r->email) ? "'" . $r->email : $r->email;
                fputcsv($out, [$email, $r->created_at, $r->consent_at, $r->unsubscribed_at ? 'Désinscrit·e' : 'Actif', $r->unsubscribed_at], ';');
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function destroy($id)
    {
        $this->authorize('Newsletter Edit');
        DB::table('newsletter_subscribers')->where('id', (int) $id)->delete();

        return back()->with('success', 'Abonné·e supprimé·e de la liste.');
    }

    public function toggle($id)
    {
        $this->authorize('Newsletter Edit');
        $row = DB::table('newsletter_subscribers')->where('id', (int) $id)->first();
        if ($row) {
            DB::table('newsletter_subscribers')->where('id', $row->id)->update([
                'unsubscribed_at' => $row->unsubscribed_at ? null : now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'État de l’abonnement mis à jour.');
    }

    private const BATCH = 25;

    /** Plafond d'envoi par campagne (limites des hébergements mutualisés : souvent 300 à 500 e-mails/heure). */
    private function maxPerCampaign(): int
    {
        return max(1, (int) (env('GN_NEWSLETTER_MAX') ?: 500));
    }

    private function validated(Request $request)
    {
        $v = Validator::make($request->all(), [
            'subject' => 'required|string|max:190',
            'body' => 'required|string|max:8000',
            'button_label' => 'nullable|string|max:60',
            'button_url' => ['nullable', 'string', 'max:255', 'regex:#^https?://[^\s]+$#i'],
        ], [
            'subject.required' => 'L’objet est obligatoire.',
            'body.required' => 'Le message est vide.',
            'button_url.regex' => 'Le lien du bouton doit commencer par https:// ou http://.',
        ]);
        if ($v->fails()) {
            return [null, $v->errors()->first()];
        }
        $d = $v->validated();
        if (!empty($d['button_label']) && empty($d['button_url'])) {
            return [null, 'Indiquez aussi le lien du bouton, ou videz son texte.'];
        }
        if (empty($d['button_label'])) {
            $d['button_label'] = $d['button_url'] = null;
        }
        return [$d, null];
    }

    /** Rendu HTML d'un e-mail (paragraphes séparés par une ligne vide ; tout est échappé). */
    private function render(array $d, $unsubscribeUrl = null, $consentDate = null): string
    {
        $paras = '';
        foreach (preg_split('/\R{2,}/', trim($d['body'])) as $p) {
            if (trim($p) === '') { continue; }
            $paras .= '<p style="margin:0 0 18px;font-size:16px;line-height:1.6;color:#17132B;">' . nl2br(e(trim($p)), false) . '</p>';
        }
        return view('emails.newsletter', [
            'subject' => $d['subject'],
            'paragraphs' => $paras,
            'buttonLabel' => $d['button_label'] ?? null,
            'buttonUrl' => $d['button_url'] ?? null,
            'unsubscribeUrl' => $unsubscribeUrl,
            'consentDate' => $consentDate,
            'preheader' => \Illuminate\Support\Str::limit(strip_tags(preg_replace('/\s+/', ' ', $d['body'])), 110, '…'),
        ])->render();
    }

    /** Vrai si le serveur d'envoi répond (évite 25 attentes de 30 s quand le SMTP est injoignable). */
    private function smtpReachable(): bool
    {
        if (config('mail.default', config('mail.driver')) !== 'smtp') {
            return true;
        }
        $host = config('mail.mailers.smtp.host', config('mail.host'));
        $port = (int) config('mail.mailers.smtp.port', config('mail.port'));
        if (!$host || $port < 1) { return false; }
        $sock = @fsockopen($host, $port, $errno, $errstr, 5);
        if ($sock) { fclose($sock); return true; }
        return false;
    }

    private function deliver(string $to, string $subject, string $html, ?string $unsubscribeUrl = null): void
    {
        Mail::send([], [], function ($m) use ($to, $subject, $html, $unsubscribeUrl) {
            $m->to($to)->subject($subject);
            $m->setBody($html, 'text/html');
            if ($unsubscribeUrl) {
                $m->getHeaders()->addTextHeader('List-Unsubscribe', '<' . $unsubscribeUrl . '>');
            }
        });
    }

    public function preview(Request $request)
    {
        $this->authorize('Newsletter Edit');
        [$d, $err] = $this->validated($request);
        if ($err) { return response()->json(['ok' => false, 'message' => $err], 422); }
        return response()->json(['ok' => true, 'html' => $this->render($d, url('/newsletter/desinscription/apercu'), now()->locale('fr')->translatedFormat('j F Y'))]);
    }

    public function sendTest(Request $request)
    {
        $this->authorize('Newsletter Edit');
        [$d, $err] = $this->validated($request);
        if ($err) { return response()->json(['ok' => false, 'message' => $err], 422); }
        $to = auth()->user()->email;
        if (!$to) { return response()->json(['ok' => false, 'message' => 'Votre compte n’a pas d’adresse e-mail.'], 422); }
        if (!$this->smtpReachable()) {
            return response()->json(['ok' => false, 'message' => 'Le serveur d’envoi (SMTP) est injoignable : vérifiez les réglages e-mail.'], 503);
        }
        try {
            $this->deliver($to, '[TEST] ' . $d['subject'], $this->render($d, url('/newsletter/desinscription/apercu'), now()->locale('fr')->translatedFormat('j F Y')));
        } catch (\Throwable $e) {
            Log::warning('newsletter test: échec envoi', ['erreur' => substr($e->getMessage(), 0, 160)]);
            return response()->json(['ok' => false, 'message' => 'L’envoi du test a échoué : le serveur d’envoi a refusé le message.'], 502);
        }
        return response()->json(['ok' => true, 'message' => 'Test envoyé à ' . $to . '.']);
    }

    /** Crée la campagne et fige la liste des destinataires actifs (une ligne de remise par abonné·e). */
    public function startCampaign(Request $request)
    {
        $this->authorize('Newsletter Edit');
        [$d, $err] = $this->validated($request);
        if ($err) { return response()->json(['ok' => false, 'message' => $err], 422); }

        if (DB::table('newsletter_campaigns')->where('status', 'sending')->exists()) {
            return response()->json(['ok' => false, 'message' => 'Un envoi est déjà en cours : reprenez-le ou attendez sa fin avant d’en lancer un autre.'], 409);
        }
        if (DB::table('newsletter_campaigns')->where('created_at', '>=', now()->subDay())->count() >= 3) {
            return response()->json(['ok' => false, 'message' => 'Limite atteinte : 3 envois maximum par 24 heures.'], 429);
        }
        $ids = DB::table('newsletter_subscribers')->whereNull('unsubscribed_at')->orderBy('id')->pluck('id');
        if ($ids->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'Aucun·e abonné·e actif·ve : rien à envoyer.'], 422);
        }
        if ($ids->count() > $this->maxPerCampaign()) {
            return response()->json(['ok' => false, 'message' => 'Trop de destinataires (' . $ids->count() . ') pour un seul envoi : le plafond est de ' . $this->maxPerCampaign() . ' (limite habituelle des hébergeurs). Contactez l’administrateur technique.'], 422);
        }
        if (!$this->smtpReachable()) {
            return response()->json(['ok' => false, 'message' => 'Le serveur d’envoi (SMTP) est injoignable : aucun envoi n’a été lancé.'], 503);
        }

        $id = DB::table('newsletter_campaigns')->insertGetId([
            'subject' => $d['subject'], 'body' => $d['body'], 'button_label' => $d['button_label'], 'button_url' => $d['button_url'],
            'status' => 'sending', 'total' => $ids->count(), 'created_by' => auth()->id(), 'started_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        foreach ($ids->chunk(500) as $chunk) {
            DB::table('newsletter_deliveries')->insert($chunk->map(function ($sid) use ($id) { return ['campaign_id' => $id, 'subscriber_id' => $sid, 'status' => 'queued']; })->all());
        }
        Log::info('newsletter: campagne créée', ['id' => $id, 'destinataires' => $ids->count(), 'user_id' => auth()->id()]);

        return response()->json(['ok' => true, 'id' => $id, 'total' => $ids->count()]);
    }

    /** Envoie un lot de 25 messages. Verrou en base : deux requêtes simultanées ne peuvent pas envoyer en double. */
    public function sendBatch($id)
    {
        $this->authorize('Newsletter Edit');
        $id = (int) $id;
        $c = DB::table('newsletter_campaigns')->where('id', $id)->first();
        if (!$c) { return response()->json(['ok' => false, 'message' => 'Campagne introuvable.'], 404); }
        if ($c->status !== 'sending') { return response()->json(['ok' => true, 'done' => true, 'sent' => $c->sent_count, 'failed' => $c->failed_count, 'total' => $c->total]); }

        // Verrou : on ne prend la main que si personne n'envoie (verrou expiré après 90 s).
        $locked = DB::table('newsletter_campaigns')->where('id', $id)
            ->where(function ($q) { $q->whereNull('locked_until')->orWhere('locked_until', '<', now()); })
            ->update(['locked_until' => now()->addSeconds(90)]);
        if (!$locked) { return response()->json(['ok' => false, 'busy' => true, 'message' => 'Un lot est déjà en cours d’envoi, patientez.'], 409); }

        if (!$this->smtpReachable()) {
            DB::table('newsletter_campaigns')->where('id', $id)->update(['locked_until' => null, 'last_error' => 'SMTP injoignable']);
            return response()->json(['ok' => false, 'message' => 'Le serveur d’envoi (SMTP) est injoignable. L’envoi est en pause : relancez-le plus tard avec « Reprendre ».'], 503);
        }

        $rows = DB::table('newsletter_deliveries as d')->join('newsletter_subscribers as s', 's.id', '=', 'd.subscriber_id')
            ->where('d.campaign_id', $id)->where('d.status', 'queued')->whereNull('s.unsubscribed_at')
            ->orderBy('d.id')->limit(self::BATCH)->get(['d.id as did', 's.email', 's.token', 's.consent_at']);

        $campaign = ['subject' => $c->subject, 'body' => $c->body, 'button_label' => $c->button_label, 'button_url' => $c->button_url];
        foreach ($rows as $r) {
            $unsub = route('newsletter.unsubscribe', $r->token);
            $date = $r->consent_at ? \Carbon\Carbon::parse($r->consent_at)->locale('fr')->translatedFormat('j F Y') : null;
            try {
                $this->deliver($r->email, $c->subject, $this->render($campaign, $unsub, $date), $unsub);
                DB::table('newsletter_deliveries')->where('id', $r->did)->update(['status' => 'sent', 'sent_at' => now()]);
            } catch (\Throwable $e) {
                DB::table('newsletter_deliveries')->where('id', $r->did)->update(['status' => 'failed']);
                DB::table('newsletter_campaigns')->where('id', $id)->update(['last_error' => substr($e->getMessage(), 0, 190)]);
                Log::warning('newsletter: échec d’envoi', ['campagne' => $id, 'remise' => $r->did]);
            }
        }

        // Désinscrits entre-temps : on les marque comme ignorés (failed sans erreur serveur) pour que la campagne puisse se terminer.
        DB::table('newsletter_deliveries as d')->join('newsletter_subscribers as s', 's.id', '=', 'd.subscriber_id')
            ->where('d.campaign_id', $id)->where('d.status', 'queued')->whereNotNull('s.unsubscribed_at')->update(['d.status' => 'failed']);

        $st = DB::table('newsletter_deliveries')->where('campaign_id', $id)
            ->selectRaw("SUM(status='sent') as s, SUM(status='failed') as f, SUM(status='queued') as q")->first();
        $done = (int) $st->q === 0;
        DB::table('newsletter_campaigns')->where('id', $id)->update([
            'sent_count' => (int) $st->s, 'failed_count' => (int) $st->f, 'locked_until' => null, 'updated_at' => now(),
            'status' => $done ? 'sent' : 'sending', 'finished_at' => $done ? now() : null,
        ]);

        return response()->json(['ok' => true, 'done' => $done, 'sent' => (int) $st->s, 'failed' => (int) $st->f, 'queued' => (int) $st->q, 'total' => (int) $c->total]);
    }

    /** Abandonne un envoi resté « en cours » (les messages déjà partis ne sont pas rappelés). */
    public function abortCampaign($id)
    {
        $this->authorize('Newsletter Edit');
        DB::table('newsletter_campaigns')->where('id', (int) $id)->where('status', 'sending')->update(['status' => 'aborted', 'locked_until' => null, 'finished_at' => now()]);
        return back()->with('success', 'Envoi abandonné. Les messages déjà partis ne sont pas rappelés.');
    }
}
