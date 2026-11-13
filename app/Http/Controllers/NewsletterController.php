<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Newsletter GANXÔ : inscription depuis le pied de page, désinscription par lien à jeton.
 * Champ piège « website » (anti-robots) ; aucune donnée autre que l'e-mail.
 */
class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $prev = url()->previous() ?: url('/');
        $anchor = in_array($request->input('anchor'), ['newsletter', 'newsletter-aside'], true) ? $request->input('anchor') : 'newsletter';
        $back = strtok($prev, '#') . '#' . $anchor;

        if (trim((string) $request->input('website')) !== '') {
            return redirect($back)->with('newsletter_status', 'Merci, vous êtes inscrit·e.');
        }

        $v = Validator::make($request->all(), [
            'newsletter_email' => 'required|email:rfc|max:190',
            'newsletter_consent' => 'accepted',
        ], [
            'newsletter_email.required' => 'Saisissez votre adresse e-mail.',
            'newsletter_email.email' => 'Cette adresse e-mail ne semble pas valide.',
            'newsletter_consent.accepted' => 'Cochez la case pour confirmer votre accord.',
        ]);

        if ($v->fails()) {
            return redirect($back)->with('newsletter_error', $v->errors()->first())->withInput($request->only('newsletter_email'));
        }

        $email = Str::lower(trim($request->input('newsletter_email')));
        $row = DB::table('newsletter_subscribers')->where('email', $email)->first();

        if ($row) {
            DB::table('newsletter_subscribers')->where('id', $row->id)->update(['unsubscribed_at' => null, 'consent_at' => now(), 'updated_at' => now()]);
        } else {
            DB::table('newsletter_subscribers')->insert([
                'email' => $email,
                'token' => Str::random(48),
                'consent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect($back)->with('newsletter_status', 'Merci, vous êtes inscrit·e à la newsletter GANXÔ.');
    }

    public function unsubscribe($token)
    {
        $n = DB::table('newsletter_subscribers')->where('token', $token)->update(['unsubscribed_at' => now(), 'updated_at' => now()]);

        return redirect(url('/') . '#newsletter')->with($n ? 'newsletter_status' : 'newsletter_error', $n ? 'Vous êtes désinscrit·e de la newsletter.' : 'Lien de désinscription invalide.');
    }
}
