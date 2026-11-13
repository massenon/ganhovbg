<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Articles\Article;
use App\Models\Customer;
use App\Models\Pages;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Recherche globale de l'administration (barre supérieure) : alertes, victimes/clients, actualités, FAQ, pages.
 * Résultats groupés, limités à 5 par groupe, filtrés selon les permissions du rôle (et, pour un prestataire,
 * aux alertes qui lui sont attribuées ou qui relèvent de ses groupes).
 */
class AdminSearchController extends Controller
{
    private function like(string $q): string
    {
        return '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
    }

    public function search(Request $request)
    {
        $q = trim(preg_replace('/\s+/u', ' ', (string) $request->query('q', '')));
        $q = mb_substr($q, 0, 60);
        if (mb_strlen($q) < 2) {
            return response()->json(['query' => $q, 'groups' => []]);
        }

        $user = Auth::user();
        $like = $this->like($q);
        $groups = [];

        // Alertes (numéro SP-… ou objet)
        if ($user->can('Ticket Access') || $user->can('Ticket Edit')) {
            $tq = Ticket::query()->where(function ($w) use ($like) {
                $w->where('ticket_id', 'like', $like)->orWhere('subject', 'like', $like);
            });
            if ($user->dashboard !== 'Admin' && !$user->hasRole('superadmin')) {
                $me = $user->id;
                $tq->where(function ($w) use ($me) {
                    $w->where('selfassignuser_id', $me)
                        ->orWhere('myassignuser_id', $me)
                        ->orWhereIn('id', DB::table('ticketassignchildren')->where('toassignuser_id', $me)->select('ticket_id'))
                        ->orWhereIn('category_id', DB::table('groups_categories')
                            ->join('groups_users', 'groups_users.groups_id', '=', 'groups_categories.group_id')
                            ->where('groups_users.users_id', $me)->select('groups_categories.category_id'));
                });
            }
            $items = [];
            foreach ($tq->latest('updated_at')->limit(5)->get(['ticket_id', 'subject', 'status']) as $t) {
                $items[] = ['label' => $t->ticket_id . ' · ' . $t->subject, 'hint' => gn_statut($t->status), 'url' => route('admin.ticketshow', $t->ticket_id)];
            }
            if ($items) { $groups[] = ['key' => 'alertes', 'label' => 'Alertes', 'items' => $items]; }
        }

        // Victimes / clients
        if ($user->can('Customers Access')) {
            $items = [];
            $rows = Customer::query()->where(function ($w) use ($like) {
                $w->where('firstname', 'like', $like)->orWhere('lastname', 'like', $like)->orWhere('username', 'like', $like)
                    ->orWhere('email', 'like', $like)->orWhere(DB::raw("concat(firstname,' ',lastname)"), 'like', $like);
            })->limit(5)->get(['id', 'firstname', 'lastname', 'username', 'email']);
            foreach ($rows as $c) {
                $name = trim($c->firstname . ' ' . $c->lastname) ?: $c->username;
                $items[] = ['label' => $name, 'hint' => $c->email, 'url' => url('administration/customer/' . $c->id)];
            }
            if ($items) { $groups[] = ['key' => 'victimes', 'label' => 'Victimes et clients', 'items' => $items]; }
        }

        // Actualités
        if ($user->can('Article Access')) {
            $items = [];
            foreach (Article::query()->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)->orWhere('tags', 'like', $like);
            })->latest()->limit(5)->get(['id', 'title', 'status']) as $a) {
                $items[] = ['label' => $a->title, 'hint' => gn_statut($a->status), 'url' => route('admin.article', $a->id)];
            }
            if ($items) { $groups[] = ['key' => 'actualites', 'label' => 'Actualités', 'items' => $items]; }
        }

        // FAQ
        if ($user->can('FAQs Access') && Schema::hasTable('faq_list')) {
            $items = [];
            foreach (DB::table('faq_list')->where('question', 'like', $like)->limit(5)->get(['id', 'question']) as $f) {
                $items[] = ['label' => $f->question, 'hint' => 'Question fréquente', 'url' => route('faq.edit', $f->id)];
            }
            if ($items) { $groups[] = ['key' => 'faq', 'label' => 'Questions fréquentes', 'items' => $items]; }
        }

        // Pages
        if ($user->can('Pages Access')) {
            $items = [];
            foreach (Pages::query()->where('pagename', 'like', $like)->limit(5)->get(['id', 'pagename']) as $p) {
                $items[] = ['label' => $p->pagename, 'hint' => 'Page', 'url' => route('pages.show', $p->id)];
            }
            if ($items) { $groups[] = ['key' => 'pages', 'label' => 'Pages', 'items' => $items]; }
        }

        return response()->json(['query' => $q, 'groups' => $groups]);
    }
}
