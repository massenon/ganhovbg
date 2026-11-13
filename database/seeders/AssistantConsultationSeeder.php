<?php

namespace Database\Seeders;

use App\Models\AssistantKb;
use Illuminate\Database\Seeder;

/**
 * Base de connaissances de l'assistant : consultation en ligne avec le psychologue (BESYP).
 * Idempotent (par question). Lancer : php artisan db:seed --class=AssistantConsultationSeeder
 */
class AssistantConsultationSeeder extends Seeder
{
    public function run()
    {
        $max = (int) AssistantKb::max('sort_order');
        foreach ($this->entries() as $i => $e) {
            if (AssistantKb::where('question', $e['question'])->exists()) {
                continue;
            }
            AssistantKb::create($e + ['active' => true, 'sort_order' => $max + ($i + 1) * 10]);
        }
    }

    private function entries(): array
    {
        return [
            [
                'question' => 'Comment avoir une consultation en ligne avec le psychologue ?',
                'answer' => "BESYP propose des consultations en ligne avec un psychologue : vous pouvez parler sans vous déplacer, depuis un endroit où vous vous sentez à l’aise. Pour prendre contact, écrivez sur WhatsApp au +229 63 60 85 13. Quelques mots suffisent pour commencer ; les horaires et les modalités vous sont précisés lors de ce premier contact. Ce n’est pas un service d’urgence : en cas de danger immédiat, contactez d’abord les secours de votre pays.",
                'keywords' => 'consultation, consultation en ligne, psychologue, psy, parler a un psychologue, rendez-vous psychologue, prendre rendez-vous, therapeute, therapie, whatsapp, besyp, ecoute, soutien psychologique, voir un psy, consulter',
                'cta_label' => 'Voir la consultation en ligne',
                'cta_url' => '/#consultation',
            ],
            [
                'question' => 'Pourquoi parler à un psychologue ?',
                'answer' => "Se sentir à bout, ruminer, mal dormir, avoir peur de ne pas être compris·e : ces signaux indiquent qu’une charge est devenue trop lourde pour être portée seul·e. Un psychologue offre un espace d’écoute confidentiel, sans jugement, pour poser des mots, remettre de l’ordre dans ses pensées et trouver des solutions concrètes. Ce n’est pas réservé aux « cas graves » : on peut venir pour du stress, de l’angoisse, un deuil, des relations difficiles, des violences vécues ou des discriminations.",
                'keywords' => 'pourquoi consulter, pourquoi parler a un psychologue, utilite psychologue, a quoi sert un psychologue, besoin de parler, mal etre, consulter un psy, premiere consultation',
                'cta_label' => 'Lire l’article',
                'cta_url' => '/article/consultation-en-ligne-psychologue',
            ],
        ];
    }
}
