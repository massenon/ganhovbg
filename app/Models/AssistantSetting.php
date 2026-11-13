<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Réglages de l'assistant (clé/valeur). Les valeurs par défaut vivent ici : l'assistant fonctionne
 * même avant que l'administrateur ait enregistré quoi que ce soit.
 */
class AssistantSetting extends Model
{
    protected $table = 'assistant_settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    private static $memo = null;

    /** Clés booléennes (« 0 » est une valeur valide, contrairement aux textes). */
    public const FLAGS = ['enabled', 'escalation_enabled', 'g_no_diagnosis', 'g_no_pii', 'g_topic_filter', 'g_sos_banner', 'g_log_anon', 'g_disclaimer'];

    public const GUARDRAILS = [
        'g_no_diagnosis' => 'Ne jamais poser de diagnostic médical ou psychologique',
        'g_no_pii' => 'Ne jamais demander d’informations identifiantes (rappel si le visiteur en saisit)',
        'g_topic_filter' => 'Répondre poliment « hors sujet » aux demandes sans rapport avec le périmètre de GANXÔ',
        'g_sos_banner' => 'Afficher le bandeau d’aide immédiate (SOS, alerte) pour tout message à risque',
        'g_log_anon' => 'Compter les échanges de façon anonymisée (aucun texte, aucune IP, aucun compte)',
        'g_disclaimer' => 'Afficher l’avertissement « ne remplace pas un·e professionnel·le »',
    ];

    public static function defaults(): array
    {
        return [
            'enabled' => '1',
            'greeting' => 'Bonjour, je suis l’assistant de GANXÔ. Je ne suis pas une personne et je ne remplace pas un·e professionnel·le. Comment puis-je vous guider ?',
            'fallback' => 'Je n’ai pas bien compris votre question. Vous pouvez la reformuler en quelques mots, ou choisir un des sujets ci-dessous. Pour être écouté·e par l’équipe GANXÔ, créez une alerte.',
            'offtopic' => 'Je suis là pour parler des violences basées sur le genre, de la dépression, du soutien aux personnes LGBTQ et du fonctionnement de GANXÔ. Je ne peux pas vous aider sur ce sujet. Choisissez un des thèmes ci-dessous ou créez une alerte pour écrire à l’équipe.',
            'disclaimer' => 'L’assistant donne des repères généraux et ne remplace pas un·e professionnel·le (médecin, psychologue, juriste).',
            'escalation_enabled' => '1',
            'escalation_keywords' => "suicide, suicidaire, me suicider, me tuer, il va me tuer, elle va me tuer, en finir, mettre fin a mes jours, veux mourir, vais mourir, plus envie de vivre, danger immediat, en danger, danger, menace de mort, il me menace, elle me menace, couteau, une arme, frappe, il me bat, elle me bat, au secours, me faire du mal, viol en cours, me force",
            'escalation_message' => 'Je suis désolé·e que vous viviez cela. Si vous êtes en danger immédiat, mettez-vous à l’abri si possible et joignez d’abord les secours de votre pays ; le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ. Vous pouvez aussi créer une alerte pour écrire à l’équipe. Si vous pensez à vous faire du mal, parlez maintenant à une personne de confiance ou aux secours de votre pays : vous n’avez pas à rester seul·e avec cela.',
            'no_diagnosis_text' => 'Je ne peux pas poser de diagnostic : seul·e un·e professionnel·le de santé peut évaluer ce que vous traversez. Je peux en revanche vous donner des repères et vous aider à préparer cet échange. Si cela pèse sur votre quotidien depuis plusieurs semaines, parlez-en à votre médecin ou à un·e psychologue.',
            'pii_text' => 'Pour votre sécurité, ne saisissez pas ici d’informations qui permettent de vous identifier (nom, adresse, téléphone, e-mail). Pour être contacté·e par l’équipe, utilisez le formulaire d’alerte.',
            'quick_questions' => "Je suis en danger\nComment créer une alerte ?\nJe me sens déprimé·e\nQue fait le bouton SOS ?",
            'g_no_diagnosis' => '1', 'g_no_pii' => '1', 'g_topic_filter' => '1', 'g_sos_banner' => '1', 'g_log_anon' => '1', 'g_disclaimer' => '1',
        ];
    }

    /** Tous les réglages (défauts + valeurs enregistrées non vides), mémoïsés pour la requête. */
    public static function allSettings(): array
    {
        if (self::$memo !== null) {
            return self::$memo;
        }
        $out = self::defaults();
        try {
            if (Schema::hasTable('assistant_settings')) {
                foreach (static::query()->get() as $row) {
                    $k = $row->getAttribute('key');
                    $v = $row->getAttribute('value');
                    if (!array_key_exists($k, $out) || $v === null) {
                        continue;
                    }
                    if ($v !== '' || in_array($k, self::FLAGS, true)) {
                        $out[$k] = (string) $v;
                    }
                }
            }
        } catch (\Throwable $e) {
            // table absente ou base indisponible : valeurs par défaut
        }
        return self::$memo = $out;
    }

    public static function get(string $key)
    {
        return self::allSettings()[$key] ?? null;
    }

    public static function flag(string $key): bool
    {
        return (string) self::get($key) === '1';
    }

    public static function put(array $values): void
    {
        foreach ($values as $k => $v) {
            static::query()->updateOrCreate(['key' => $k], ['value' => (string) $v]);
        }
        self::$memo = null;
    }
}
