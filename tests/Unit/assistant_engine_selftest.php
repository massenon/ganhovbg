<?php
/**
 * Test unitaire en PHP pur du moteur de l'assistant (aucune base, aucune écriture, aucun RefreshDatabase).
 * Lancer : php tests/Unit/assistant_engine_selftest.php   (code de sortie 0 = tout est vert)
 */
require __DIR__ . '/../../vendor/autoload.php';

use App\Models\AssistantSetting;
use App\Services\AssistantEngine as E;
use Database\Seeders\AssistantSeeder;

$kb = [];
foreach (AssistantSeeder::entries() as $i => $e) {
    $kb[] = $e + ['id' => $i + 1, 'active' => true];
}
$set = AssistantSetting::defaults();
$links = ['alert' => '/guest/openticket', 'forum' => 'http://forum.example'];
$fail = 0; $n = 0;
$check = function ($label, $cond, $extra = '') use (&$fail, &$n) {
    $n++;
    if (!$cond) { $fail++; echo "ECHEC  $label $extra\n"; }
};
$r = function ($t, $s = null, $k = null) use (&$kb, &$set, $links) { return E::respond($t, $k ?? $kb, $s ?? $set, $links); };

// normalisation / racines
$check('normalize accents', E::normalize('Déprimé·e, À l’aide !') === 'deprimee a l aide');
$check('synonyme viol', E::concept('violee') === 'viol' && E::concept('violence') === 'violence' && E::concept('violents') === 'violence');
$check('synonyme depression', E::concept('deprime') === E::concept('depression') && E::concept('triste') === E::concept('depression'));

// chaque question de la base renvoie sa propre entrée
foreach ($kb as $e) {
    $x = $r($e['question']);
    $check('question propre #' . $e['id'], $x['kb_id'] === $e['id'] || $x['kind'] === 'no_diagnosis', "kind={$x['kind']} id=" . var_export($x['kb_id'], true));
}

// requêtes naturelles -> entrée attendue (id)
$cases = [
    'Comment créer une alerte ?' => 2, 'c est quoi une alerte' => 1, 
    'Que fait le bouton SOS ?' => 3, 'est-ce que le sos prévient la police' => 3, 'localisation gps' => 3,
    'mes données sont-elles confidentielles' => 4, 'est ce anonyme' => 4,
    'quels types de violences' => 5, 'violence verbale' => 5, 'rejet familial' => 5,
    'J’ai été violée' => 6, 'que faire après une agression sexuelle' => 6, 'violence sexuelle' => 6,
    'on me fait du chantage avec des photos en ligne' => 7, 'harcèlement sur les réseaux sociaux' => 7, 'cyber harcelement' => 7,
    'chantage à l’outing' => 8, 'je subis de l’homophobie' => 8, 'discrimination LGBTQ' => 8, 'transphobie au travail' => 8,
    'c est quoi le consentement' => 9,
    'Je me sens déprimé·e' => 10, 'je fais une depression' => 10, 'je suis très triste en ce moment' => 10, 'idées noires' => 10,
    'je fais des crises d’angoisse' => 11, 'comment me calmer' => 11, 'exercice de respiration' => 11,
    'mon amie est victime de violence comment l aider' => 12, 'je suis témoin d une situation' => 12,
    'forum' => 13, 'comment partager mon expérience' => 13,
    'application mobile' => 14, 'puis-je télécharger l appli' => 14,
    'comment vous contacter' => 15, 'écrire à l équipe' => 15,
    'je cherche un psychologue' => 16, 'trouver un avocat' => 16,
    'comment porter plainte' => 17, 'quels sont mes droits' => 17,
];
foreach ($cases as $q => $want) {
    $x = $r($q);
    $check("« $q » -> #$want", $x['kb_id'] === $want, 'obtenu: ' . var_export($x['kb_id'], true) . ' (' . $x['kind'] . ')');
}

// typos et variantes d'accent
foreach (['depresion' => 10, 'DEPRESSION!!!' => 10, 'harcelment en ligne' => 7, 'consentment' => 9, 'agrésion sexuèlle' => 6] as $q => $want) {
    $x = $r($q);
    $check("typo « $q » -> #$want", $x['kb_id'] === $want, 'obtenu: ' . var_export($x['kb_id'], true) . ' (' . $x['kind'] . ')');
}

// escalade (avec variantes d'accent et fautes)
foreach (['Je suis en danger', 'je veux me tuer', 'je vais me suicider', 'suiside', 'il va me tuer', 'IL ME MENACE avec un couteau', 'il m’a frappée', 'danger imédiat', 'au secour', 'je veux mettre fin à mes jours', 'je n’ai plus envie de vivre'] as $q) {
    $x = $r($q);
    $check("escalade « $q »", $x['kind'] === 'escalation' && $x['escalate'] === true && count($x['cta']) === 2, "kind={$x['kind']}");
    $check("escalade CTA « $q »", isset($x['cta'][0]['url']) && $x['cta'][1]['url'] === '#gn-sos');
}
// pas de faux positif évident
foreach (['Comment créer une alerte ?', 'Je me sens déprimé·e', 'bonjour', 'quels types de violences'] as $q) {
    $check("pas d'escalade « $q »", $r($q)['kind'] !== 'escalation');
}
// message d'escalade : formulation imposée par le client
$esc = $r('je veux me tuer')['answer'];
$check('message escalade contient la formulation client', strpos($esc, 'joignez d’abord les secours de votre pays ; le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ') !== false);
// désactivation de l'escalade / du bandeau
$s2 = $set; $s2['g_sos_banner'] = '0';
$x = $r('je veux me tuer', $s2); $check('bandeau désactivé : escalate=false mais message urgent conservé', $x['escalate'] === false && $x['kind'] === 'escalation');
$s2 = $set; $s2['escalation_enabled'] = '0';
$x = $r('je veux me tuer', $s2); $check('escalade désactivée', $x['kind'] !== 'escalation');
$s2 = $set; $s2['escalation_keywords'] = 'mot-special-admin';
$x = $r('voici un mot special admin', $s2); $check('mots-clés d’escalade administrables', $x['kind'] === 'escalation');

// hors sujet / pas de réponse
$x = $r('quelle est la meilleure recette de crêpes'); $check('hors sujet', $x['kind'] === 'offtopic' && $x['matched'] === false && strpos($x['answer'], 'ne peux pas') !== false, $x['kind']);
$s2 = $set; $s2['g_topic_filter'] = '0';
$x = $r('quelle est la meilleure recette de crêpes', $s2); $check('filtre de sujet désactivé -> repli', $x['kind'] === 'fallback');
$x = $r('xyzzy plugh'); $check('inconnu -> repli (ou hors sujet) sans entrée', $x['matched'] === false && $x['kb_id'] === null);
$x = $r('je ne sais pas trop'); $check('vague -> repli avec suggestions', $x['matched'] === false && count($x['suggestions']) === 3, $x['kind']);

// salutations
$x = $r('Bonjour'); $check('salutation', $x['kind'] === 'greeting' && $x['matched'] === false);
$x = $r('merci beaucoup'); $check('remerciement', $x['kind'] === 'thanks');

// aucun diagnostic
foreach (['suis-je dépressif ?', 'est-ce que j’ai une dépression ?', 'donne moi un diagnostic', 'ai-je un trouble bipolaire'] as $q) {
    $x = $r($q); $check("diagnostic refusé « $q »", $x['kind'] === 'no_diagnosis' && strpos($x['answer'], 'ne peux pas poser de diagnostic') !== false, $x['kind']);
}
$s2 = $set; $s2['g_no_diagnosis'] = '0';
$x = $r('suis-je dépressif ?', $s2); $check('garde-fou diagnostic désactivé', $x['kind'] !== 'no_diagnosis');

// données personnelles : rappel, jamais de demande
$x = $r('Comment créer une alerte ? mon mail est test@example.org'); $check('rappel PII', strpos($x['answer'], 'ne saisissez pas ici d’informations') !== false);
$x = $r('Comment créer une alerte ?'); $check('pas de rappel PII sans donnée', strpos($x['answer'], 'ne saisissez pas ici') === false);

// CTA, suggestions, structure
$x = $r('Comment créer une alerte ?');
$check('CTA alerte résolu', $x['cta'] === [['label' => 'Créer une alerte', 'url' => '/guest/openticket']], json_encode($x['cta']));
$check('suggestions ≤ 3, sans la réponse elle-même', count($x['suggestions']) <= 3 && !in_array('Comment créer une alerte sans compte ?', $x['suggestions'], true));
$x = $r('forum'); $check('CTA forum', $x['cta'][0]['url'] === 'http://forum.example');
$bad = $kb; $bad[0]['cta_url'] = 'javascript:alert(1)'; $x = $r('c est quoi une alerte', null, $bad); $check('URL CTA dangereuse rejetée', $x['cta'] === []);
$keys = array_keys($r('forum')); sort($keys);
$check('forme de la réponse', $keys === ['answer', 'cta', 'escalate', 'kb_id', 'kind', 'matched', 'start_alert', 'suggestions'], implode(',', $keys));
$off = $kb; $off[1]['active'] = false; $x = $r('Comment créer une alerte sans compte ?', null, $off); $check('entrée inactive ignorée', $x['kb_id'] !== 2);

// intention « créer une alerte » (kind=start_alert) : envie exprimée, pas simple question
foreach (['je veux signaler', 'Je veux dénoncer une agression', 'créer une alerte', 'Créer une alerte avec l’assistant', 'je veux faire un signalement',
          'je voudrais créer une alerte', 'signaler un harcèlement', 'je souhaite témoigner', 'faire une alerte', 'aidez-moi à signaler une situation'] as $q) {
    $x = $r($q);
    $check("start_alert « $q »", $x['kind'] === 'start_alert' && $x['start_alert'] === true && $x['cta'] === [], $x['kind']);
}
foreach (['Comment créer une alerte ?', 'c est quoi une alerte', 'Comment créer une alerte sans compte ?', 'puis-je signaler anonymement ?', 'Que fait le bouton SOS ?', 'forum', 'bonjour', 'quels types de violences'] as $q) {
    $x = $r($q);
    $check("pas de start_alert « $q »", $x['start_alert'] === false && $x['kind'] !== 'start_alert', $x['kind']);
}
$x = $r('je veux dénoncer, il va me tuer');
$check('escalade + intention : bannière ET start_alert', $x['kind'] === 'escalation' && $x['escalate'] === true && $x['start_alert'] === true);
$x = $r('je veux signaler', $set, []);
$check('start_alert sans base de connaissances', $x['kind'] === 'start_alert');
$check('start_alert : jamais de promesse police/secours', !preg_match('/police|secours|urgence/iu', $x['answer']));

// règles de rédaction du client sur toute la base et les textes par défaut
$all = json_encode([$kb, $set], JSON_UNESCAPED_UNICODE);
$check('pas de « pas un service d’urgence »', !preg_match('/n.est pas un service d.urgence|pas un service d.urgence/iu', $all));
$check('pas de LGBTQIA', stripos($all, 'LGBTQIA') === false && stripos($all, 'LGBTQ+') === false);
$check('pas de numéro de téléphone', !preg_match('/\d{2}[ .]\d{2}[ .]\d{2}/', $all));
$check('pas de citation de loi', !preg_match('/\bloi n|article \d|décret/iu', $all));

echo ($fail === 0 ? "OK" : "ECHEC") . " : " . ($n - $fail) . "/$n vérifications\n";
exit($fail === 0 ? 0 : 1);
