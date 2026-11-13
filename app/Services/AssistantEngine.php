<?php

namespace App\Services;

use App\Models\AssistantKb;
use App\Models\AssistantLog;
use App\Models\AssistantSetting;

/**
 * Moteur de réponse de l'assistant GANXÔ : aucune IA générative, aucun appel externe.
 * Le visiteur écrit une phrase ; on la normalise, on la réduit à des « concepts » (radicaux + synonymes)
 * et on la compare aux entrées de la base de connaissances rédigées par l'équipe.
 *
 * Garanties : jamais de diagnostic, jamais de demande d'informations identifiantes, le texte du visiteur
 * n'est ni stocké ni journalisé (seul un compteur anonyme l'est, voir ask()).
 *
 * respond() est pure (aucun accès base, aucun helper Laravel) : elle se teste sans base.
 */
class AssistantEngine
{
    public const THRESHOLD = 3.0;
    public const MAX_LEN = 500;

    private const STOP = 'a ai aie aient ait au aux avec avoir avons ayant c ca car ce ceci cela celle celles celui ces cet cette chez ci comme comment dans de des du donc dont elle elles en encore es est et etais etait etes etre eu fait faire fais font il ils j je jusque l la le les leur leurs lui m ma mais me meme mes moi mon n ne ni nos notre nous on ou ou par pas peu peut peux plus pour pourquoi puis puisque qu quand que quel quelle quelles quels qui quoi s sa sans se ses si sois son sont sous suis sur t ta te tes toi ton tous tout toute toutes tres tu un une vos votre vous y bien aussi alors ainsi cest veux voudrais aimerais besoin svp stp quoi quelque quelques faut dois doit dit dire fois jai apres avant depuis entre vers';

    /** Groupes de synonymes : canon => formes (« * » final = préfixe). */
    private const GROUPS = [
        'viol' => ['viol', 'viols', 'viole', 'violee', 'violees', 'violer', 'violes'],
        'violence' => ['violen*'],
        'deprim' => ['deprim*', 'depress*', 'triste*', 'cafard', 'morose', 'abattu*', 'abattement'],
        'angoiss' => ['angoiss*', 'anxi*', 'stress*', 'panique*', 'paniqu*', 'nerveu*'],
        'agress' => ['agress*', 'attouch*', 'abus*'],
        'harcel' => ['harcel*', 'cyberharcel*'],
        'chantag' => ['chantag*', 'sextort*', 'racket*'],
        'outing' => ['outing*', 'outer', 'outee', 'outes'],
        'lgbtq' => ['lgbt*', 'gay*', 'lesbien*', 'homosexuel*', 'homosexualite', 'bisexuel*', 'transgenre*', 'trans', 'transidentite', 'queer', 'homophob*', 'transphob*', 'biphob*'],
        'alert' => ['alert*', 'signal*'],
        'sos' => ['sos', 'gps', 'geoloc*', 'localis*', 'position*', 'bouton*'],
        'confid' => ['confiden*', 'anonym*', 'prive*', 'secret*', 'discret*'],
        'proche' => ['ami', 'amie', 'amis', 'amies', 'proche*', 'copine', 'copain', 'soeur*', 'frere*', 'collegue*', 'voisin*', 'cousin*'],
        'plainte' => ['plainte*', 'plaindre', 'police*', 'gendarm*', 'tribunal*', 'justice', 'loi', 'lois', 'droit*', 'juridi*'],
        'pro' => ['psycholog*', 'psychiatr*', 'therapeut*', 'avocat*', 'juriste*', 'medecin*', 'docteur*', 'infirm*', 'professionnel*', 'association*'],
        'forum' => ['forum*', 'communaute*', 'entraide', 'temoign*'],
        'app' => ['applic*', 'appli', 'android', 'ios', 'telecharg*', 'mobile*', 'smartphone'],
        'contact' => ['contact*', 'ecrire', 'joindre', 'mail*', 'email*', 'courriel'],
        'famil' => ['famil*', 'parent*', 'rejet*', 'expuls*'],
        'consent' => ['consent*'],
        'compte' => ['compte*', 'inscri*', 'connexion'],
        'respir' => ['respir*', 'inspir*', 'expir*'],
        'sexuel' => ['sexuel*', 'sexe'],
        'ganxo' => ['ganxo', 'ganho'],
    ];

    /** Mots qui montrent que la question reste dans le périmètre (détresse, aide, couple…) sans être un mot-clé. */
    private const SCOPE_EXTRA = ['peur*', 'seul*', 'souffr*', 'pleur*', 'mal', 'honte*', 'culpab*', 'epuis*', 'desesp*', 'perdu*', 'victime*', 'couple*', 'mari', 'conjoint*', 'ex', 'partenaire*', 'aide*', 'aider', 'soutien*', 'ecoute*', 'parler', 'securite*', 'danger*', 'menace*', 'frapp*', 'insult*', 'humili*', 'harcel*', 'discrimin*', 'genre', 'femme*', 'homme*', 'identite*', 'orientation*', 'sante*', 'dormir', 'sommeil', 'fatigu*', 'vide', 'envie*', 'moral', 'etre', 'coming*', 'emotion*', 'rassur*', 'preuve*', 'temoin*', 'ganxo'];

    private const GREET = ['bonjour', 'salut', 'coucou', 'bonsoir', 'hello', 'hey', 'allo'];
    private const THANKS = ['merci', 'thanks', 'ok', 'daccord'];

    private static $stopMap = null;
    private static $compiled = [];

    // ------------------------------------------------------------------ normalisation

    public static function normalize(string $s): string
    {
        $s = mb_strtolower($s, 'UTF-8');
        $s = strtr($s, [
            'œ' => 'oe', 'æ' => 'ae', '’' => "'", '‘' => "'", '·' => '', '•' => '',
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ç' => 'c',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ô' => 'o', 'ö' => 'o', 'ó' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u', 'ÿ' => 'y', 'ñ' => 'n',
        ]);
        $s = preg_replace('/[^a-z0-9]+/', ' ', $s);
        return trim(preg_replace('/\s+/', ' ', $s));
    }

    private static function stopMap(): array
    {
        if (self::$stopMap === null) {
            self::$stopMap = array_flip(explode(' ', self::STOP));
        }
        return self::$stopMap;
    }

    /** Radical léger (pluriels, féminins, quelques suffixes courants). */
    public static function stem(string $w): string
    {
        if (strlen($w) <= 4 || ctype_digit($w)) {
            return $w;
        }
        if (strlen($w) > 4 && (substr($w, -1) === 's' || substr($w, -1) === 'x') && substr($w, -2) !== 'ss') {
            $w = substr($w, 0, -1);
        }
        foreach (['ements', 'ement', 'ations', 'ation', 'ateurs', 'ateur', 'ions', 'ion', 'ees', 'ee', 'er', 'ez', 'ent', 'ant', 'e'] as $suf) {
            $n = strlen($suf);
            if (strlen($w) - $n >= 4 && substr($w, -$n) === $suf) {
                return substr($w, 0, -$n);
            }
        }
        return $w;
    }

    private static function compiledGroups(): array
    {
        if (!self::$compiled) {
            foreach (self::GROUPS as $canon => $forms) {
                foreach ($forms as $f) {
                    if (substr($f, -1) === '*') {
                        self::$compiled['p'][] = [substr($f, 0, -1), $canon];
                    } else {
                        self::$compiled['e'][$f] = $canon;
                    }
                }
            }
        }
        return self::$compiled;
    }

    /** Concept d'un mot : canon du groupe de synonymes, sinon radical. */
    public static function concept(string $w): string
    {
        $c = self::compiledGroups();
        if (isset($c['e'][$w])) {
            return $c['e'][$w];
        }
        foreach ($c['p'] as [$pre, $canon]) {
            if (strpos($w, $pre) === 0) {
                return $canon;
            }
        }
        // faute de frappe dans un radical de 6 lettres ou plus (depresion, agresion...)
        if (strlen($w) >= 6) {
            foreach ($c['p'] as [$pre, $canon]) {
                $n = strlen($pre);
                if ($n >= 6 && strlen($w) >= $n && levenshtein(substr($w, 0, $n), $pre) <= 1) {
                    return $canon;
                }
            }
        }
        return self::stem($w);
    }

    /** Concepts distincts d'un texte (mots vides retirés). */
    public static function concepts(string $text): array
    {
        $stop = self::stopMap();
        $out = [];
        foreach (explode(' ', self::normalize($text)) as $w) {
            if ($w === '' || isset($stop[$w]) || (strlen($w) < 2 && !ctype_digit($w))) {
                continue;
            }
            $out[self::concept($w)] = true;
        }
        return array_keys($out);
    }

    private static function inScopeWord(string $w): bool
    {
        foreach (self::SCOPE_EXTRA as $p) {
            if (substr($p, -1) === '*' ? strpos($w, substr($p, 0, -1)) === 0 : $w === $p) {
                return true;
            }
        }
        return false;
    }

    // ------------------------------------------------------------------ escalade

    /** @param string[] $keywords mots ou expressions (déjà normalisables) */
    public static function isEscalation(string $text, array $keywords): bool
    {
        $tokens = array_values(array_filter(explode(' ', self::normalize($text)), 'strlen'));
        $nt = count($tokens);
        foreach ($keywords as $kw) {
            $words = array_values(array_filter(explode(' ', self::normalize($kw)), 'strlen'));
            $n = count($words);
            if ($n === 0 || $n > $nt) {
                continue;
            }
            for ($i = 0; $i + $n <= $nt; $i++) {
                $ok = true;
                for ($j = 0; $j < $n; $j++) {
                    if (!self::wordLike($tokens[$i + $j], $words[$j])) {
                        $ok = false;
                        break;
                    }
                }
                if ($ok) {
                    return true;
                }
            }
        }
        return false;
    }

    /** Égalité tolérante : mots courts exacts ; mots de 5+ lettres : préfixe (frappe/frappée) ou une faute de frappe. */
    private static function wordLike(string $token, string $kw): bool
    {
        if ($token === $kw) {
            return true;
        }
        $len = strlen($kw);
        if ($len < 5) {
            return false;
        }
        if (strpos($token, $kw) === 0) {
            return true;
        }
        return abs(strlen($token) - $len) <= 1 && levenshtein($token, $kw) <= 1;
    }

    // ------------------------------------------------------------------ intention « créer une alerte »

    /**
     * Vrai si le visiteur exprime l'envie de signaler / dénoncer / créer une alerte (et non une simple question
     * « comment créer une alerte ? », qui reçoit la réponse de la base de connaissances).
     */
    public static function wantsAlert(string $text): bool
    {
        $n = self::normalize($text);
        if ($n === '' || strlen($n) > 240) {
            return false;
        }
        // question d'information : réponse de la base de connaissances
        if (preg_match('/^(comment|c est quoi|qu est ce|quest ce|pourquoi|est ce|quel|quelle|quels|quelles|ou |quand|que fait|puis je|peut on|peux je|faut il|combien|qui )/', $n)) {
            return false;
        }
        // formulations directes : « créer une alerte », « faire un signalement »...
        if (preg_match('/\b(creer|faire|deposer|envoyer|enregistrer|ouvrir|lancer|transmettre|declarer|remplir|poster) (une |l |mon |un |ma |notre )?(nouvelle )?(alerte|signalement)\b/', $n)) {
            return true;
        }
        // verbes de signalement, seuls ou précédés d'un souhait
        if (preg_match('/^(signaler|denoncer|temoigner|alerter|declarer)\b/', $n)) {
            return true;
        }
        if (preg_match('/\b(je veux|je voudrais|je souhaite|j aimerais|je dois|il faut que je|envie de|besoin de|je vais|je viens|on veut|nous voulons|je peux|aidez moi a|aide moi a)\b.{0,30}\b(signaler|denoncer|temoigner|alerter|declarer)\b/', $n)) {
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------ appariement

    private static function splitList(string $s): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[,;\n\r]+/u', $s)), 'strlen'));
    }

    /**
     * Note chaque entrée active de la base de connaissances.
     * @return array<int,array{i:int,score:float}> triées par note décroissante
     */
    public static function score(string $text, array $kb): array
    {
        $norm = self::normalize($text);
        $q = array_flip(self::concepts($text));
        $scores = [];
        foreach (array_values($kb) as $i => $e) {
            if (isset($e['active']) && !$e['active']) {
                continue;
            }
            $s = 0.0;
            if ($norm !== '' && self::normalize($e['question'] ?? '') === $norm) {
                $s += 100;
            }
            $seen = [];
            foreach (self::splitList($e['keywords'] ?? '') as $phrase) {
                $cs = self::concepts($phrase);
                $n = count($cs);
                $sig = implode('|', $cs);
                if ($n === 0 || isset($seen[$sig])) {
                    continue; // synonymes d'un même concept : comptés une seule fois
                }
                $seen[$sig] = true;
                $w = 3.0 + 1.5 * ($n - 1);
                $all = true;
                foreach ($cs as $c) {
                    if (!isset($q[$c])) {
                        $all = false;
                        break;
                    }
                }
                if ($all) {
                    $s += $w;
                } elseif ($n === 1 && strlen($cs[0]) >= 5) {
                    foreach ($q as $qc => $_) {
                        if (strlen($qc) >= 5 && abs(strlen($qc) - strlen($cs[0])) <= 1 && levenshtein($qc, $cs[0]) <= 1) {
                            $s += $w * 0.6;
                            break;
                        }
                    }
                }
            }
            $ov = 0;
            foreach (self::concepts($e['question'] ?? '') as $c) {
                if (isset($q[$c])) {
                    $ov++;
                }
            }
            $s += min($ov, 4);
            if ($s > 0) {
                $scores[] = ['i' => $i, 'score' => $s];
            }
        }
        usort($scores, function ($a, $b) {
            return $b['score'] <=> $a['score'] ?: $a['i'] <=> $b['i'];
        });
        return $scores;
    }

    private static function resolveUrl($u, array $links)
    {
        $u = trim((string) $u);
        if ($u === '') {
            return null;
        }
        if ($u === '{alert}') {
            return $links['alert'] ?? null;
        }
        if ($u === '{forum}') {
            return $links['forum'] ?? null;
        }
        return preg_match('#^(/(?!/)|\#|https?://)#i', $u) ? $u : null;
    }

    private static function kbCta(array $e, array $links): array
    {
        $url = self::resolveUrl($e['cta_url'] ?? '', $links);
        $label = trim((string) ($e['cta_label'] ?? ''));
        return ($url && $label !== '') ? [['label' => $label, 'url' => $url]] : [];
    }

    private static function hasPii(string $text): bool
    {
        return (bool) preg_match('/[\w.+-]+@[\w-]+\.[\w.-]+/u', $text)
            || (bool) preg_match('/(?:\+|00)?\d(?:[\s.\-]?\d){7,}/', $text)
            || (bool) preg_match('/\b(je m.?appelle|mon nom est|mon adresse|j.?habite (au|a|à|en|rue))\b/iu', $text);
    }

    private static function asksDiagnosis(string $text): bool
    {
        $n = ' ' . self::normalize($text) . ' ';
        if (preg_match('/ (diagnostic\w*|diagnostiqu\w*) /', $n)) {
            return true;
        }
        $cond = '(deprim\w*|depress\w*|bipolaire|anxieu\w*|borderline|schizo\w*|autiste|malade mental|fou|folle|burn ?out|traumatis\w*)';
        return (bool) preg_match('/ (suis je|ai je|est ce que je suis|est ce que j ai|est ce que c est|c est une|je souffre d une?|souffre je) (\w+ ){0,3}' . $cond . ' /', $n);
    }

    private static function suggestions(array $scores, array $kb, ?int $exclude, int $max = 3): array
    {
        $out = [];
        foreach ($scores as $s) {
            if ($s['i'] === $exclude) {
                continue;
            }
            $out[$kb[$s['i']]['question']] = true;
            if (count($out) >= $max) {
                return array_keys($out);
            }
        }
        foreach ($kb as $i => $e) {
            if ($i === $exclude || (isset($e['active']) && !$e['active'])) {
                continue;
            }
            $out[$e['question']] = true;
            if (count($out) >= $max) {
                break;
            }
        }
        return array_slice(array_keys($out), 0, $max);
    }

    /**
     * Réponse à un message. Pure : $kb = lignes (id, question, answer, keywords, cta_label, cta_url, active),
     * $settings = AssistantSetting::allSettings(), $links = ['alert' => url, 'forum' => url].
     *
     * @return array{answer:string,cta:array,suggestions:array,escalate:bool,matched:bool,kind:string,kb_id:?int,start_alert:bool}
     */
    public static function respond(string $text, array $kb, array $settings, array $links = []): array
    {
        $kb = array_values(array_filter($kb, function ($e) {
            return !isset($e['active']) || $e['active'];
        }));
        $on = function ($k) use ($settings) {
            return (string) ($settings[$k] ?? '1') === '1';
        };
        $alertCta = ['label' => 'Créer une alerte', 'url' => $links['alert'] ?? '/guest/openticket'];
        $base = ['cta' => [], 'suggestions' => [], 'escalate' => false, 'matched' => false, 'kind' => 'fallback', 'kb_id' => null, 'start_alert' => false];
        $scores = self::score($text, $kb);
        $wantsAlert = self::wantsAlert($text);

        // 1. Escalade : message à risque (mots-clés gérés par l'administration)
        if ($on('escalation_enabled') && self::isEscalation($text, self::splitList((string) ($settings['escalation_keywords'] ?? '')))) {
            $near = [];
            foreach ($scores as $s) {
                $near[] = $s;
            }
            return array_merge($base, [
                'answer' => (string) $settings['escalation_message'],
                'cta' => [$alertCta, ['label' => 'Utiliser le bouton SOS', 'url' => '#gn-sos']],
                'suggestions' => self::suggestions($near, $kb, null),
                'escalate' => $on('g_sos_banner'),
                'kind' => 'escalation',
                'start_alert' => $wantsAlert,
            ]);
        }

        // 1 bis. Envie de créer une alerte : le navigateur lance la conversation guidée
        if ($wantsAlert) {
            return array_merge($base, [
                'answer' => 'Je peux vous accompagner pour créer une alerte, pas à pas : vous répondez, je pose les questions, et vous relisez tout avant l’envoi. Vous pouvez annuler ou revenir en arrière à chaque étape.',
                'matched' => true,
                'kind' => 'start_alert',
                'start_alert' => true,
            ]);
        }

        $norm = self::normalize($text);
        $words = array_values(array_filter(explode(' ', $norm), 'strlen'));

        // 2. Salutations et remerciements (messages très courts uniquement)
        if (count($words) > 0 && count($words) <= 3) {
            if (count(array_diff($words, self::GREET)) === 0) {
                return array_merge($base, ['answer' => (string) $settings['greeting'], 'suggestions' => self::suggestions([], $kb, null), 'kind' => 'greeting']);
            }
            if (in_array($words[0], self::THANKS, true) && count(array_diff($words, array_merge(self::THANKS, ['beaucoup', 'bien', 'a', 'vous', 'toi']))) === 0) {
                return array_merge($base, ['answer' => 'Avec plaisir. Prenez soin de vous : vous pouvez revenir quand vous voulez.', 'kind' => 'thanks']);
            }
        }

        $pii = $on('g_no_pii') && self::hasPii($text) ? "\n\n" . $settings['pii_text'] : '';

        // 3. Aucun diagnostic
        if ($on('g_no_diagnosis') && self::asksDiagnosis($text)) {
            return array_merge($base, [
                'answer' => (string) $settings['no_diagnosis_text'] . $pii,
                'cta' => [$alertCta],
                'suggestions' => self::suggestions($scores, $kb, null),
                'kind' => 'no_diagnosis',
            ]);
        }

        // 4. Meilleure entrée de la base de connaissances
        if ($scores && $scores[0]['score'] >= self::THRESHOLD) {
            $i = $scores[0]['i'];
            $e = $kb[$i];
            return array_merge($base, [
                'answer' => (string) $e['answer'] . $pii,
                'cta' => self::kbCta($e, $links),
                'suggestions' => self::suggestions(array_slice($scores, 1), $kb, $i),
                'matched' => true,
                'kind' => 'answer',
                'kb_id' => isset($e['id']) ? (int) $e['id'] : null,
            ]);
        }

        // 5. Hors périmètre ? (aucun mot du domaine, ni de la base de connaissances)
        if ($on('g_topic_filter') && count($words) >= 2 && !$scores) {
            $scope = false;
            $kbConcepts = [];
            foreach ($kb as $e) {
                foreach (self::concepts(($e['keywords'] ?? '') . ' ' . ($e['question'] ?? '')) as $c) {
                    $kbConcepts[$c] = true;
                }
            }
            $stop = self::stopMap();
            foreach ($words as $w) {
                if (isset($stop[$w])) {
                    continue;
                }
                $c = self::concept($w);
                if (isset($kbConcepts[$c]) || isset(self::GROUPS[$c]) || self::inScopeWord($w)) {
                    $scope = true;
                    break;
                }
            }
            if (!$scope) {
                return array_merge($base, [
                    'answer' => (string) $settings['offtopic'] . $pii,
                    'cta' => [$alertCta],
                    'suggestions' => self::suggestions([], $kb, null),
                    'kind' => 'offtopic',
                ]);
            }
        }

        return array_merge($base, [
            'answer' => (string) $settings['fallback'] . $pii,
            'cta' => [$alertCta],
            'suggestions' => self::suggestions($scores, $kb, null),
        ]);
    }

    // ------------------------------------------------------------------ accès base (hors fonction pure)

    public static function alertUrl(): string
    {
        return setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : route('auth.login');
    }

    public static function links(): array
    {
        return ['alert' => self::alertUrl(), 'forum' => gn_forum_url()];
    }

    public static function activeKb(): array
    {
        return AssistantKb::where('active', 1)->orderBy('sort_order')->orderBy('id')
            ->get(['id', 'question', 'answer', 'keywords', 'cta_label', 'cta_url', 'active'])->toArray();
    }

    /** Répond et compte l'échange de façon anonyme : kb_id, escalade, trouvé. Jamais le texte, l'IP ou le compte. */
    public static function ask(string $text): array
    {
        $settings = AssistantSetting::allSettings();
        $r = self::respond($text, self::activeKb(), $settings, self::links());
        if (AssistantSetting::flag('g_log_anon')) {
            try {
                AssistantLog::create([
                    'kb_id' => $r['kb_id'],
                    'escalated' => $r['kind'] === 'escalation',
                    'matched' => $r['matched'],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }
        unset($r['kb_id']);
        return $r;
    }

    /** Vérifie seulement l'escalade (pendant la création d'une alerte) : rien n'est journalisé ni stocké. */
    public static function escalationCheck(string $text): array
    {
        $s = AssistantSetting::allSettings();
        $on = (string) ($s['escalation_enabled'] ?? '1') === '1';
        $hit = $on && self::isEscalation($text, self::splitList((string) ($s['escalation_keywords'] ?? '')));
        return [
            'escalate' => $hit && (string) ($s['g_sos_banner'] ?? '1') === '1',
            'answer' => $hit ? (string) $s['escalation_message'] : '',
            'cta' => $hit ? [['label' => 'Utiliser le bouton SOS', 'url' => '#gn-sos']] : [],
        ];
    }

    /**
     * Paramètres de la création d'alerte par la conversation : mêmes règles que le formulaire /guest/openticket.
     * ok=false (avec « reason ») si le formulaire utilise une option que la conversation ne gère pas (captcha, Envato, champ obligatoire).
     */
    public static function alertConfig(): array
    {
        $cats = \App\Models\Ticket\Category::whereIn('display', ['ticket', 'both'])->where('status', '1')->orderBy('id')->get(['id', 'name']);
        $fields = \App\Models\Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status', '1')->get();
        $phone = $fields->first(function ($f) {
            return $f->fieldtypes === 'text' && preg_match('/phone|t[eé]l[eé]phone|mobile|whatsapp/iu', (string) $f->fieldnames);
        });
        $requiredOther = $fields->filter(function ($f) use ($phone) {
            return (string) $f->fieldrequired === '1' && (!$phone || $f->id !== $phone->id);
        })->count();
        $captcha = setting('CAPTCHATYPE') !== 'off' && setting('RECAPTCH_ENABLE_GUEST') == 'yes';
        $reason = null;
        if (setting('GUEST_TICKET') != 'yes') {
            $reason = 'disabled';
        } elseif ($captcha || setting('ENVATO_ON') == 'on' || $requiredOther > 0) {
            $reason = 'unsupported';
        } elseif ($cats->isEmpty()) {
            $reason = 'nocategory';
        }
        // même condition que GuestticketController@index : « no » affiche le formulaire avec code de vérification
        $otp = setting('GUEST_TICKET_OTP') == 'no';
        return [
            'ok' => $reason === null,
            'reason' => $reason,
            'otp' => $otp,
            'subject_max' => max(10, (int) setting('TICKET_CHARACTER') ?: 55),
            'desc_min' => 20,
            'desc_max' => 4000,
            'categories' => $cats->map(function ($c) { return ['id' => (int) $c->id, 'name' => (string) $c->name]; })->values()->all(),
            'phone_field' => $phone ? (int) $phone->id : null,
            'urls' => [
                'form' => self::alertUrl(),
                'login' => route('auth.login'),
                'send' => url($otp ? '/guest/openticket' : '/guest/openticketnootp'),
                'send_noemail' => url('/guest/openticketnootp'),
                'email_validate' => route('guest.emailsvalidate'),
                'verify_otp' => route('guest.verifyotp'),
                'view' => url('guest/ticketdetails'),
            ],
        ];
    }

    /** Données livrées avec la page : réglages publics, questions rapides, URLs. */
    public static function pageConfig(): array
    {
        $s = AssistantSetting::allSettings();
        return [
            'enabled' => $s['enabled'] === '1',
            'greeting' => $s['greeting'],
            'disclaimer' => $s['g_disclaimer'] === '1' ? $s['disclaimer'] : '',
            'quick' => array_slice(self::splitList((string) $s['quick_questions']), 0, 6),
            'alert_url' => self::alertUrl(),
            'ask_url' => url('/assistant/ask'),
            'config_url' => url('/assistant/alert-config'),
            'alert_chat' => setting('GUEST_TICKET') == 'yes',
            'max' => self::MAX_LEN,
        ];
    }
}
