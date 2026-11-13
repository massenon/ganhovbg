<?php

/**
 * Compatibilité des anciennes URL (/customer/..., /admin/...) avec les nouvelles (/espace-client/..., /administration/...).
 * Appelé tout au début de public/index.php, AVANT le démarrage de Laravel :
 *  - GET/HEAD : redirection 301 vers la nouvelle URL (paramètres de requête conservés) ;
 *  - autres méthodes (POST, DELETE…) : réécriture interne de l'URI (une 301 transformerait le POST en GET), ce qui
 *    garde fonctionnels les formulaires et appels AJAX encore codés en dur avec l'ancienne adresse.
 * Les NOMS de routes n'ont pas changé : route('...') produit toujours la nouvelle adresse.
 */
function gn_legacy_url_map(string $path): ?string
{
    $path = '/' . ltrim($path, '/');

    // ---- Administration : préfixe + pages d'authentification ----
    if (preg_match('#^/admin(/.*)?$#', $path, $m)) {
        $rest = $m[1] ?? '';
        $auth = [
            '#^/login$#' => '/connexion',
            '#^/logout$#' => '/deconnexion',
            '#^/password/reset/(.+)$#' => '/mot-de-passe/reinitialiser/$1',
            '#^/password/reset$#' => '/mot-de-passe/oublie',
            '#^/password/email$#' => '/mot-de-passe/email',
            '#^/change-password$#' => '/changer-mot-de-passe',
            '#^/validatelog$#' => '/validation-connexion',
            '#^/([^/]+)/reset-password$#' => '/lien-reinitialisation/$1',
        ];
        foreach ($auth as $re => $to) {
            if (preg_match($re, $rest)) {
                // POST /admin/password/reset (envoi du nouveau mot de passe) garde son adresse « reinitialiser »
                if ($re === '#^/password/reset$#' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
                    return '/administration/mot-de-passe/reinitialiser';
                }
                return '/administration' . preg_replace($re, $to, $rest);
            }
        }
        return '/administration' . $rest;
    }

    // ---- Espace client ----
    if (preg_match('#^/customer(/.*)?$#', $path, $m)) {
        $rest = $m[1] ?? '';
        if ($rest === '' || $rest === '/') {
            return '/espace-client';
        }
        $rules = [
            '#^/login/(.+)/callback$#' => '/connexion/$1/retour',
            '#^/login/(.+)$#' => '/connexion/$1',
            '#^/login$#' => '/connexion',
            '#^/ajaxlogin$#' => '/connexion-ajax',
            '#^/logout$#' => '/deconnexion',
            '#^/emailverification$#' => '/verification-email',
            '#^/emailverificationstore/(.+)$#' => '/verification-email/$1',
            '#^/register1$#' => '/inscription-ajax',
            '#^/register$#' => '/inscription',
            '#^/forgotpasswordajax$#' => '/mot-de-passe-oublie-ajax',
            '#^/forgotpassword$#' => '/mot-de-passe-oublie',
            '#^/change-password$#' => '/profil/mot-de-passe',
            '#^/reset-password$#' => '/mot-de-passe/reinitialiser',
            '#^/user/verify/(.+)$#' => '/verification/$1',
            '#^/notification/(.+)$#' => '/notifications/$1',
            '#^/notification$#' => '/notifications',
            '#^/mark-as-read$#' => '/notifications/marquer-lu',
            '#^/markAsRead$#' => '/notifications/tout-lire',
            '#^/notify/delete$#' => '/notifications/supprimer',
            '#^/markallnotify$#' => '/notifications/tout-marquer-lu',
            '#^/notifystatus$#' => '/notifications/statut',
            '#^/notifysearch$#' => '/notifications/recherche',
            '#^/cnotificationalerts$#' => '/notifications/alertes',
            '#^/cnotificationsreading$#' => '/notifications/lecture',
            '#^/cbadgecount$#' => '/notifications/compteur',
            '#^/cmarkasreadcount$#' => '/notifications/compteur-lu',
            '#^/profile$#' => '/profil',
            '#^/deleteaccount/(.+)$#' => '/profil/supprimer/$1',
            '#^/image/remove/(.+)$#' => '/profil/photo/$1',
            '#^/custsettings$#' => '/preferences/reglages',
            '#^/generalsetting$#' => '/preferences',
            '#^/general/notification$#' => '/preferences/notifications',
            '#^/ticket/view/([^/]+)$#' => '/alertes/$1',
            '#^/ticket/imageupload/([^/]+)$#' => '/alertes/$1/pieces-jointes',
            '#^/ticket/delete/tickets$#' => '/alertes/supprimer-plusieurs',
            '#^/ticket/delete/([^/]+)$#' => '/alertes/supprimer/$1',
            '#^/ticket/editcomment/([^/]+)$#' => '/alertes/commentaire/$1',
            '#^/ticket/pdfmake/([^/]+)$#' => '/alertes/$1/pdf',
            '#^/ticket/([^/]+)$#' => '/alertes/$1/repondre', // POST d'une réponse (la création POST /ticket est traitée plus bas)
            '#^/ticket$#' => '/alertes/nouvelle',
            '#^/imageupload$#' => '/pieces-jointes',
            '#^/activeticket$#' => '/alertes/suivies',
            '#^/closedticket$#' => '/alertes/resolues',
            '#^/onholdticket$#' => '/alertes/en-attente',
            '#^/closed/([^/]+)$#' => '/alertes/$1/cloturer',
            '#^/image/delete/([^/]+)$#' => '/alertes/piece-jointe/$1',
            '#^/subcat$#' => '/categories/sous-categories',
            '#^/rating/star([1-5])/([^/]+)$#' => '/alertes/$2/evaluation/etoile-$1',
            '#^/rating/([^/]+)$#' => '/alertes/$1/evaluation',
            '#^/([^/]+)/reset-password$#' => '/mot-de-passe/reinitialiser/$1',
        ];
        foreach ($rules as $re => $to) {
            if (preg_match($re, $rest)) {
                return '/espace-client' . preg_replace($re, $to, $rest);
            }
        }
        return '/espace-client' . $rest;
    }

    return null;
}

function gn_legacy_url_redirect(): void
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $qs = '';
    if (($i = strpos($uri, '?')) !== false) {
        $qs = substr($uri, $i);
        $uri = substr($uri, 0, $i);
    }

    // Sous-dossier d'installation éventuel (ex. http://hote/ganhovbg/public) : on ne traite que le chemin relatif au script.
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    $relative = ($base !== '' && strpos($uri, $base) === 0) ? substr($uri, strlen($base)) : $uri;

    $new = gn_legacy_url_map(rawurldecode($relative));
    if ($new === null || $new === $relative) {
        return;
    }
    $new = implode('/', array_map('rawurlencode', explode('/', $new)));

    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method === 'GET' || $method === 'HEAD') {
        header('Location: ' . $base . $new . $qs, true, 301);
        exit;
    }

    // Réécriture interne pour les autres méthodes
    $_SERVER['REQUEST_URI'] = $base . $new . $qs;
    $_SERVER['PATH_INFO'] = $new;
    $_SERVER['SCRIPT_NAME'] = $base . '/index.php';
    $_SERVER['PHP_SELF'] = $base . '/index.php';
}

gn_legacy_url_redirect();
