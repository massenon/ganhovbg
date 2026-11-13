<?php

namespace App\Services;

use App\Models\Groupsusers;
use App\Models\Ticket\Category;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Données communes à l'export Excel et au rapport PDF des alertes VBG.
 * - Périmètre : un·e superadmin voit tout ; un·e prestataire ne voit que ses cas (affectés à elle/lui, auto-attribués,
 *   ou rattachés à une catégorie de l'un de ses groupes).
 * - Filtres : statut, catégorie, période (du / au), recherche (numéro ou objet).
 * - Aucun contenu de message n'est lu : seulement des métadonnées.
 */
class AlertReportService
{
    /** Alertes visibles par l'utilisateur, filtrées. */
    public static function query($user, Request $r)
    {
        $q = Ticket::query()->whereNull('tickets.deleted_at');

        if ($user->dashboard !== 'Admin') {
            $uid = (int) $user->id;
            $cats = DB::table('groups_categories')
                ->join('groups_users', 'groups_users.groups_id', '=', 'groups_categories.group_id')
                ->where('groups_users.users_id', $uid)->pluck('groups_categories.category_id')->all();
            $q->where(function ($w) use ($uid, $cats) {
                $w->where('tickets.myassignuser_id', $uid)
                    ->orWhere('tickets.selfassignuser_id', $uid)
                    ->orWhereIn('tickets.id', DB::table('ticketassignchildren')->where('toassignuser_id', $uid)->pluck('ticket_id'))
                    ->orWhereIn('tickets.category_id', $cats ?: [0]);
            });
        }

        if ($r->filled('statut')) {
            $q->where('tickets.status', $r->input('statut'));
        }
        if ($r->filled('categorie')) {
            $q->where('tickets.category_id', (int) $r->input('categorie'));
        }
        [$from, $to] = self::period($r, false);
        if ($from) {
            $q->where('tickets.created_at', '>=', $from);
        }
        if ($to) {
            $q->where('tickets.created_at', '<=', $to);
        }
        if ($r->filled('q')) {
            $term = str_replace(['%', '_'], ['\\%', '\\_'], trim($r->input('q')));
            $q->where(function ($w) use ($term) {
                $w->where('tickets.ticket_id', 'like', "%$term%")->orWhere('tickets.subject', 'like', "%$term%");
            });
        }
        return $q;
    }

    /**
     * Période demandée : 7j | 30j | 90j | annee | perso (du/au) | tout (sans borne, défaut de l'export).
     * @return array{0:?Carbon,1:?Carbon}
     */
    public static function period(Request $r, $defaultThirty = true)
    {
        $p = $r->input('periode', $defaultThirty ? '30j' : 'tout');
        $now = Carbon::now();
        switch ($p) {
            case '7j': return [$now->copy()->subDays(7)->startOfDay(), $now];
            case '30j': return [$now->copy()->subDays(30)->startOfDay(), $now];
            case '90j': return [$now->copy()->subDays(90)->startOfDay(), $now];
            case 'annee': return [$now->copy()->startOfYear(), $now];
            case 'perso':
                try {
                    $f = $r->filled('du') ? Carbon::parse($r->input('du'))->startOfDay() : null;
                    $t = $r->filled('au') ? Carbon::parse($r->input('au'))->endOfDay() : null;
                } catch (\Throwable $e) { $f = $t = null; }
                return [$f, $t];
        }
        return [null, null];
    }

    public static function periodLabel(Request $r)
    {
        [$f, $t] = self::period($r, true);
        $fmt = function ($d) { return $d->copy()->locale('fr')->translatedFormat('j F Y'); };
        if (!$f && !$t) { return 'Toute la période'; }
        return ($f ? 'Du ' . $fmt($f) : 'Jusqu’au') . ' ' . ($t ? ($f ? 'au ' : '') . $fmt($t) : 'à aujourd’hui');
    }

    /** Heures entre deux dates (1 décimale) ou null. */
    public static function hours($a, $b)
    {
        if (!$a || !$b) { return null; }
        $h = (Carbon::parse($a)->diffInSeconds(Carbon::parse($b), false)) / 3600;
        return $h < 0 ? null : round($h, 1);
    }

    /** Une ligne par alerte (métadonnées uniquement). */
    public static function rows($user, Request $r, $withCustomer)
    {
        $tickets = self::query($user, $r)
            ->with(['category:id,name', 'cust:id,firstname,lastname', 'myassignuser:id,name'])
            ->addSelect(['first_reply' => DB::table('comments')->selectRaw('MIN(created_at)')
                ->whereColumn('comments.ticket_id', 'tickets.id')->whereNotNull('comments.user_id')->whereNull('comments.deleted_at')])
            ->orderByDesc('tickets.created_at')->get();

        $assigned = DB::table('ticketassignchildren')->join('users', 'users.id', '=', 'ticketassignchildren.toassignuser_id')
            ->whereIn('ticketassignchildren.ticket_id', $tickets->pluck('id'))->get(['ticketassignchildren.ticket_id', 'users.name'])->groupBy('ticket_id');

        $rows = [];
        foreach ($tickets as $t) {
            $agents = collect([optional($t->myassignuser)->name])->merge(($assigned[$t->id] ?? collect())->pluck('name'))->filter()->unique()->values()->implode(', ');
            $closed = strtolower((string) $t->status) === 'closed';
            $rows[] = [
                'numero' => $t->ticket_id,
                'objet' => (string) $t->subject,
                'categorie' => optional($t->category)->name ?: '—',
                'statut' => gn_statut($t->status),
                'statut_brut' => (string) $t->status,
                'priorite' => gn_statut($t->priority),
                'cree' => $t->created_at,
                'maj' => $t->updated_at,
                'agent' => $agents ?: '—',
                'victime' => $withCustomer ? trim(optional($t->cust)->firstname . ' ' . optional($t->cust)->lastname) ?: '—' : null,
                'h_reponse' => self::hours($t->created_at, $t->first_reply),
                'h_resolution' => $closed ? self::hours($t->created_at, $t->closing_ticket ?: $t->updated_at) : null,
                'agent_ids' => $agents,
                'closed' => $closed,
            ];
        }
        return $rows;
    }

    /** Agrégats pour le rapport PDF. */
    public static function stats($user, Request $r)
    {
        $rows = self::rows($user, $r, false);
        $n = count($rows);
        $by = function ($key) use ($rows) {
            $c = [];
            foreach ($rows as $row) { $c[$row[$key]] = ($c[$row[$key]] ?? 0) + 1; }
            arsort($c);
            return $c;
        };
        $closed = count(array_filter($rows, function ($x) { return $x['closed']; }));
        $pending = count(array_filter($rows, function ($x) { return in_array(strtolower($x['statut_brut']), ['new', 'onhold', 'on-hold'], true); }));
        $followed = count(array_filter($rows, function ($x) { return !$x['closed'] && !in_array(strtolower($x['statut_brut']), ['new', 'onhold', 'on-hold'], true); }));
        $avg = function ($key) use ($rows) {
            $v = array_filter(array_column($rows, $key), function ($x) { return $x !== null; });
            return count($v) ? round(array_sum($v) / count($v), 1) : null;
        };

        $weeks = [];
        foreach ($rows as $row) {
            $k = Carbon::parse($row['cree'])->startOfWeek()->format('Y-m-d');
            $weeks[$k] = ($weeks[$k] ?? 0) + 1;
        }
        ksort($weeks);

        $agents = [];
        foreach ($rows as $row) {
            if ($row['agent'] === '—') { continue; }
            foreach (explode(', ', $row['agent']) as $a) {
                $agents[$a]['total'] = ($agents[$a]['total'] ?? 0) + 1;
                $agents[$a]['closed'] = ($agents[$a]['closed'] ?? 0) + ($row['closed'] ? 1 : 0);
            }
        }
        uasort($agents, function ($a, $b) { return $b['total'] <=> $a['total']; });

        return [
            'total' => $n, 'pending' => $pending, 'followed' => $followed, 'closed' => $closed,
            'rate' => $n ? round($closed * 100 / $n) : 0,
            'avg_reply' => $avg('h_reponse'), 'avg_resolution' => $avg('h_resolution'),
            'categories' => $by('categorie'), 'statuts' => $by('statut'), 'priorites' => $by('priorite'),
            'weeks' => $weeks, 'agents' => array_slice($agents, 0, 10, true),
        ];
    }

    public static function categories()
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }
}
