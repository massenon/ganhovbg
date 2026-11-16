<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Textes d'introduction des pages /category/{slug} (affichés sous le titre de la page).
 * Stockés dans la table `settings` (clé `category_intro:<slug>`), car la table `categories` n'a pas de champ de description.
 * Idempotent (par clé). À lancer : php artisan db:seed --class=CategoryIntroSeeder
 * Textes PROPOSÉS : à faire valider par le client (et un professionnel pour la santé et le droit).
 * Paragraphes séparés par une ligne vide.
 */
class CategoryIntroSeeder extends Seeder
{
    public static function texts(): array
    {
        return [
            'violence-verbale' => <<<'TXT'
La violence verbale regroupe les insultes, les moqueries répétées, les humiliations, les menaces, les cris et les remarques qui rabaissent une personne à cause de son genre, de son orientation, de son apparence ou de son identité. Elle peut venir d’un proche, d’un collègue, d’un camarade, d’un inconnu ou d’un compte en ligne. Même sans coup, ces mots laissent des traces : perte de confiance, peur, isolement.

Quelques signes peuvent vous alerter : vous évitez certaines personnes ou certains lieux, vous pesez chaque mot pour ne pas « provoquer », vous vous sentez petit·e ou coupable après un échange, ou vous vous dites que « ce n’est pas si grave ». Ce que vous ressentez compte, et vous n’avez pas à le justifier.

Vous pouvez en parler à une personne de confiance, noter les faits (date, lieu, paroles, témoins) et conserver les messages ou captures d’écran quand il y en a. L’assistant GANXÔ peut vous aider à y voir plus clair, sans jugement. Si vous le souhaitez, vous pouvez aussi créer une alerte confidentielle : l’équipe GANXÔ la lit et peut vous orienter vers une personne ou une structure adaptée. Pour tout avis médical ou juridique, rapprochez-vous d’un·e professionnel·le.
TXT,
            'violence-sexuelle' => <<<'TXT'
La violence sexuelle désigne tout acte à caractère sexuel imposé sans consentement libre : contacts forcés, agressions, viol, harcèlement sexuel, pressions ou chantage, diffusion d’images intimes sans accord. Elle peut toucher toute personne, quels que soient son genre, son âge ou son orientation, et l’auteur est souvent quelqu’un que la victime connaît. Le consentement doit toujours être clair et libre, et il peut être retiré à tout moment.

Après de tels faits, les réactions varient beaucoup : sidération, honte, colère, silence, peur de ne pas être cru·e. Aucune de ces réactions n’est une faute. Vous n’êtes pas responsable de ce qui s’est passé, quelle que soit la situation.

Si vous le pouvez, mettez-vous en sécurité et parlez à une personne en qui vous avez confiance. Il peut être utile de garder ce qui peut servir plus tard (messages, photos, notes sur les faits), sans vous forcer à tout faire tout de suite. Un·e professionnel·le de santé peut vous recevoir et vous conseiller. L’assistant GANXÔ vous informe en toute discrétion, et vous pouvez créer une alerte confidentielle : l’équipe GANXÔ la reçoit et peut vous orienter. En danger immédiat, contactez les secours de votre pays : une alerte GANXÔ ne les contacte pas.
TXT,
            'violence-psychologique' => <<<'TXT'
La violence psychologique est plus discrète, mais elle peut être très destructrice. Elle comprend le contrôle permanent, l’isolement de vos proches, la dévalorisation, les menaces, le chantage affectif, la manipulation qui vous fait douter de vous, ou encore le harcèlement en ligne. Elle s’installe souvent peu à peu, et on la reconnaît parfois tard.

Voici quelques signes : vous avez peur de la réaction de l’autre, vous demandez la permission pour des choses simples, vous vous excusez sans cesse, vos proches se sont éloignés, ou vous ne vous reconnaissez plus. Si vous vous y retrouvez, ce n’est pas de votre faute : la violence engage celui ou celle qui l’exerce.

Vous pouvez commencer par en parler à quelqu’un de confiance et par noter ce qui se passe, avec les dates, ce qui a été dit et ce que vous avez ressenti. Gardez les messages et les captures d’écran si vous en avez. L’assistant GANXÔ est là pour écouter et vous aider à repérer vos prochains pas, à votre rythme. Vous pouvez aussi créer une alerte confidentielle : l’équipe GANXÔ vous répond et peut vous orienter vers un soutien adapté. Si vous vous sentez très mal, parlez-en à un·e professionnel·le de santé ou de l’écoute psychologique.
TXT,
            'cas-de-rejet-familial' => <<<'TXT'
Le rejet familial touche des personnes dont la famille ne les accepte pas, souvent à cause de leur orientation sexuelle, de leur identité de genre ou de leur façon de vivre. Il peut prendre la forme de menaces, de silence, d’humiliations, de pressions pour « changer », d’une mise à la porte, ou d’une coupure des liens. Ces situations sont douloureuses, car elles viennent de celles et ceux dont on attend de l’appui.

Si vous vivez cela, vous n’êtes pas seul·e, et vous n’avez pas à « mériter » l’affection de votre famille pour être respecté·e. Votre valeur ne dépend pas de leur accord. Il est normal de ressentir de la tristesse, de la colère, de la peur ou de la fatigue.

Pensez d’abord à votre sécurité : à qui pouvez-vous parler, où pouvez-vous être accueilli·e si la situation se tend ? Un ami, une personne de confiance ou une association peut vous aider. Notez les faits importants et conservez les messages qui peuvent servir plus tard. L’assistant GANXÔ vous écoute sans jugement, et vous pouvez créer une alerte confidentielle : l’équipe GANXÔ la lit et peut vous orienter vers un soutien. Pour un conseil juridique ou psychologique, rapprochez-vous d’un·e professionnel·le. En danger immédiat, appelez les secours de votre pays.
TXT,
            'pool-com' => <<<'TXT'
Cette catégorie rassemble les contenus de la communication GANXÔ : actions de sensibilisation, messages de prévention, informations sur les campagnes et sur la vie de la communauté. L’objectif est simple : faire connaître les violences basées sur le genre et les ressources qui existent, avec des mots clairs et respectueux.

Vous pouvez lire ces articles pour mieux comprendre ces sujets, les partager autour de vous ou vous en inspirer. Si une situation vous concerne, vous pouvez parler à l’assistant GANXÔ ou créer une alerte confidentielle : l’équipe la lit et peut vous orienter.
TXT,
            'sante-mentale' => <<<'TXT'
Les violences, le rejet et l’isolement pèsent sur la santé mentale : anxiété, tristesse, troubles du sommeil, perte de confiance, pensées sombres. Ces réactions sont humaines et elles n’ont rien de honteux. Demander de l’aide est une force, pas une faiblesse.

Les articles de cette catégorie proposent des repères simples pour comprendre ce que vous traversez et prendre soin de vous, à votre rythme. Ils ne remplacent pas l’avis d’un·e professionnel·le de santé. Si vous ne vous sentez pas bien, parlez-en à une personne de confiance, à l’assistant GANXÔ ou à l’équipe via une alerte confidentielle. En danger immédiat, contactez les secours de votre pays.
TXT,
            'sante-et-droits' => <<<'TXT'
Connaître ses droits et savoir où trouver des soins aide à mieux se protéger. Cette catégorie réunit des articles d’information sur la santé (y compris sexuelle et reproductive), sur la prévention et sur les droits des personnes confrontées à la violence ou à la discrimination.

Ces contenus sont des repères généraux : les règles et les services varient selon les pays et les situations, et rien ici ne remplace l’avis d’un·e professionnel·le de santé ou d’un·e juriste. L’assistant GANXÔ peut vous aider à y voir plus clair, et l’équipe GANXÔ peut vous orienter si vous créez une alerte confidentielle.
TXT,
        ];
    }

    public function run(): void
    {
        foreach (self::texts() as $slug => $text) {
            Setting::updateOrCreate(['key' => 'category_intro:' . $slug], ['value' => trim($text)]);
        }
    }
}
