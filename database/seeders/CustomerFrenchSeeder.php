<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Espace client : valeurs françaises des chaînes lang() qui s'affichaient encore en anglais (profil, dossier d'alerte,
 * notifications, évaluation). Idempotent : met à jour la ligne fr existante (même clé, même groupe) ou la crée.
 * Lancer : php artisan db:seed --class=CustomerFrenchSeeder
 */
class CustomerFrenchSeeder extends Seeder
{
    public function run()
    {
        $rows = [];
        foreach ($this->dictionary() as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                $rows[] = [$key, strtolower($group), $value];
            }
        }

        // La table contient des doublons (lang() insère une ligne vide à chaque clé manquante) : on met à jour TOUTES
        // les lignes fr de la clé et du groupe, sinon lang() peut relire un doublon encore vide.
        $now = now();
        foreach ($rows as [$key, $group, $value]) {
            $n = DB::table('translates')->where('lang_code', 'fr')->where('key', $key)->where('group_langname', $group)
                ->update(['value' => $value, 'updated_at' => $now]);
            if (!$n) {
                DB::table('translates')->insert(['lang_code' => 'fr', 'group_langname' => $group, 'key' => $key, 'value' => $value, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    private function dictionary(): array
    {
        return [
            'General' => [
                'Personal setting' => 'Paramètres personnels',
                'Delete Account' => 'Supprimer mon compte',
                'Once you delete your account, you can not access your account with the same credentials. You need to re-register your account.' => 'Une fois votre compte supprimé, vous ne pourrez plus vous connecter avec les mêmes identifiants. Vous devrez créer un nouveau compte.',
                'Warning! You are about to delete your account.' => 'Attention : vous êtes sur le point de supprimer votre compte.',
                'This action can not be undo. This will permanently delete your account' => 'Cette action est irréversible : votre compte sera définitivement supprimé.',
                'Rating' => 'Évaluation',
                'langconvert.menu.feedbackrecent' => 'Vos retours',
                'We Love Your Feedback!' => 'Votre avis nous aide !',
                'We are always looking for ways to improve and would love to know how we did recently.' => 'Nous cherchons toujours à nous améliorer. Dites-nous comment s’est passé votre échange avec l’équipe.',
                'Worst' => 'Très insatisfaisant',
                'Poor' => 'Insatisfaisant',
                'Average' => 'Correct',
                'Good' => 'Bien',
                'Excellent' => 'Excellent',
                'Add a Comment about the quality of support you received (optional):' => 'Ajoutez un commentaire sur l’accompagnement reçu (facultatif) :',
                "Voir l'arlete" => 'Voir l’alerte',
                'Online' => 'En ligne',
                'online' => 'en ligne',
                'offline' => 'hors ligne',
                'Puchase Code' => 'Code d’achat',
                'Arlete VBG Information' => 'Informations sur l’alerte',
                'Create Ticket' => 'Nouvelle alerte',
                'Arlete ID' => 'Numéro de l’alerte',
                'Project' => 'Projet',
                'Opened Date' => 'Date d’ouverture',
                'Reply Status' => 'État de la réponse',
                'Print' => 'Imprimer',
                'Created Date' => 'Créée',
                'This ticket is closed. Do you want to reopen it?' => 'Cette alerte est clôturée. Souhaitez-vous la rouvrir ?',
                'This ticket is closed.' => 'Cette alerte est clôturée.',
                'Do you want to reopen it?' => 'Souhaitez-vous la rouvrir ?',
                'Note' => 'Note',
                'You have reached maximum allowed replies to this ticket, please try after sometime.' => 'Vous avez atteint le nombre maximal de réponses pour cette alerte. Réessayez plus tard.',
                'Upload File' => 'Joindre un fichier',
                'Inprogress' => 'Prise en charge',
                'Leave as current' => 'Laisser en l’état',
                'Solved' => 'Résolue',
                'Reply Ticket' => 'Répondre à l’alerte',
                'Conversions' => 'Conversation',
                'Update' => 'Envoyer',
                'Ticket Category :' => 'Catégorie :',
                'Status :' => 'Statut :',
                'Reply Status :' => 'État de la réponse :',
                'Created' => 'Créée',
                'Replied By' => 'Répondu par',
                'Last Replied' => 'Dernière réponse',
                'View Ticket' => 'Voir l’alerte',
                'Supprimer Arlette' => 'Supprimer l’alerte',
                'Détails Arlette' => 'Détails de l’alerte',
                'Today' => 'Aujourd’hui',
                'Yesterday' => 'Hier',
                'View' => 'Voir',
                'Support Policy' => 'Politique d’assistance',
                'Nouvelle Arlette VBG' => 'Nouvelle alerte VBG',
                'Arlette VBG fermées' => 'Alertes VBG clôturées',
                'Alertes VBG en attente' => 'Alertes VBG en attente',
                'Alerte VBG non résolues' => 'Alertes VBG non résolues',
                'New' => 'Reçue',
                'Re-Open' => 'Rouverte',
                'On-Hold' => 'En pause',
                'Closed' => 'Clôturée',
                'Waiting for response' => 'En attente de réponse',
                'Unanswered' => 'Sans réponse',
                'Low' => 'Faible',
                'Medium' => 'Moyenne',
                'High' => 'Élevée',
                'Critical' => 'Critique',
            ],
            'alerts' => [
                'Are you sure you want to remove the profile image?' => 'Voulez-vous vraiment retirer votre photo de profil ?',
                'Are you sure you want to continue?' => 'Voulez-vous vraiment continuer ?',
                'This might erase your records permanently' => 'Cela peut supprimer définitivement vos données.',
                'Check the all field(*) required' => 'Vérifiez les champs obligatoires (*).',
            ],
            'filesetting' => [
                'The file size should not be more than' => 'La taille du fichier ne doit pas dépasser',
                'MB' => 'Mo',
            ],
            'menu' => [
                'Loading..' => 'Chargement…',
                'Notification' => 'Notifications',
            ],
            'notification' => [
                'New Ticket' => 'Nouvelle alerte',
                'Your new ticket has been created' => 'Votre nouvelle alerte a été créée',
                'Closed Ticket' => 'Alerte clôturée',
                'Your ticket has been closed' => 'Votre alerte a été clôturée',
                'On-Hold Ticket' => 'Alerte en pause',
                'Your ticket status is On-Hold' => 'Votre alerte est en pause',
                'Overdue Ticket' => 'Alerte en retard',
                'Your ticket status is overdue' => 'Votre alerte est en retard',
                'Your ticket status is Overdue' => 'Votre alerte est en retard',
                'Your ticket status is Suspend' => 'Votre alerte est suspendue',
                'Re-Open Ticket' => 'Alerte rouverte',
                'Your ticket has been Reopened' => 'Votre alerte a été rouverte',
                'Inprogress Ticket' => 'Alerte prise en charge',
                'You got a new reply on this ticket' => 'Vous avez reçu une nouvelle réponse sur cette alerte',
                'There are no notifications. We will notify you when the new notification arrives.' => 'Aucune notification pour le moment. Nous vous avertirons dès qu’il y en aura une.',
                'Filter Notifications' => 'Filtrer les notifications',
                'Sort by Status' => 'Trier par statut',
                'Re-Open Tickets' => 'Alertes rouvertes',
                'Inprogress Tickets' => 'Alertes prises en charge',
                'Custom Notifications' => 'Messages de l’équipe',
                'New Notifications' => 'Nouvelles notifications',
            ],
        ];
    }
}
