<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Seosetting;
use App\Models\Ticket\Ticket;
use Auth;

/**
 * Espace client Ganho : listes d'alertes par état (en attente / suivies / résolues).
 * Correspondance avec les statuts de la table tickets :
 *   en attente = New + On-Hold ; suivies = Inprogress + Re-Open ; résolues = Closed.
 */
class GnClientController extends Controller
{
    public const ETATS = [
        'en-attente' => ['statuts' => ['New', 'On-Hold'], 'titre' => 'Alertes en attente', 'lead' => 'Alertes envoyées, en attente d’attribution à un·e intervenant·e.', 'vide' => 'Vous n’avez aucune alerte en attente.'],
        'suivies' => ['statuts' => ['Inprogress', 'Re-Open'], 'titre' => 'Alertes suivies', 'lead' => 'Dossiers pris en charge par l’équipe GANXÔ (psychologue, assistant·e social·e, juriste).', 'vide' => 'Aucun dossier n’est suivi pour le moment.'],
        'resolues' => ['statuts' => ['Closed'], 'titre' => 'Alertes résolues', 'lead' => 'Historique des dossiers clôturés.', 'vide' => 'Aucun dossier n’a encore été clôturé.'],
    ];

    public function alertes($etat)
    {
        $conf = self::ETATS[$etat];
        $tickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)
            ->whereIn('status', $conf['statuts'])
            ->with(['category', 'toassignuser'])
            ->latest('updated_at')
            ->get();

        $data = [
            'etat' => $etat,
            'conf' => $conf,
            'tickets' => $tickets,
            'title' => Apptitle::first(),
            'footertext' => Footertext::first(),
            'seopage' => Seosetting::first(),
            'page' => Pages::all(),
        ];

        return view('user.gnalertes', $data);
    }
}
