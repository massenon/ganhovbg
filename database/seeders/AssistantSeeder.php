<?php

namespace Database\Seeders;

use App\Models\AssistantKb;
use App\Models\AssistantSetting;
use Illuminate\Database\Seeder;

/**
 * Base de connaissances de départ de l'assistant GANXÔ et réglages par défaut.
 * Idempotent : n'écrase jamais une entrée ou un réglage existant (php artisan db:seed --class=AssistantSeeder).
 * Aucune coordonnée, aucun numéro d'urgence, aucune loi citée : ces éléments dépendent du pays du visiteur.
 */
class AssistantSeeder extends Seeder
{
    public function run()
    {
        if (AssistantKb::count() === 0) {
            foreach (self::entries() as $i => $e) {
                AssistantKb::create($e + ['active' => true, 'sort_order' => ($i + 1) * 10]);
            }
        }
        foreach (AssistantSetting::defaults() as $k => $v) {
            if (!AssistantSetting::query()->where('key', $k)->exists()) {
                AssistantSetting::query()->create(['key' => $k, 'value' => $v]);
            }
        }
    }

    /** @return array<int,array{question:string,answer:string,keywords:string,cta_label:?string,cta_url:?string}> */
    public static function entries(): array
    {
        $alert = ['cta_label' => 'Créer une alerte', 'cta_url' => '{alert}'];
        return [
            [
                'question' => 'Qu’est-ce qu’une alerte GANXÔ ?',
                'answer' => "Une alerte est un message que vous envoyez à l’équipe GANXÔ pour lui faire part d’une situation de violence basée sur le genre ou de détresse. Vous décrivez ce qui se passe avec vos mots, vous choisissez la catégorie qui correspond le mieux, et l’équipe peut vous répondre via le suivi de votre alerte.\n\nVous pouvez aussi signaler une situation dont vous êtes témoin. Vous décidez de ce que vous écrivez : rien ne vous oblige à tout raconter.",
                'keywords' => 'alerte, signalement, c est quoi une alerte, a quoi sert, ganxo, plateforme, presentation',
            ] + $alert,
            [
                'question' => 'Comment créer une alerte sans compte ?',
                'answer' => "Pour créer une alerte, aucun compte n’est nécessaire :\n1) cliquez sur « Créer une alerte » ;\n2) indiquez votre adresse e-mail pour le suivi (un code de confirmation peut vous être demandé) : elle est facultative dans la conversation avec l’assistant, vous pouvez dire que vous n’en avez pas ;\n3) choisissez la catégorie et décrivez la situation avec vos mots ;\n4) envoyez. Vous pourrez ensuite suivre votre alerte et échanger avec l’équipe GANXÔ.\n\nSans adresse e-mail, notez bien la référence et le lien de suivi affichés après l’envoi : ce sont les seuls moyens de retrouver votre alerte. Si vous craignez que quelqu’un lise vos messages, utilisez une adresse e-mail que vous seul·e consultez.",
                'keywords' => 'creer une alerte, envoyer une alerte, deposer une alerte, remplir le formulaire, sans compte, inscription, creer un compte, besoin d un compte',
            ] + $alert,
            [
                'question' => 'Que fait le bouton SOS ?',
                'answer' => "Le bouton SOS se trouve en bas à droite de l’écran. Maintenez-le appuyé environ 2 secondes : après votre confirmation, il lit votre position GPS (si vous l’autorisez) et la joint à un formulaire d’alerte que vous pouvez relire avant d’envoyer. L’alerte est reçue par l’équipe GANXÔ.\n\nIl ne contacte ni la police ni les secours. Si vous êtes en danger immédiat, mettez-vous à l’abri si possible et joignez d’abord les secours de votre pays ; le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ.",
                'keywords' => 'sos, bouton sos, gps, position, localisation, geolocalisation, bouton rouge',
            ] + $alert,
            [
                'question' => 'Mes données et ma confidentialité sont-elles protégées ?',
                'answer' => "Une alerte est transmise à l’équipe GANXÔ via la plateforme. Cette conversation avec l’assistant n’est pas enregistrée sur nos serveurs et n’est pas lue par l’équipe : seuls des compteurs anonymes (sujet abordé, sans votre texte, sans adresse IP ni compte) servent à améliorer les réponses.\n\nSi vous craignez d’être surveillé·e : effacez la conversation avec le bouton prévu, fermez l’onglet ensuite et pensez à vider l’historique de votre navigateur. Ne saisissez pas d’informations qui permettent de vous identifier.",
                'keywords' => 'confidentialite, anonyme, donnees, vie privee, historique, surveille, traces, enregistre',
                'cta_label' => null, 'cta_url' => null,
            ],
            [
                'question' => 'Quels sont les types de violences ?',
                'answer' => "GANXÔ distingue plusieurs formes de violences :\n• violence verbale : insultes, humiliations, menaces, cris ;\n• violence psychologique : contrôle, isolement, dénigrement, chantage affectif ;\n• violence sexuelle : tout acte ou geste sexuel imposé sans consentement ;\n• rejet familial : expulsion, rupture des liens ou pressions à cause de votre genre, de votre orientation ou de votre identité.\n\nSi votre situation ne correspond à aucune case, ce n’est pas grave : décrivez-la avec vos mots dans une alerte. D’autres formes existent, comme les violences physiques.",
                'keywords' => 'types de violence, formes de violence, categories, violence verbale, violence psychologique, rejet familial, insulte, humiliation, controle, emprise, violence conjugale',
            ] + $alert,
            [
                'question' => 'Que faire après une violence sexuelle ?',
                'answer' => "Ce qui s’est passé n’est pas de votre faute, et vous n’avez pas à le justifier. Quelques repères, à suivre à votre rythme :\n• si c’est possible, rejoignez un lieu sûr et une personne en qui vous avez confiance ;\n• consultez rapidement un·e professionnel·le de santé, même sans blessure visible : certains soins préventifs sont plus efficaces s’ils sont pris tôt ;\n• si vous le pouvez, gardez des traces (messages, photos, vêtements non lavés, notes avec dates et lieux) : cela vous laisse le choix de la suite ;\n• vous décidez de la suite à donner, personne ne peut vous y forcer.\n\nVous pouvez écrire à l’équipe GANXÔ via une alerte pour être écouté·e et orienté·e. Si vous êtes en danger immédiat, mettez-vous à l’abri si possible et joignez d’abord les secours de votre pays.",
                'keywords' => 'violence sexuelle, agression sexuelle, viol, agression, attouchement, abus sexuel, harcelement sexuel, forcee, apres une agression',
            ] + $alert,
            [
                'question' => 'Je subis du harcèlement ou un chantage en ligne',
                'answer' => "Ce que vous vivez n’est pas acceptable et vous n’en êtes pas responsable. Quelques réflexes :\n• ne répondez pas aux provocations et ne payez pas : payer ne garantit pas que cela s’arrête ;\n• faites des captures d’écran avec la date, l’heure et l’adresse du profil ou du site ;\n• bloquez le compte, puis signalez-le à la plateforme concernée ;\n• parlez-en à une personne de confiance ;\n• ne supprimez pas les messages avant d’en avoir gardé une trace.\n\nVous pouvez décrire la situation dans une alerte : l’équipe GANXÔ peut vous répondre et vous orienter.",
                'keywords' => 'harcelement, cyberharcelement, chantage, en ligne, reseaux sociaux, photos, sextorsion, menace de publier, revenge porn',
            ] + $alert,
            [
                'question' => 'Chantage à l’outing, homophobie, transphobie : que faire ?',
                'answer' => "Quelle que soit votre orientation ou votre identité de genre, vous avez le droit d’être en sécurité et respecté·e.\n\nEn cas de chantage à l’outing (menace de révéler votre orientation ou votre identité) : ne payez pas et ne cédez pas à la pression, gardez des captures d’écran datées, bloquez le compte si c’est sans risque pour vous, et confiez-vous à une personne de confiance.\n\nEn cas de discrimination ou de rejet : notez les faits (date, lieu, ce qui a été dit ou fait, témoins éventuels). Vous pouvez les décrire dans une alerte, et rejoindre le forum pour échanger avec la communauté LGBTQ.\n\nVous n’avez jamais à faire votre coming out avant d’être prêt·e.",
                'keywords' => 'outing, chantage outing, outer, homophobie, transphobie, lgbtq, discrimination, orientation sexuelle, identite de genre, coming out',
            ] + $alert,
            [
                'question' => 'Qu’est-ce que le consentement ?',
                'answer' => "Le consentement doit être libre (sans pression ni peur), éclairé, spécifique à chaque acte et réversible : on peut changer d’avis à tout moment, même en cours de route. Le silence, la peur, la sidération, l’alcool ou l’insistance ne valent jamais consentement. Dire oui une fois ne vaut pas pour toujours.\n\nVous n’avez pas à vous justifier pour dire non.",
                'keywords' => 'consentement, consentir, consentant',
                'cta_label' => null, 'cta_url' => null,
            ],
            [
                'question' => 'Quels sont les signes de la dépression ?',
                'answer' => "Je ne peux pas poser de diagnostic, mais voici des signes souvent décrits quand on traverse une dépression : une tristesse ou un vide qui dure, la perte d’intérêt pour ce qui plaisait, une grande fatigue, des troubles du sommeil ou de l’appétit, des difficultés à se concentrer, un sentiment de culpabilité ou de ne plus valoir grand-chose.\n\nSi plusieurs de ces signes durent depuis plusieurs semaines et pèsent sur votre quotidien, c’est une bonne raison d’en parler à votre médecin ou à un·e psychologue : la dépression se soigne. En attendant, parlez à une personne de confiance, gardez un rythme simple (sommeil, repas, un peu de marche si possible) et ne restez pas seul·e avec cela.\n\nSi vous pensez à vous faire du mal, parlez-en tout de suite à une personne de confiance ou aux secours de votre pays.",
                'keywords' => 'depression, deprime, triste, tristesse, idees noires, plus envie de rien, moral au plus bas, mal etre, epuise, fatigue, signes de depression, symptomes',
            ] + $alert,
            [
                'question' => 'J’ai de l’angoisse ou du stress : comment me calmer ?',
                'answer' => "Essayez la respiration carrée, assis·e confortablement : inspirez par le nez 4 secondes, retenez votre souffle 4 secondes, expirez lentement 4 secondes, retenez 4 secondes. Répétez cinq fois.\n\nAutre repère pour revenir au présent : nommez 5 choses que vous voyez, 4 que vous pouvez toucher, 3 que vous entendez.\n\nCes gestes apaisent sur le moment, ils ne remplacent pas un suivi. Si l’angoisse revient souvent ou vous empêche de vivre normalement, parlez-en à un·e professionnel·le de santé ou à une personne de confiance.",
                'keywords' => 'angoisse, anxiete, stress, panique, crise d angoisse, respiration, calmer, apaiser, relaxation, aller mieux, me sentir mieux, soulager',
            ] + $alert,
            [
                'question' => 'Comment aider une amie ou un proche en détresse ?',
                'answer' => "Vous pouvez beaucoup, même sans être spécialiste :\n• écoutez sans juger ni minimiser, et croyez ce que la personne vous dit ;\n• laissez-la décider de son rythme : ne la forcez pas à parler ou à agir ;\n• ne confrontez pas seul·e la personne qui fait du mal : cela peut aggraver le danger ;\n• encouragez-la à se mettre en sécurité et à parler à un·e professionnel·le ;\n• aidez-la à garder des traces si elle le souhaite.\n\nSi vous êtes témoin d’une situation, vous pouvez la signaler à l’équipe GANXÔ via une alerte. Prenez aussi soin de vous : aider quelqu’un peut peser lourd.",
                'keywords' => 'aider une amie, aider un proche, un ami, mon ami, ma soeur, proche, temoin, aider quelqu un, quelqu un que je connais',
            ] + $alert,
            [
                'question' => 'Comment fonctionne le forum d’entraide ?',
                'answer' => "Le forum d’entraide GANXÔ est un espace de discussion où l’on peut poser des questions, partager son expérience et trouver du soutien auprès d’autres personnes. Vous pouvez le rejoindre depuis le lien ci-dessous.\n\nLe forum est un espace d’échange entre membres, pas une prise en charge individuelle : pour être écouté·e par l’équipe, créez une alerte. Évitez d’y publier des informations qui permettent de vous identifier.",
                'keywords' => 'forum, communaute, entraide, echanger, partager mon experience, temoigner, discuter',
                'cta_label' => 'Rejoindre le forum', 'cta_url' => '{forum}',
            ],
            [
                'question' => 'Existe-t-il une application mobile ?',
                'answer' => "GANXÔ s’utilise directement depuis le navigateur de votre téléphone ou de votre ordinateur : rien à télécharger pour créer une alerte, utiliser le bouton SOS ou parler à l’assistant. Si une application mobile est proposée un jour, elle sera annoncée sur le site.",
                'keywords' => 'application, appli, mobile, telephone, android, ios, telecharger, play store, iphone',
                'cta_label' => null, 'cta_url' => null,
            ],
            [
                'question' => 'Comment contacter l’équipe GANXÔ ?',
                'answer' => "Pour une question sur la plateforme, un partenariat ou une suggestion, utilisez le formulaire de contact. Pour être aidé·e dans une situation de violence ou de détresse, créez plutôt une alerte : c’est le moyen le plus direct d’être lu·e par l’équipe.",
                'keywords' => 'contact, contacter, ecrire a l equipe, joindre, email, mail, equipe, partenariat, suggestion',
                'cta_label' => 'Nous contacter', 'cta_url' => '/contact-us',
            ],
            [
                'question' => 'Puis-je être orienté·e vers un·e psychologue, un·e avocat·e ou une association ?',
                'answer' => "L’équipe GANXÔ peut vous orienter vers des associations, des psychologues ou des avocat·es qui offrent du soutien. Je ne peux pas vous donner de coordonnées dans cette conversation : décrivez votre besoin dans une alerte, l’équipe vous répondra via le suivi. En attendant, votre médecin ou un centre de santé près de chez vous peut aussi être un premier contact.",
                'keywords' => 'psychologue, avocat, juriste, association, medecin, orienter, orientation, professionnel, therapeute, consulter, annuaire',
            ] + $alert,
            [
                'question' => 'Puis-je porter plainte, et quels sont mes droits ?',
                'answer' => "Porter plainte est un choix personnel, pas une obligation. Les règles et les démarches varient d’un pays à l’autre : je ne peux pas vous en donner le détail précis. Un·e avocat·e ou une association d’aide aux victimes de votre pays peut vous informer sur vos droits et vous accompagner.\n\nSi c’est sans risque pour vous, gardez des traces (messages, photos, certificats médicaux, dates et lieux). L’équipe GANXÔ peut vous aider à vous orienter : décrivez votre situation dans une alerte.",
                'keywords' => 'plainte, porter plainte, police, justice, loi, droits, tribunal, gendarmerie',
            ] + $alert,
        ];
    }
}
