<?php

namespace Database\Seeders;

use App\Models\Articles\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Actualités GANXÔ (visuels Pool Com BESYP) avec chapô, intertitres, encadrés, source et crédit photo.
 * Idempotent : mise à jour par slug (les articles « disons-non » et « calmer-une-crise-dangoisse-en-4-etapes »
 * existent déjà en base : ils sont enrichis, pas dupliqués). Les compteurs de vues existants sont conservés.
 * Lancer : php artisan db:seed --class=NewsArticlesSeeder
 */
class NewsArticlesSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $cats = [];
        foreach ([['Santé mentale', 'sante-mentale'], ['Santé et droits', 'sante-et-droits']] as $c) {
            $row = DB::table('categories')->where('categoryslug', $c[1])->first();
            if (!$row) {
                $id = DB::table('categories')->insertGetId(['name' => $c[0], 'display' => 'knowledge', 'categoryslug' => $c[1], 'priority' => 0, 'status' => '1', 'created_at' => $now, 'updated_at' => $now]);
            } else {
                $id = $row->id;
            }
            $cats[$c[1]] = $id;
        }
        $cats['pool-com'] = DB::table('categories')->where('categoryslug', 'pool-com')->value('id') ?: 5;

        foreach ($this->articles($cats) as $a) {
            $existing = Article::where('articleslug', $a['articleslug'])->first();
            $date = $a['date'];
            unset($a['date']);
            if ($existing) {
                $existing->fill($a)->save();
                $existing->updated_at = $now;
                $existing->save();
            } else {
                $m = new Article();
                $m->fill($a);
                $m->views = 0;
                $m->created_at = $date;
                $m->updated_at = $date;
                $m->save();
            }
        }
    }

    private function articles(array $cats): array
    {
        $credit = 'Pool Com BESYP (affiche)';

        $consultation = <<<'HTML'
<h2>Pourquoi parler à un psychologue ?</h2>
<p>Se sentir à bout, ruminer, dormir mal, avoir peur de ne pas être compris·e : ces signaux ne sont pas « de la faiblesse ». Ils indiquent qu’une charge est devenue trop lourde pour être portée seul·e. Un échange avec un psychologue permet de poser des mots, de remettre de l’ordre dans ses pensées et de repérer des solutions concrètes.</p>
<p>Pour de nombreuses personnes, la première difficulté n’est pas de savoir <strong>quoi</strong> dire, mais <strong>à qui</strong> le dire sans craindre le jugement. C’est précisément l’objet de cette consultation en ligne proposée à BESYP.</p>

<aside class="gc-callout" aria-label="Pour comprendre">
<p class="gc-callout__t">Pour comprendre</p>
<p>Consulter un psychologue n’est pas réservé aux « cas graves ». C’est un espace d’écoute, confidentiel, où l’on peut parler de stress, d’angoisse, de deuil, de relations, de violences vécues ou de discriminations, sans être étiqueté·e.</p>
</aside>

<h2>Comment se déroule la prise de contact ?</h2>
<ol>
<li><strong>Vous écrivez</strong> sur WhatsApp au <strong>+229 63 60 85 13</strong>. Quelques mots suffisent : vous n’avez pas à tout raconter dès le premier message.</li>
<li><strong>Vous convenez d’un créneau</strong> avec le professionnel. Les horaires et les modalités vous sont précisés lors de ce premier contact.</li>
<li><strong>Vous échangez en ligne</strong>, depuis un endroit où vous vous sentez à l’aise et à l’abri des regards.</li>
</ol>

<blockquote>
<p>« Demander de l’aide, ce n’est pas renoncer à son autonomie : c’est se donner les moyens de la retrouver. »</p>
<footer>Message de l’équipe GANXÔ</footer>
</blockquote>

<h2>Avant de vous connecter : quelques repères</h2>
<ul>
<li>Choisissez un lieu calme et un moment où vous ne serez pas interrompu·e.</li>
<li>Vous pouvez noter à l’avance ce qui vous préoccupe le plus : cela aide à démarrer.</li>
<li>Vous avez le droit de ne pas répondre à une question et de faire une pause.</li>
</ul>

<h2>Ce que la consultation ne remplace pas</h2>
<p>Une consultation en ligne n’est pas un service d’urgence. Si vous êtes en danger immédiat, ou si vous pensez à vous faire du mal, contactez d’abord les secours de votre pays ou une personne de confiance à proximité. Si vous avez subi une violence, vous pouvez aussi <a href="/guest/openticket">créer une alerte VBG</a> : l’équipe GANXÔ vous répond et vous oriente.</p>

<h2>Passer à l’action</h2>
<p><strong>Contactez-le sur WhatsApp : +229 63 60 85 13.</strong> Vous hésitez encore ? Posez vos questions à <a href="/assistant">l’assistant GANXÔ</a> ou lisez nos repères pour <a href="/article/calmer-une-crise-dangoisse-en-4-etapes">calmer une crise d’angoisse en 4 étapes</a>.</p>
HTML;

        $codes = <<<'HTML'
<h2>Un principe simple : l’habit ne définit pas la personne</h2>
<p>Dans un lycée, un bureau, une administration ou une association, un règlement vestimentaire peut sembler anodin. Pourtant, quand il impose à chacun·e de s’habiller selon le sexe inscrit sur son état civil, il peut devenir un outil d’exclusion : humiliations, renvois, sanctions, peur d’aller à l’école ou au travail.</p>
<p>Le message de cette publication de Pool Com BESYP est clair : <strong>les codes vestimentaires devraient être non discriminatoires et flexibles</strong>, et ne doivent pas empêcher les gens de s’habiller selon leur identité de genre.</p>

<blockquote>
<p>« Tous les êtres humains naissent libres et égaux en dignité et en droits. »</p>
<footer>Déclaration universelle des droits de l’homme, article premier</footer>
</blockquote>

<h2>Ce que cela change concrètement</h2>
<ul>
<li><strong>À l’école :</strong> un·e élève trans ou non binaire ne devrait pas avoir à choisir entre sa scolarité et son identité.</li>
<li><strong>Au travail :</strong> une tenue sobre et adaptée à la fonction peut être exigée de toutes et tous, sans la lier au genre.</li>
<li><strong>Dans la vie sociale :</strong> s’habiller comme on se sent ne devrait exposer ni aux moqueries ni aux violences.</li>
</ul>

<aside class="gc-callout" aria-label="Le saviez-vous ?">
<p class="gc-callout__t">Le saviez-vous ?</p>
<p>L’identité de genre est le sentiment profond d’être une femme, un homme, les deux, ou aucun des deux. Elle est différente de l’orientation sexuelle et de l’apparence. S’habiller « autrement » n’est donc pas une provocation : c’est souvent une manière d’être soi.</p>
</aside>

<h2>Des repères pour un règlement plus juste</h2>
<ol>
<li>Définir des critères de <strong>décence et de sécurité</strong> valables pour tout le monde.</li>
<li>Remplacer les listes « tenues filles / tenues garçons » par des <strong>règles neutres</strong>.</li>
<li>Prévoir un <strong>interlocuteur de confiance</strong> pour traiter les situations sans stigmatiser.</li>
</ol>
<p>Le principe de non-discrimination est aussi inscrit à l’article 2 de la Charte africaine des droits de l’homme et des peuples.</p>

<h2>Si vous êtes concerné·e</h2>
<p>Si l’on vous a sanctionné·e, insulté·e ou menacé·e à cause de votre tenue, vous n’êtes pas en tort. Vous pouvez <a href="/guest/openticket">créer une alerte VBG</a> : l’équipe vous écoute et vous oriente. Pour mieux comprendre les situations de violence, consultez notre <a href="/faq">foire aux questions</a>.</p>
HTML;

        $avortement = <<<'HTML'
<h2>De quoi parle-t-on ?</h2>
<p>Selon l’Organisation mondiale de la santé (OMS), un avortement est dit <strong>sécurisé</strong> lorsqu’il est pratiqué par une personne formée, avec une méthode recommandée et adaptée à la durée de la grossesse. À l’inverse, un avortement <strong>non sécurisé</strong> (méthodes dangereuses, personne non qualifiée, absence de suivi) est une cause évitable de complications graves et de décès.</p>
<p>Cette publication de BESYP rappelle un point souvent oublié : <strong>l’avortement sécurisé concerne aussi les minorités de sexe et de genre</strong>.</p>

<h2>Pourquoi les minorités de sexe et de genre sont concernées</h2>
<ul>
<li>Les <strong>femmes lesbiennes et bisexuelles</strong> peuvent tomber enceintes, notamment après une relation avec un homme ou une violence sexuelle.</li>
<li>Certains <strong>hommes trans et personnes non binaires</strong> peuvent aussi être enceint·es.</li>
<li>Les <strong>violences sexuelles</strong>, y compris les viols dits « correctifs », exposent à des grossesses non désirées.</li>
</ul>
<p>Pourtant, beaucoup renoncent à consulter par peur d’être jugé·es, moqué·es, dénoncé·es ou mal accueilli·es. Ce silence pousse parfois vers des solutions risquées.</p>

<aside class="gc-callout" aria-label="Le saviez-vous ?">
<p class="gc-callout__t">Le saviez-vous ?</p>
<p>L’OMS estime que presque toutes les complications liées à un avortement non sécurisé peuvent être évitées par un accès à des soins de qualité, y compris à la contraception et aux conseils avant et après l’acte.</p>
</aside>

<h2>Ce qu’il faut retenir</h2>
<ol>
<li><strong>Ne recourez jamais à une méthode « maison »</strong> ou à des médicaments obtenus sans avis professionnel.</li>
<li><strong>Parlez à un professionnel de santé</strong> d’un établissement agréé, ou à une structure en laquelle vous avez confiance.</li>
<li><strong>Après une violence sexuelle</strong>, consultez rapidement : une contraception d’urgence et une prise en charge médicale sont possibles dans les jours qui suivent.</li>
<li><strong>Vous avez droit au respect.</strong> Un soin ne devrait jamais s’accompagner de jugement sur votre identité ou votre orientation.</li>
</ol>

<blockquote>
<p>« Personne ne devrait risquer sa vie pour avoir cherché des soins. »</p>
<footer>Équipe GANXÔ</footer>
</blockquote>

<h2>Sur la loi</h2>
<p>Les règles sur l’interruption volontaire de grossesse diffèrent d’un pays à l’autre et peuvent évoluer. Renseignez-vous auprès d’un établissement de santé agréé ou contactez-nous : nous pouvons vous orienter sans jugement.</p>

<h2>Où trouver de l’aide ?</h2>
<ul>
<li>Par e-mail : <a href="mailto:contact@beninsynergieplus.org">contact@beninsynergieplus.org</a></li>
<li>Par téléphone : <strong>+229 01 68 40 73 37</strong></li>
<li>Site de l’association : <a href="https://www.beninsynergieplus.org" rel="noopener">www.beninsynergieplus.org</a></li>
<li>Sur GANXÔ : <a href="/guest/openticket">créer une alerte VBG</a>, ou poser une question à <a href="/assistant">l’assistant</a>.</li>
</ul>
HTML;

        $angoisse = <<<'HTML'
<h2>Une crise d’angoisse, c’est quoi ?</h2>
<p>Cœur qui s’emballe, souffle court, mains moites, vertiges, impression de perdre le contrôle : la crise d’angoisse est <strong>impressionnante, mais elle n’est pas dangereuse en elle-même</strong>. Elle atteint en général son pic en une dizaine de minutes, puis diminue.</p>
<p>La santé mentale est aussi essentielle que la santé physique. Prendre soin de son esprit chaque jour, c’est mieux vivre. Voici quatre gestes simples, proposés par Pool Com BESYP, pour traverser une crise.</p>

<h2>Les 4 étapes pour la calmer</h2>
<h3>1. S’isoler dans un endroit calme</h3>
<p>Éloignez-vous du bruit et des stimulations : une pièce, un coin tranquille, un banc. Dites simplement à votre entourage : « J’ai besoin de quelques minutes. » Moins il y a de bruit et de monde, plus votre corps peut redescendre.</p>
<h3>2. Détendre son corps et l’étirer</h3>
<p>Relâchez les épaules, desserrez la mâchoire, ouvrez les mains. Étirez doucement la nuque, les bras, le dos. La tension physique entretient l’angoisse : la relâcher envoie au cerveau un signal de sécurité.</p>
<h3>3. Se concentrer sur sa respiration</h3>
<p>Inspirez par le nez en comptant jusqu’à 4, puis expirez lentement par la bouche en comptant jusqu’à 6. Répétez pendant quelques minutes. Si vous êtes encore agité·e, posez une main sur le ventre et regardez-le se soulever.</p>
<h3>4. Identifier la cause et l’éviter</h3>
<p>Quand le calme revient, demandez-vous : qu’est-ce qui a déclenché la crise ? Une dispute, une peur, une surcharge, un souvenir ? Notez-le. Repérer le déclencheur permet de l’anticiper, de l’éviter quand c’est possible, ou d’en parler à un professionnel.</p>

<aside class="gc-callout" aria-label="Pour comprendre">
<p class="gc-callout__t">Une astuce : la méthode 5-4-3-2-1</p>
<p>Nommez 5 choses que vous voyez, 4 que vous touchez, 3 que vous entendez, 2 que vous sentez et 1 que vous goûtez. Cet exercice ramène l’attention dans le présent.</p>
</aside>

<h2>Quand demander de l’aide ?</h2>
<ul>
<li>Les crises reviennent souvent ou vous font éviter certains lieux ou personnes.</li>
<li>Vous ressentez une douleur à la poitrine, ou un malaise inhabituel : demandez un avis médical, par prudence.</li>
<li>Vous avez des pensées sombres ou l’envie de vous faire du mal : parlez-en tout de suite à une personne de confiance ou contactez les secours de votre pays.</li>
</ul>
<p>Vous pouvez aussi vous faire accompagner : <a href="/article/consultation-en-ligne-psychologue">une consultation en ligne avec un psychologue</a> est proposée à BESYP. Et pour faire le point sur votre quotidien, essayez notre <a href="/article/liste-de-controle-sante-mentale">liste de contrôle pour la santé mentale</a>.</p>
HTML;

        $liste = <<<'HTML'
<h2>Prendre sa température mentale</h2>
<p>On surveille sa fièvre, rarement son moral. Cette liste de contrôle de Pool Com BESYP propose <strong>six questions simples</strong> à vous poser, régulièrement, sans vous juger. Il n’y a pas de bonne ou de mauvaise réponse : l’objectif est de remarquer ce qui va, et ce qui mérite de l’attention.</p>

<h2>Les 6 questions</h2>
<h3>1. Est-ce que je mange régulièrement ?</h3>
<p>Sauter des repas ou manger sans appétit peut accompagner le stress, la tristesse ou l’anxiété. Un corps nourri soutient un esprit plus stable.</p>
<h3>2. Est-ce que je dors suffisamment ?</h3>
<p>Un sommeil trop court, haché ou impossible est l’un des premiers signaux d’alerte. Observez vos nuits sur plusieurs jours, pas sur une seule.</p>
<h3>3. Est-ce que je me sens bien ?</h3>
<p>Pas « parfaitement bien » : simplement, la plupart du temps, pouvez-vous ressentir de la joie, de l’intérêt, du calme ?</p>
<h3>4. Qu’est-ce que je ressens ?</h3>
<p>Mettre un mot sur une émotion (colère, honte, peur, fatigue) la rend plus facile à traverser. Écrire quelques lignes chaque soir aide beaucoup.</p>
<h3>5. Est-ce que je suis gentil·le avec moi et avec les autres ?</h3>
<p>Remarquez votre voix intérieure : vous parlez-vous comme vous parleriez à un·e ami·e ? La bienveillance se travaille, envers soi comme envers autrui.</p>
<h3>6. À qui puis-je parler ?</h3>
<p>Avoir au moins une personne à qui se confier est l’un des meilleurs protecteurs de la santé mentale. Si vous n’en voyez aucune, c’est un signe qu’il faut peut-être élargir le cercle, pas que vous êtes seul·e à porter cela.</p>

<aside class="gc-callout" aria-label="Pour comprendre">
<p class="gc-callout__t">Comment lire vos réponses ?</p>
<p>Si plusieurs réponses sont « non » ou « je ne sais pas » pendant <strong>plus de deux semaines</strong>, ou si elles nuisent à votre travail, vos études ou vos relations, c’est le moment d’en parler à un professionnel.</p>
</aside>

<h2>Et maintenant ?</h2>
<ul>
<li>Parlez à une personne de confiance dès aujourd’hui.</li>
<li>Réservez un échange avec un psychologue : <a href="/article/consultation-en-ligne-psychologue">consultation en ligne à BESYP</a>.</li>
<li>Si une crise survient : <a href="/article/calmer-une-crise-dangoisse-en-4-etapes">calmer une crise d’angoisse en 4 étapes</a>.</li>
<li>Si vous vivez ou témoignez d’une violence : <a href="/guest/openticket">créez une alerte</a>.</li>
</ul>
HTML;

        $charge = <<<'HTML'
<h2>La charge mentale, ce poids invisible</h2>
<p>Penser à tout, anticiper, organiser, ne rien oublier : la charge mentale, c’est le travail invisible de la tête. Elle s’accumule entre le travail, la famille, les études, les responsabilités… et pèse souvent plus lourd sur certaines personnes que sur d’autres. Résultat : fatigue, irritabilité, difficultés à dormir, sentiment de ne jamais en faire assez.</p>
<p>Voici <strong>cinq conseils de Pool Com BESYP</strong> pour alléger ce poids, sans tout bouleverser.</p>

<h2>Les 5 conseils</h2>
<h3>1. Faire des listes pour organiser les tâches</h3>
<p>Sortez les tâches de votre tête et mettez-les sur papier. Classez-les : <em>urgent</em>, <em>important</em>, <em>peut attendre</em>. Trois priorités par jour suffisent.</p>
<h3>2. Pratiquer la méditation pour se recentrer</h3>
<p>Cinq minutes suffisent : asseyez-vous, fermez les yeux, suivez votre respiration. Quand l’esprit s’égare, ramenez-le doucement. Ce n’est pas « ne penser à rien », c’est apprendre à revenir.</p>
<h3>3. Déléguer des tâches lorsque c’est possible</h3>
<p>Vous n’avez pas à tout porter seul·e. Répartissez : enfants, conjoint·e, collègues, ami·es. Expliquez clairement ce que vous attendez, puis lâchez prise sur la manière.</p>
<h3>4. Prendre des pauses régulières pour se reposer</h3>
<p>Programmez de vraies pauses, par exemple cinq minutes toutes les heures et demie : marcher, s’étirer, boire de l’eau, sans écran. Se reposer n’est pas perdre du temps.</p>
<h3>5. Établir des routines pour simplifier la journée</h3>
<p>Ce qui est automatique ne consomme plus d’énergie : préparer ses affaires la veille, fixer des horaires de repas, garder un rituel de coucher.</p>

<blockquote>
<p>« On n’a pas besoin de tout faire : on a besoin de faire ce qui compte. »</p>
<footer>Équipe GANXÔ</footer>
</blockquote>

<aside class="gc-callout" aria-label="Le saviez-vous ?">
<p class="gc-callout__t">Le saviez-vous ?</p>
<p>La charge mentale est souvent liée à la répartition inégale des responsabilités. En parler ouvertement avec son entourage est déjà une façon de l’alléger.</p>
</aside>

<h2>Quand c’est trop lourd</h2>
<p>Si l’épuisement dure, si vous vous sentez dépassé·e en permanence ou si vous ne trouvez plus de plaisir à rien, parlez-en. <a href="/article/consultation-en-ligne-psychologue">Un psychologue peut vous aider</a>, et notre <a href="/article/liste-de-controle-sante-mentale">liste de contrôle</a> vous donne un premier repère.</p>
HTML;

        $non = <<<'HTML'
<h2>Quand le silence devient complice</h2>
<p>Les violences basées sur le genre (VBG), le racisme, les discriminations, la haine et les violences du quotidien ont un point commun : elles prospèrent dans le silence. Pour beaucoup de personnes, se taire est une façon de se protéger. Pour la société, c’est ce qui permet à ces violences de durer.</p>
<p>Cette affiche de BESYP porte un mot simple, en lettres blanches : <strong>NON</strong>. Non au racisme, non aux discriminations, non à la haine, non aux violences, non au silence.</p>

<h2>De quoi parle-t-on ?</h2>
<ul>
<li><strong>Les VBG</strong> sont des violences exercées sur une personne à cause de son genre, réel ou supposé : violences physiques, sexuelles, psychologiques, verbales, économiques.</li>
<li><strong>Les discriminations</strong> sont des traitements défavorables fondés sur l’origine, le sexe, l’orientation sexuelle, l’identité de genre, le handicap, la religion…</li>
<li><strong>La haine</strong> prend la forme d’insultes, de menaces, de harcèlement, y compris en ligne.</li>
<li><strong>Les violations des droits humains</strong> (VDH) commencent souvent par ces violences banalisées.</li>
</ul>

<blockquote>
<p>« Le silence ne protège pas la victime. Il protège celui ou celle qui fait du mal. »</p>
<footer>Équipe GANXÔ</footer>
</blockquote>

<h2>Que faire ?</h2>
<h3>Si vous êtes victime</h3>
<ul>
<li>Ce qui vous arrive n’est pas de votre faute.</li>
<li>Parlez-en à une personne de confiance, ou <a href="/guest/openticket">créez une alerte VBG</a> : l’équipe GANXÔ vous écoute et vous oriente.</li>
<li>Gardez des preuves si vous le pouvez : messages, photos, dates, noms.</li>
</ul>
<h3>Si vous êtes témoin</h3>
<ul>
<li>Ne détournez pas le regard : un mot de soutien peut tout changer.</li>
<li>Écoutez sans juger et sans exiger de preuves.</li>
<li>Orientez la personne vers de l’aide, sans la forcer à agir.</li>
</ul>

<aside class="gc-callout" aria-label="Pour comprendre">
<p class="gc-callout__t">Pour comprendre</p>
<p>Un témoin n’a pas besoin d’être un héros. Dire « Je suis là » ou « Ça n’est pas normal » est déjà un acte de soutien. Et si la personne est en danger immédiat, contactez les secours de votre pays.</p>
</aside>

<h2>Aller plus loin</h2>
<p>Retrouvez nos réponses dans la <a href="/faq">foire aux questions</a>, parlez à <a href="/assistant">l’assistant GANXÔ</a>, ou lisez nos repères sur <a href="/article/liste-de-controle-sante-mentale">la santé mentale</a> et <a href="/article/calmer-une-crise-dangoisse-en-4-etapes">la gestion de l’angoisse</a>.</p>
HTML;

        return [
            [
                'title' => 'Consultation en ligne avec le psychologue : parler sans se déplacer',
                'articleslug' => 'consultation-en-ligne-psychologue',
                'category_id' => $cats['sante-mentale'],
                'tags' => 'santé mentale,psychologue,consultation en ligne,BESYP,écoute',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-consultation-en-ligne-psychologue.jpg',
                'chapo' => 'BESYP propose des consultations en ligne avec un psychologue. À qui elles s’adressent, comment se passe le premier échange et comment prendre contact par WhatsApp : tout ce qu’il faut savoir pour franchir le pas.',
                'image_caption' => 'Deux personnes échangent : une consultation en ligne avec le psychologue de BESYP.',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $consultation, 'date' => '2026-11-03 09:00:00',
            ],
            [
                'title' => 'Le saviez-vous ? Les codes vestimentaires ne devraient pas discriminer',
                'articleslug' => 'codes-vestimentaires-non-discriminatoires',
                'category_id' => $cats['pool-com'],
                'tags' => 'identité de genre,non-discrimination,droits,école,travail',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-codes-vestimentaires-non-discriminatoires.jpg',
                'chapo' => 'Un règlement vestimentaire peut exclure sans en avoir l’air. Pourquoi les codes devraient être flexibles et non discriminatoires, et comment chacun·e peut s’habiller selon son identité de genre sans craindre la sanction.',
                'image_caption' => 'Illustration « Le saviez-vous ? » : deux personnes enlacées, en tenues colorées.',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $codes, 'date' => '2026-11-05 09:00:00',
            ],
            [
                'title' => 'Avortement sécurisé : une question de santé qui concerne aussi les minorités de sexe et de genre',
                'articleslug' => 'avortement-securise-minorites-sexe-genre',
                'category_id' => $cats['sante-et-droits'],
                'tags' => 'avortement sécurisé,santé sexuelle,minorités de genre,LGBTQ,santé et droits',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-avortement-securise-minorites.jpg',
                'chapo' => 'Un avortement sécurisé sauve des vies, et il concerne aussi les femmes lesbiennes et bisexuelles, les hommes trans et les personnes non binaires. Repères de santé, risques à éviter et adresses pour être orienté·e sans jugement.',
                'image_caption' => 'Trois femmes portant les lettres L, B et Q : l’avortement sécurisé concerne aussi les minorités de sexe et de genre.',
                'image_credit' => $credit,
                'source' => 'Organisation mondiale de la santé (repères généraux) ; BESYP',
                'author_name' => 'Équipe GANXÔ',
                'message' => $avortement, 'date' => '2026-11-07 09:00:00',
            ],
            [
                'title' => 'Calmer une crise d’angoisse en 4 étapes',
                'articleslug' => 'calmer-une-crise-dangoisse-en-4-etapes',
                'category_id' => $cats['sante-mentale'],
                'tags' => 'angoisse,santé mentale,respiration,crise d’angoisse',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-calmer-crise-angoisse.jpg',
                'chapo' => 'Cœur qui s’emballe, souffle court, peur de perdre le contrôle : une crise d’angoisse passe. Quatre gestes simples pour la traverser, une astuce pour revenir au présent et les signes qui doivent vous pousser à demander de l’aide.',
                'image_caption' => 'Une femme assise, les bras autour des genoux : l’angoisse peut se traverser par étapes.',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $angoisse, 'date' => '2026-11-09 09:00:00',
            ],
            [
                'title' => 'Liste de contrôle pour la santé mentale : 6 questions à vous poser',
                'articleslug' => 'liste-de-controle-sante-mentale',
                'category_id' => $cats['sante-mentale'],
                'tags' => 'santé mentale,bien-être,autoévaluation,sommeil,écoute',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-liste-controle-sante-mentale.jpg',
                'chapo' => 'Manger, dormir, ressentir, se parler avec bienveillance, avoir quelqu’un à qui parler : six questions simples pour faire le point sur votre santé mentale, et savoir quand demander un soutien.',
                'image_caption' => 'Six bulles colorées, six questions pour prendre soin de sa santé mentale.',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $liste, 'date' => '2026-11-10 09:00:00',
            ],
            [
                'title' => '5 conseils pour diminuer la charge mentale',
                'articleslug' => '5-conseils-diminuer-charge-mentale',
                'category_id' => $cats['sante-mentale'],
                'tags' => 'charge mentale,santé mentale,organisation,repos',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-cinq-conseils-charge-mentale.jpg',
                'chapo' => 'Listes, méditation, délégation, pauses, routines : cinq conseils concrets pour alléger la charge mentale, ce poids invisible qui épuise, et reprendre la main sur son quotidien.',
                'image_caption' => 'Une femme qui porte un rocher sur le dos : l’image de la charge mentale.',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $charge, 'date' => '2026-11-11 09:00:00',
            ],
            [
                'title' => 'Disons NON aux VBG, au racisme, aux discriminations et à la haine',
                'articleslug' => 'disons-non',
                'category_id' => $cats['pool-com'],
                'tags' => 'VBG,RACISME,STIGMATISATIONS,discriminations,haine,silence',
                'status' => 'Published', 'privatemode' => 0,
                'featureimage' => 'gn-disons-non.jpg',
                'chapo' => 'VBG, racisme, discriminations, haine, silence : un seul mot pour les refuser, NON. Ce qu’ils recouvrent, pourquoi le silence aggrave tout, et ce que vous pouvez faire, que vous soyez victime ou témoin.',
                'image_caption' => 'Une femme se tient la tête entre les mains, entourée des mots VBG, racisme, discriminations, haine, silence, violence : « NON ».',
                'image_credit' => $credit,
                'source' => 'BESYP, Pool Com',
                'author_name' => 'Équipe GANXÔ',
                'message' => $non, 'date' => '2026-11-12 08:00:00',
            ],
        ];
    }
}
