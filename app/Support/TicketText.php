<?php

namespace App\Support;

/**
 * Affichage sûr du texte d'une alerte (colonne tickets.message).
 *
 * - Nouveau format (formulaire public, assistant, forum) : texte brut -> échappé, retours à la ligne conservés.
 * - Ancien format (éditeur riche d'avant) : HTML -> seules des balises de mise en forme simples sont conservées,
 *   sans aucun attribut (donc ni script, ni gestionnaire d'événement, ni lien javascript:).
 */
class TicketText
{
    private const ALLOWED = '<p><br><b><strong><u><i><em><ul><ol><li>';

    public static function html($text): string
    {
        $text = (string) $text;
        if ($text === '') {
            return '';
        }
        // HTML de l'ancien éditeur : commence par une balise de bloc, ou contient </p> / <br>
        if (!preg_match('/^\s*<(p|ul|ol|div|h[1-6])\b|<\/p>|<br\s*\/?>/i', $text)) {
            return nl2br(e(str_replace(["\r\n", "\r"], "\n", $text)), false);
        }
        // Les contenus de <script>/<style> sont retirés avec leurs balises
        $text = preg_replace('#<(script|style)\b[^>]*>.*?</\1\s*>#is', '', $text);
        $text = strip_tags($text, self::ALLOWED);
        // Plus aucun attribut sur les balises conservées
        return preg_replace('/<(\/?)([a-z][a-z0-9]*)\b[^>]*?(\/?)>/i', '<$1$2$3>', $text);
    }
}
