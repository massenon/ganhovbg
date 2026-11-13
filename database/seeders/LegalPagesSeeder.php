<?php

namespace Database\Seeders;

use App\Models\Pages;
use Illuminate\Database\Seeder;

/**
 * Pages légales de GANXÔ Alerte VBG (mentions légales, confidentialité, conditions, cookies).
 * Idempotent : mise à jour par slug. Les informations que l'équipe doit fournir (forme juridique, adresse,
 * hébergeur, durées de conservation, juridiction) sont signalées par <mark>À compléter</mark> : aucune donnée n'est inventée.
 * Lancer : php artisan db:seed --class=LegalPagesSeeder
 */
class LegalPagesSeeder extends Seeder
{
    public function run()
    {
        foreach ($this->pages() as $slug => $p) {
            Pages::updateOrCreate(
                ['pageslug' => $slug],
                ['pagename' => $p[0], 'pagedescription' => $p[1], 'viewonpages' => 'footer', 'status' => 1]
            );
        }
    }

    private function pages(): array
    {
        $todo = fn (string $what) => '<mark>[À compléter : ' . $what . ']</mark>';

        $mentions = '
<p>Les présentes mentions légales décrivent l’éditeur du site GANXÔ Alerte VBG (« le site ») et les conditions générales de sa consultation. Dernière mise à jour : novembre 2026.</p>

<h2>Éditeur du site</h2>
<ul>
<li><strong>Nom :</strong> GANXÔ Alerte VBG (marque Ganho)</li>
<li><strong>Éditeur :</strong> Bénin Synergies PLUS · BESYP</li>
<li><strong>Forme juridique :</strong> Association</li>
<li><strong>Adresse :</strong> Abomey-Calavi, Quartier DJADJO (Bénin)</li>
<li><strong>Immatriculation :</strong> récépissé n° En.2011/0491/DEP-ALT.-LITT/SG/SAG-ASSOC du 11 novembre 2011 ; modification n° 2019/2561/DEP-LITT/SG/SAG-ASSOC</li>
<li><strong>Directeur·rice de la publication :</strong> Bénin Synergies PLUS · BESYP</li>
<li><strong>Contact :</strong> <a href="mailto:contact@ganho.app">contact@ganho.app</a></li>
</ul>

<h2>Hébergement</h2>
<p>Le site est hébergé par TrueHost.</p>

<h2>Conception</h2>
<p>Conception et développement : Dr MASSENON Rhodes, <a href="https://www.massenonrhodes.com" rel="noopener">www.massenonrhodes.com</a>.</p>

<h2>Nature du service</h2>
<p>GANXÔ Alerte VBG permet de transmettre une alerte à l’équipe GANXÔ, d’obtenir des repères grâce à l’Assistant Ganho et de consulter des ressources sur les violences basées sur le genre et la santé mentale. Les informations publiées sont d’ordre général : elles ne remplacent ni un avis médical, ni un accompagnement psychologique, ni un conseil juridique personnalisé.</p>
<p>Le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ : il ne contacte ni la police, ni les secours. En situation de danger immédiat, joignez d’abord les secours de votre pays.</p>

<h2>Propriété intellectuelle</h2>
<p>La marque, le logo, la charte graphique, les textes et le code du site sont la propriété de leurs titulaires respectifs ; l’association Bénin Synergies PLUS · BESYP détient les droits sur les contenus qu’elle produit. Toute reproduction non autorisée est interdite.</p>
<p>Plusieurs photographies proviennent de la banque d’images Pexels et sont utilisées selon la licence Pexels (<a href="https://www.pexels.com/license/" rel="noopener">pexels.com/license</a>). Crédits : Pavel Danilyuk, Vk Moraes, Nduwa Siachaba, Anete Lusina, RDNE Stock project.</p>

<h2>Données personnelles et cookies</h2>
<p>Le traitement de vos données est décrit dans la <a href="/page/politique-de-confidentialite">politique de confidentialité</a>. L’usage des cookies est décrit dans la <a href="/page/politique-de-cookies">politique de cookies</a>.</p>

<h2>Responsabilité et liens externes</h2>
<p>L’éditeur s’efforce de fournir des informations exactes et à jour, sans garantie d’exhaustivité. Le site peut contenir des liens vers des sites tiers dont l’éditeur ne maîtrise pas le contenu.</p>

<h2>Droit applicable</h2>
<p>Les présentes mentions sont soumises au droit béninois. En cas de litige, et à défaut de règlement amiable, les juridictions béninoises compétentes sont celles du ressort du siège de l’association (Abomey-Calavi).</p>';

        $privacy = '
<p>Cette politique explique quelles données GANXÔ Alerte VBG traite, pourquoi, et comment exercer vos droits. Dernière mise à jour : novembre 2026.</p>

<h2>Qui est responsable du traitement ?</h2>
<p>Le responsable du traitement est l’association Bénin Synergies PLUS · BESYP (Abomey-Calavi, Quartier DJADJO, Bénin). Contact : <a href="mailto:contact@ganho.app">contact@ganho.app</a>.</p>

<h2>Quelles données collectons-nous ?</h2>
<ul>
<li><strong>Une alerte :</strong> votre adresse e-mail, le sujet, le message, la catégorie choisie et les pièces jointes éventuelles, pour que l’équipe puisse vous répondre et suivre l’alerte.</li>
<li><strong>Le bouton SOS :</strong> vos coordonnées GPS, uniquement après votre confirmation. Elles sont jointes au message de l’alerte, que vous pouvez modifier avant de l’envoyer.</li>
<li><strong>Un compte :</strong> votre adresse e-mail, votre nom et un mot de passe conservé sous forme hachée (jamais en clair).</li>
<li><strong>Le formulaire de contact :</strong> nom, adresse e-mail, téléphone (facultatif), sujet et message.</li>
<li><strong>L’Assistant Ganho :</strong> vos messages ne sont pas conservés. Seuls des compteurs anonymes sont enregistrés (réponse trouvée ou non, message jugé urgent ou non), sans texte, sans adresse IP et sans identifiant.</li>
<li><strong>Données techniques :</strong> cookies nécessaires au fonctionnement, journaux techniques du serveur (dont l’adresse IP) pour la sécurité et le bon fonctionnement du site.</li>
</ul>

<h2>Pourquoi et sur quelle base ?</h2>
<p>Ces données servent à recevoir et traiter vos alertes et messages, à gérer votre compte, à sécuriser le site et à améliorer l’assistant. Base juridique : la mission de l’association Bénin Synergies PLUS · BESYP (accompagnement et orientation des personnes concernées par les violences basées sur le genre), votre consentement (alerte, position GPS, cookies non nécessaires) et notre intérêt légitime à sécuriser le site.</p>

<h2>Qui reçoit vos données ?</h2>
<ul>
<li>Les membres habilités de l’équipe GANXÔ, tenus à la confidentialité.</li>
<li>L’hébergeur du site (TrueHost) et le prestataire d’envoi des e-mails, qui n’agissent que sur nos instructions.</li>
<li>Des partenaires d’accompagnement (associations, professionnel·les de santé, psychologues ou juristes) uniquement avec votre accord, pour vous orienter.</li>
<li>Les autorités, uniquement si la loi l’exige ou en cas de danger grave et imminent.</li>
</ul>
<p>Vos données ne sont ni vendues ni cédées à des fins commerciales.</p>

<h2>Combien de temps les conservons-nous ?</h2>
<ul>
<li><strong>Alertes et messages associés :</strong> 3 ans après la clôture de l’alerte, puis suppression ou anonymisation.</li>
<li><strong>Position GPS (bouton SOS) :</strong> conservée avec l’alerte, pas plus longtemps qu’elle.</li>
<li><strong>Formulaire de contact :</strong> 12 mois.</li>
<li><strong>Compte :</strong> tant qu’il est actif, puis suppression après 24 mois d’inactivité ou à votre demande.</li>
<li><strong>Journaux techniques (adresse IP) :</strong> 12 mois.</li>
<li><strong>Compteurs anonymes de l’assistant :</strong> 24 mois.</li>
</ul>

<h2>Vos droits</h2>
<p>Vous pouvez demander l’accès à vos données, leur rectification ou leur suppression, vous opposer à un traitement ou en demander la limitation, en écrivant à <a href="mailto:contact@ganho.app">contact@ganho.app</a>. Nous répondrons dans un délai d’un mois au plus. Vous pouvez aussi saisir l’Autorité de protection des données à caractère personnel (APDP) du Bénin, ou celle de votre pays.</p>

<h2>Sécurité</h2>
<p>Nous mettons en œuvre des mesures techniques et organisationnelles raisonnables pour protéger vos données. Aucun système n’étant infaillible, évitez de partager des informations qui ne sont pas nécessaires à votre demande.</p>

<h2>Mineur·es</h2>
<p>Le site s’adresse aux personnes de 15 ans et plus. Une personne plus jeune peut transmettre une alerte avec l’aide d’un·e adulte de confiance ; l’équipe traite ces alertes avec une vigilance particulière et peut orienter vers les services de protection de l’enfance. Si vous avez moins de 18 ans, vos données restent confidentielles et ne sont jamais utilisées à des fins commerciales.</p>

<h2>Modifications</h2>
<p>Cette politique peut évoluer. La date de dernière mise à jour figure en haut de la page.</p>';

        $terms = '
<p>Ces conditions encadrent l’utilisation du site GANXÔ Alerte VBG. En l’utilisant, vous les acceptez. Dernière mise à jour : novembre 2026.</p>

<h2>Objet du site</h2>
<p>Le site permet de transmettre une alerte à l’équipe GANXÔ, de dialoguer avec l’Assistant Ganho, de consulter des ressources et de rejoindre le forum de la communauté.</p>

<h2>Ce que le site ne fait pas</h2>
<ul>
<li>Il ne remplace ni un avis médical, ni un accompagnement psychologique, ni un conseil juridique personnalisé.</li>
<li>L’Assistant Ganho donne des repères généraux à partir de réponses préparées par l’équipe ; il ne pose pas de diagnostic.</li>
<li>Le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ ; il ne contacte ni la police, ni les secours. En danger immédiat, joignez d’abord les secours de votre pays.</li>
<li>L’équipe s’efforce d’accuser réception d’une alerte sous 48 heures ouvrées ; ce délai n’est pas garanti et ne constitue pas un service d’intervention immédiate.</li>
</ul>

<h2>Votre usage du site</h2>
<ul>
<li>Vous transmettez des informations exactes et de bonne foi ; les fausses alertes sont interdites.</li>
<li>Vous respectez les autres personnes : pas de harcèlement, de discours haineux, de contenu illicite ou de publication de données personnelles d’autrui sans leur accord.</li>
<li>Vous gardez vos identifiants confidentiels.</li>
</ul>

<h2>Contenus et propriété intellectuelle</h2>
<p>Vous restez propriétaire des contenus que vous transmettez et autorisez l’équipe à les traiter pour répondre à votre demande. Les éléments du site (marque, logo, textes, graphismes) sont protégés : voir les <a href="/page/mentions-legales">mentions légales</a>.</p>

<h2>Suspension</h2>
<p>L’équipe peut suspendre ou supprimer un compte ou un contenu qui ne respecte pas ces conditions ou qui met en danger d’autres personnes.</p>

<h2>Responsabilité</h2>
<p>Le site est fourni « en l’état », sans garantie de disponibilité continue. L’éditeur ne peut être tenu responsable des interruptions ou des contenus de sites tiers, dans la mesure permise par la loi applicable.</p>

<h2>Données personnelles</h2>
<p>Voir la <a href="/page/politique-de-confidentialite">politique de confidentialité</a>.</p>

<h2>Droit applicable et contact</h2>
<p>Ces conditions sont soumises au droit béninois ; les juridictions compétentes sont celles du ressort du siège de l’association (Abomey-Calavi). Questions : <a href="mailto:contact@ganho.app">contact@ganho.app</a>.</p>';

        $cookies = '
<p>Cette page explique comment GANXÔ Alerte VBG utilise les cookies. Dernière mise à jour : novembre 2026.</p>

<h2>Qu’est-ce qu’un cookie ?</h2>
<p>Un cookie est un petit fichier enregistré dans votre navigateur. Il permet au site de fonctionner correctement et de se souvenir de certains choix.</p>

<h2>Cookies utilisés</h2>
<table>
<thead><tr><th>Cookie</th><th>Rôle</th><th>Durée</th></tr></thead>
<tbody>
<tr><td>Session du site</td><td>Maintient votre session (connexion, formulaires). Nécessaire.</td><td>Environ 2 heures</td></tr>
<tr><td>XSRF-TOKEN</td><td>Protège les formulaires contre les envois frauduleux. Nécessaire.</td><td>Environ 2 heures</td></tr>
<tr><td>Consentement aux cookies</td><td>Mémorise votre réponse à la bannière.</td><td>12 mois</td></tr>
<tr><td>Mesure d’audience</td><td>Statistiques de fréquentation (Google Analytics), uniquement si elle est activée par l’équipe et après votre accord.</td><td>Jusqu’à 13 mois (cookies Google Analytics)</td></tr>
</tbody>
</table>
<p>L’Assistant Ganho enregistre votre conversation uniquement dans la mémoire temporaire de votre onglet (stockage de session) : elle disparaît à la fermeture de l’onglet ou quand vous cliquez sur « Effacer ».</p>

<h2>Gérer vos choix</h2>
<p>Vous pouvez accepter les cookies via la bannière, ou les supprimer à tout moment dans les réglages de votre navigateur. Refuser les cookies nécessaires peut empêcher certaines fonctions (connexion, envoi d’une alerte).</p>

<h2>Plus d’informations</h2>
<p>Voir la <a href="/page/politique-de-confidentialite">politique de confidentialité</a> ou écrire à <a href="mailto:contact@ganho.app">contact@ganho.app</a>.</p>';

        return [
            'mentions-legales' => ['Mentions légales', $mentions],
            'politique-de-confidentialite' => ['Politique de confidentialité', $privacy],
            'conditions-d-utilisation' => ['Conditions d’utilisation', $terms],
            'politique-de-cookies' => ['Politique de cookies', $cookies],
        ];
    }
}
