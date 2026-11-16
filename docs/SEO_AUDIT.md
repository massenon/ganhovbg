# Audit SEO front-end : ganho.app et forum.ganho.app

**Date : 14 novembre 2026.** Périmètre : pages publiques de `ganhovbg` (ganho.app) et du forum (forum.ganho.app).
Méthode : crawl de 83 pages en local, mesure de performance en émulation mobile, lecture du code des gabarits SEO.

## 0. Ce que cet audit n'a PAS pu vérifier

| Limite | Conséquence |
|---|---|
| Tout a été mesuré en **local** (`php -S`, sans Apache, sans TLS, sans CDN) | Les en-têtes de production (HSTS, cache, compression, redirections `www`, HTTPS) ne sont pas vérifiés. |
| Pas de **Search Console**, pas de données **CrUX** (utilisateurs réels) | Aucune donnée d'indexation réelle (pages indexées, erreurs, requêtes). Les Core Web Vitals ci-dessous sont des **mesures de laboratoire**, pas des données de terrain. |
| Pas de Lighthouse ni de PageSpeed Insights (pas de réseau vers le site en ligne) | Les scores 0-100 ne sont pas donnés. Les métriques (FCP, LCP, CLS, TBT) viennent d'un script Playwright, processeur ralenti ×4 et réseau 4G simulé (1,6 Mbit/s, 150 ms). |
| Forum mesuré **sans compression** (`php -S` n'a pas `mod_deflate`) | Les poids du forum sont ceux d'avant compression ; en production le `.htaccess` active la compression (gain estimé ×4 sur CSS et JS). |
| Pas de crawl du site en ligne ni d'audit de liens externes, de backlinks, de mots-clés ou de concurrents | Pas d'analyse de positionnement. |
| Contenu rendu par JavaScript non testé avec Googlebot | Les pages de ganhovbg sont rendues côté serveur (le contenu est dans le HTML) ; le forum aussi pour les pages publiques. |

## 1. Résumé

**ganhovbg** est dans un bon état technique : titres, descriptions, canonical, Open Graph, Twitter Card et JSON-LD sont présents et valides sur toutes les pages publiques ; aucune image sans `alt` ; aucun JSON-LD invalide ; CLS à 0.
L'audit a trouvé **quatre défauts réels, corrigés dans cette séance** (voir §2), dont une régression de sécurité SEO (pages de connexion devenues indexables).

Le **forum** a de bonnes bases (robots dynamique avec `Sitemap`, sitemap valide, indexation limitée aux auteurs consentants) mais :
- ses pages sont **très lourdes** (≈ 1,6 Mo avant compression, FCP ≈ 10 s en émulation mobile lente, CLS 0,49 sur une discussion) ;
- les URL inconnues répondent **302 vers la page de connexion** au lieu d'un 404 ;
- les contenus éducatifs et les BD, publics, sont en **`noindex,nofollow`** : c'est le contenu le plus utile au référencement et il est exclu.

## 2. Corrections appliquées pendant l'audit

| # | Défaut | Constat | Correction | Vérifié |
|---|---|---|---|---|
| 1 | `<html lang="en">` | 45 pages sur 50 déclaraient l'anglais alors que le contenu et `hreflang` sont en français | `lang="fr"` dans `layouts/gnlean.blade.php` | Oui (crawl) |
| 2 | **Pages de connexion indexables** | Après le renommage des URL, `/espace-client/*` et `/administration/*` n'étaient plus dans la liste `noindex` de `includes/seo.blade.php` (qui ne connaissait que `customer` et `admin`) : 5 pages en `index,follow` | Ajout de `espace-client` et `administration` | Oui : `noindex,follow` |
| 3 | Liens et fils d'Ariane vers d'anciennes URL | Liens `url('customer/login')` et éléments de `BreadcrumbList` pointant vers `/customer/login` (301) | Remplacés par `/espace-client/connexion`, `/inscription`, `/mot-de-passe-oublie` (liens et JSON-LD) | Oui : 0 lien hérité sur les pages testées |
| 4 | LCP de `/knowledge` | L'image de la première carte (élément LCP) était en `loading="lazy"` | `fetchpriority="high"` à la place pour la carte « à la une » | Oui : LCP **2 964 ms → 1 844 ms** (une mesure avant, une après) |
| 5 | Forum : discussions sans description propre | Toutes les discussions avaient la **même** `description`/`og:description` générique et un titre « Auteur sur GANXÔ FORUM » | Pour une discussion publique indexable avec texte : titre « Auteur : début du texte », description = début du texte, et nœud JSON-LD `DiscussionForumPosting` (`headline`, `text`, `datePublished`, `author`, `commentCount`, `interactionStatistic`) | Oui sur 2 discussions ; une discussion image seule garde la description générique |

Les corrections 1 à 4 sont dans `ganhovbg` et la 5 dans `forumganho/themes/default/apps/main/seo.phtml`. Elles ne sont **pas encore en ligne**.

## 2 bis. Deuxième passe : corrections des points ouverts (14 novembre 2026)

Le rapport initial listait des points ouverts ; ils ont été traités le même jour. État réel de chacun :

| Point | État | Ce qui a été fait | Vérifié |
|---|---|---|---|
| Forum : URL inconnues en 302 | **Corrigé (partiellement un artefact)** | Le « 302 vers la connexion » des chemins avec tirets venait de mon **routeur de test local** : sous Apache, ils passent par `ErrorDocument 404` et renvoient un vrai 404. Le vrai défaut concernait les chemins qui ressemblent à un pseudo (3 à 25 caractères) : ils faisaient `302 → /404 → 404`. Nouvelle fonction `cl_not_found()` : vraie page 404 directe pour profils, discussions, fiches, BD et pages statiques inconnues ; le SPA du forum garde sa redirection JSON | Oui, sur un **vrai Apache avec PHP** : 404 sur 5 types d'URL inconnues, 200 sur les pages existantes |
| Forum : contenus éducatifs et BD en `noindex` | **Corrigé** | `index,follow`, fil d'Ariane, `CollectionPage` + `ItemList` (listes), `Article` (fiches), `ComicStory` (BD), image Open Graph, 27 URL ajoutées au sitemap | Oui (JSON-LD relu, sitemap 568 → 595 URL) |
| Vie privée : indexation par défaut | **Corrigé dans le code ; à lancer en production** | Les 5 chemins de création de compte posent `index_privacy = 'N'`. Script `core/seeders/index_privacy_optin.php` : valeur par défaut de la colonne en `N` ; avec `RESET_EXISTING=1`, remet les 228 comptes existants à `N` | Oui en local (sitemap 595 → 32 URL, discussions en `noindex`) puis base locale remise à l'état d'origine. **Non lancé en production** |
| Forum : pages lourdes | **Amélioré, pas résolu** | Voir §7 bis | Oui (mesures avant/après) |
| ganho.app : Summernote et jQuery sur le formulaire public | **Corrigé** | `<textarea>` natif, JS natif (`includes/gn/alertjs.blade.php`), 367 Ko → 84 Ko de JS, 528 → 217 Ko de page ; texte stocké en brut, affichage échappé partout (classe `TicketText`) | Oui : parcours complet jusqu'à l'envoi, XSS testé, données de test supprimées |
| ganho.app : pages minces | **Corrigé (textes à valider)** | Introductions de 195 à 208 mots pour les 4 catégories de violences et de 90 à 106 mots pour les 3 autres ; section « Quand nous écrire ? » sur `/contact-us` ; `/faq/5` et `/faq/6` (≈ 535 mots, contenu de la base) laissées | Oui (pages de 337 à 567 mots) |
| En-têtes de sécurité | **Ajoutés ; à confirmer en production** | `nosniff`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, CSP minimale (`frame-ancestors`, `base-uri`, `object-src`), HSTS seulement en HTTPS. Forum : `.htaccess`. ganho.app : `.htaccess` **racine** (le domaine pointe sur la racine : c'est lui qui s'applique aux pages) et `public/.htaccess` (fichiers statiques) | Oui sur un **vrai Apache** : pages dynamiques et fichiers statiques des deux sites |
| Forum : accès directs dangereux | **Corrigé** | `core/`, `install/`, `site_backups/` entièrement interdits ; gabarits `apps/` et `themes/*/apps/` (`.php`, `.phtml`) interdits ; PHP non exécutable dans `upload/` | Oui (403) ; pages et API toujours 200 |
| Titres de 61 caractères | **Corrigé** | Limite à 59 caractères + « … » = 60 (`seo.blade.php`, `knowledge-view.blade.php`) | Oui (code) |
| Sitemap ganho.app : 3 URL sans `lastmod` | **Corrigé** | Date de modification des vues | Oui : 45/45 |
| Forum : `robots.txt` statique | **Corrigé** | Supprimé ; `robots.php` interdit aussi `/api/mobile/` et `/mobile_v1.php` | Oui sur Apache |
| Forum : erreur console du vieux service worker | **Corrigé** | Enregistrement mort retiré (portée invalide, code cassé) ; le forum garde son `/sw.js` | Oui : 0 erreur console sur 11 pages membre et administrateur |

Autres découvertes de cette passe :
- **Redirection HTTPS du forum** (jamais testée jusqu'ici) : validée sur Apache (`301` vers `https://forum.ganho.app/...`, rien si `X-Forwarded-Proto: https`).
- **Production de ganho.app** : le domaine pointe sur la **racine du projet** (la trace d'erreur montrait `ganho.app/server.php`), donc `public/.htaccess` ne couvre que les fichiers statiques.
- **En-tête `Authorization`** : transmis à PHP par la règle `E=HTTP_AUTHORIZATION`, vérifié avec l'API mobile du forum derrière Apache.
- Le bug de la page de confirmation d'alerte (erreur 500, route `gusettiket` au lieu de `gusetticket`) existait avant ; corrigé.

## 3. Crawlabilité et indexation

### 3.1 robots.txt

| Site | Constat | Verdict |
|---|---|---|
| ganho.app | `Allow: /`, interdictions d'`/administration`, `/espace-client`, `/api`, `/install`, `/search`, `/guest/ticket*`, dossiers d'uploads privés ; `Sitemap: https://ganho.app/sitemap.xml` | Correct |
| forum.ganho.app | `robots.php` dynamique (Apache le sert à la place du `robots.txt` statique grâce à la règle de réécriture) : interdictions de `/admin_panel`, `/native_api/`, `/mobile_api/`, `/oauth`…, `Allow: /upload/`, `Sitemap: https://forum.ganho.app/sitemap.xml` | Correct |
| Forum : `robots.txt` **statique** laissé à la racine | Ancien fichier sans `Sitemap` ni interdictions récentes ; seul le routeur de test local l'a servi | **À supprimer** (risque si la réécriture saute un jour) |
| Les deux | robots.txt de ganho.app est servi avec des cookies de session et `Cache-Control: no-cache, private` | Mineur : à rendre cacheable |

Un `Disallow` n'empêche pas l'indexation d'une URL liée ailleurs ; c'est pourquoi la balise `noindex` des pages de connexion (correction 2) compte.

### 3.2 Sitemaps

| Site | Constat |
|---|---|
| ganho.app | XML valide, **45 URL**, toutes en `https://ganho.app`, sans doublon, 42 avec `lastmod` (accueil, assistant, FAQ, 19 articles, 7 catégories, pages légales, contact). Les URL de connexion et d'espace client n'y figurent pas. |
| forum.ganho.app | XML valide, **568 URL** (5 pages statiques, discussions, profils) avec `lastmod`, `Cache-Control: public, max-age=3600`. Limité aux auteurs ayant « visibilité dans la recherche » = oui. |
| À corriger | ganho.app : 3 URL sans `lastmod`. Forum : `changefreq` et `priority` sont ignorés par Google (inutiles, inoffensifs). |

**Point de vie privée à décider** : sur le forum, la visibilité dans la recherche vaut « oui » pour **les 228 comptes** (c'est la valeur par défaut, pas un choix explicite). Le code respecte bien le réglage, mais en pratique tout est indexable. Pour une communauté LGBTQ au Bénin, une inscription avec indexation désactivée par défaut (opt-in) est plus prudente.

### 3.3 Codes de réponse et redirections

| Test | ganho.app | forum |
|---|---|---|
| Page inexistante | **404** + `noindex,follow` : correct | 404 réel sous Apache (le 302 observé en première passe venait du routeur de test) ; chemins de forme « pseudo » : 302 puis 404, **corrigé en 404 direct** (§2 bis) |
| Accueil | 200 | `/` → 302 → `/guest` (en `noindex`) : la page d'accueil du forum n'est pas indexable |
| Liens internes cassés | 2 liens vers d'anciennes URL (301), corrigés | 0 |
| Pages exigeant une connexion | Non testées une par une | `/admin_panel`, `/explore` → redirigent vers `/404` |

### 3.4 Balises d'indexation

| Site | Constat |
|---|---|
| ganho.app | 49 pages `index,follow,max-image-preview:large,max-snippet:-1`, 404 et `/offline` en `noindex`. Canonical absolu en HTTPS sur 49/50 (`/offline` volontairement sans), toutes identiques à l'URL attendue. `hreflang` : `fr` et `x-default` vers la même URL (inoffensif). |
| forum | Pages statiques, profils et discussions publiques : `index,follow` ; `/guest`, `/suggest`, `/alert` : `noindex` ; **`/contenus-educatifs` et `/bandes-dessinees` (accessibles sans compte) : `noindex,nofollow`**. Canonical présent partout (en test local il porte l'hôte local, en production `forum.ganho.app`). |

## 4. Optimisation de page (on-page)

### ganho.app (50 pages HTML analysées)

| Contrôle | Résultat |
|---|---|
| `<title>` présent | 50/50 ; aucun doublon |
| Longueur du title | 4 articles à 61 caractères (limite 60), 1 article ancien à 20 (« L'harcèlement Sexuel ») |
| Meta description | 49/50 (seule `/offline` n'en a pas, à raison) ; 1 de 65 caractères (`/faq/5`) ; aucun doublon ; aucune > 160 |
| H1 | 1 par page partout (sauf la connexion d'administration) ; pas de saut de niveau de titres |
| H1 identique au title | 3 articles anciens (inoffensif) |
| Images | 0 sans `alt`, 0 sans largeur/hauteur (sauf la connexion admin), `<picture>` WebP sur les cartes et héros |
| Liens `_blank` | tous avec `noopener` |
| Contenu | Articles : plus de 300 mots. **Pages minces (< 150 mots)** : 4 catégories de violences (92 à 96 mots), `/faq/5` et `/faq/6` (≈ 120 mots), `/contact-us` (102), pages techniques |

### forum

| Contrôle | Résultat |
|---|---|
| Titles | Présents, avec suffixe « GANXÔ FORUM » ; discussions maintenant descriptifs (correction 5) |
| Descriptions | Générique du site sur les pages statiques et les profils (identique d'une page à l'autre) ; 12 caractères sur un profil, 22 sur une discussion |
| H1 | **Aucune balise `<h1>`** sur les pages statiques, profils et discussions : le titre de page est dans `div role="heading" aria-level="1"`, accessible mais pas un vrai H1. Les pages `noindex` (éducatif, BD, alerte) ont un H1. |
| Contenu | Pages légales 460 à 790 mots ; `À propos` 259 ; FAQ 180 |

## 5. Open Graph, Twitter Card

| | ganho.app | forum |
|---|---|---|
| `og:title`, `og:description`, `og:image`, `og:url`, `og:type`, `og:site_name`, `og:locale` | 49/50 pages complètes (`/offline` exclue). Articles : `og:type=article`, `og:image` + `secure_url`, largeur, hauteur, `alt`. `og:url` = canonical partout. | Complets (11 balises) ; discussions : 8 balises, `og:type=article`, image = première image de la publication |
| Twitter | `summary_large_image` avec titre, description, image, `alt` | `summary` (discussions, pages statiques) : `summary_large_image` rendrait mieux avec une image |
| Réserve | Les images des articles sont construites avec l'hôte de la requête (`asset()`), pas avec `APP_URL` ; correct en production tant que le site n'est atteint que par `ganho.app` | Image d'accueil/pages statiques : logo (pas d'image 1200×630 dédiée) |

Vérification non faite : rendu réel dans le débogueur Facebook, LinkedIn et X (il faut une URL publique).

## 6. Schema.org / JSON-LD

| | ganho.app | forum |
|---|---|---|
| Validité JSON | **0 erreur** sur 49 pages | 0 erreur |
| Types | `Organization` + `WebSite` + `WebPage` + `BreadcrumbList` (49), `NewsArticle` (22 articles), `FAQPage` (20 pages) | `Organization`, `WebSite`, `WebPage`/`AboutPage`, `BreadcrumbList` ; **`DiscussionForumPosting`** sur les discussions avec texte (ajouté) ; **aucun JSON-LD sur les profils** (voulu : vie privée) |
| `NewsArticle` | `headline`, `image`, `datePublished`, `dateModified`, `author`, `publisher`, `mainEntityOfPage` : tous présents | — |
| `BreadcrumbList` | `position` correcte, `name` partout, `item` sur tous les niveaux sauf le dernier | idem (structure vérifiée par le crawler) |
| Fil d'Ariane visible | Présent sur les pages internes, cohérent avec le JSON-LD (détecté par le crawler). L'accueil porte un `BreadcrumbList` à 1 seul élément (sans effet) | Présent dans le JSON-LD ; affichage visible non vérifié |
| Points d'attention | `Organization` sans `sameAs` (profils sociaux inconnus à ce jour) ; `FAQPage` : depuis 2023, Google ne montre le résultat enrichi FAQ que pour des sites d'autorité publique ou de santé, le balisage reste valide | `DiscussionForumPosting` ajouté sans test dans l'outil officiel (non disponible hors ligne) |

À faire en ligne : passer 3 URL types (accueil, un article, une discussion) dans le **Test des résultats enrichis** de Google et le **validateur Schema.org**.

## 7. Performance et Core Web Vitals (laboratoire)

Conditions : Chrome mobile 390×844, processeur ×4 plus lent, 4G simulée. Deuxième passage conservé (le premier chauffe le serveur). **Ce ne sont pas des Core Web Vitals de terrain.**
Seuils Google : LCP bon ≤ 2,5 s ; CLS bon ≤ 0,1 ; TBT (indicateur de laboratoire proche d'INP) bon ≤ 200 ms.

| Page | FCP | LCP | CLS | TBT | Poids transféré | Requêtes |
|---|---|---|---|---|---|---|
| ganho.app `/` | 1,7 s | 1,7 s (H1) | 0 | 0 ms | 252 Ko | 15 |
| ganho.app `/article/disons-non` | 1,8 s | 1,8 s | 0 | 0 ms | 244 Ko | 15 |
| ganho.app `/knowledge` (après correction) | 1,8 s | **1,8 s** (avant : 3,0 s) | 0 | 0 ms | 417 Ko | 17 |
| ganho.app `/faq` | 1,7 s | 1,7 s | 0 | 0 ms | 214 Ko | 13 |
| ganho.app `/guest/openticket` | 1,7 s | 1,7 s | 0 | **215 ms** | **528 Ko** (JS 367 Ko) | 19 |
| ganho.app `/assistant` | 1,9 s | 1,9 s | 0 | 49 ms | 215 Ko | 14 |
| forum `/guest` | 1,1 s | 2,6 s (texte du bandeau cookies) | 0 | 194 ms | 395 Ko | 14 |
| forum `/about_us` | **10,9 s** | 10,9 s | 0 | **1 542 ms** | 1 601 Ko | 28 |
| forum `/thread/908` | **10,3 s** | 10,3 s | **0,49** | **894 ms** | 1 631 Ko | 29 |
| forum `/terms_of_use` | **10,5 s** | 10,5 s | 0 | **1 306 ms** | 1 601 Ko | 28 |

**ganho.app** : LCP et CLS bons partout, JS total faible (≈ 80 Ko, dont `gn-assistant.js` 46 Ko), police unique de 37 Ko préchargée. Deux points : `/guest/openticket` charge **Summernote (196 Ko) et jQuery (87 Ko)** pour un simple champ de texte ; `gn-spa.js` est un script sans `defer` dans le `<head>` (voulu pour intercepter les clics tôt, impact mesuré faible).

**Forum** : les pages publiques chargent toute l'application : `master.styles.css` **504 Ko** (35 Ko compressé), `afterglow.min.js` **419 Ko** (lecteur vidéo, 100 Ko compressé) et 17 fichiers JS sur chaque page, y compris les pages légales. La compression Apache (déjà configurée) réduirait le transfert d'un facteur 4 environ, mais le temps de calcul du navigateur (TBT 0,9 à 1,5 s) resterait élevé. Le **CLS 0,49** sur une discussion est mauvais ; la cause exacte n'a pas été isolée.

Rappel : `Set-Cookie` et `Cache-Control: no-cache` sont posés sur toutes les pages publiques des deux sites (cookie de session). Cela empêche toute mise en cache partagée ; sans CDN, l'impact est faible.

## 7 bis. Performance du forum après corrections

Chiffres mesurés **sans compression** (`php -S`), conditions du §7. Avant → après :

| Page | Poids | FCP | CLS |
|---|---|---|---|
| `/about_us`, `/faqs`, pages légales | 1 601 → 755 Ko (÷ 2,1) | 10-11 s → 6 s | 0 → 0 |
| `/thread/908` | 1 631 → 869 Ko | 9,5 → 6,1 s | **0,49 → 0** |
| `/thread/904` | 1 607 → 761 Ko | 9,6 → 5,8 s | 0 → 0 |
| `/BESYP` (profil) | 1 938 → 1 175 Ko | 12,8 → 7,8 s | 0 → 0 |

Mesure complémentaire **avec la compression d'un vrai Apache** (la plus proche de la production), après corrections :

| Page | Transféré | FCP | LCP | CLS | TBT |
|---|---|---|---|---|---|
| `/guest` | 207 Ko | 1,0 s | 1,7 s | 0 | 692 ms |
| `/about_us` | 212 Ko | 1,8 s | 2,1 s | 0 | 536 ms |
| `/thread/904` | 217 Ko | 2,1 s | 2,4 s | 0 | 447 ms |
| `/thread/908` | 242 Ko | 2,4 s | **2,8 s** | 0 | **1 366 ms** |
| `/BESYP` | 558 Ko | 2,3 s | 2,8 s | 0 | **1 634 ms** |

Ce qui a été fait : lecteur vidéo `afterglow` (419 Ko) chargé seulement quand une vidéo apparaît ; feuille CSS **allégée pour les visiteurs** (`visiteurs.purged.css`, 306 Ko contre 645 Ko, comparaison de styles calculés sur 15 pages en clair et 4 en nuit : 0 différence) ; `fancybox` chargé à la première interaction avec une image ; `audio-recorder` retiré pour les visiteurs ; **cause du CLS 0,49 trouvée** (image de publication de 2,4 Mo sans dimensions : `width`, `height` et un `src` transparent ajoutés). Détails et régénération : `forumganho/docs/PERFORMANCE_FORUM.md`.

**Ce qui n'est PAS résolu** : le **TBT** (temps de blocage du fil principal) reste élevé (450 à 1 650 ms en émulation processeur ×4, très variable d'un passage à l'autre) : jQuery, Vue, Bootstrap et les scripts maison s'exécutent encore sur les pages publiques. `/thread/908` est à la limite sur le LCP (2,8 s contre 2,5 s attendus). Les membres connectés gardent les feuilles complètes (le CLS cumulé d'une session membre n'a pas été comparé avant/après).

Pistes restantes : ne pas initialiser Vue ni les plugins de composition sur les pages publiques statiques ; remplacer jQuery sur ces pages ; limiter le nombre de publications chargées sur un profil (`/BESYP` : 4 300 éléments de page).

## 8. Technique et sécurité de base

| Contrôle | Constat |
|---|---|
| Viewport, charset, favicon, manifeste, `theme-color` | Présents sur toutes les pages publiques |
| Compression | ganho.app : Brotli ou gzip configurés (`public/.htaccess`) ; forum : deflate configuré ; à confirmer en ligne |
| Cache des fichiers statiques | `ExpiresByType` configuré (images 6 mois) ; versions par hash de fichier côté ganhovbg |
| En-têtes de sécurité | **Aucun** `Strict-Transport-Security`, `X-Content-Type-Options`, `X-Frame-Options`, `Content-Security-Policy` ni `Referrer-Policy` observé en local, sur les deux sites. À vérifier en production, sinon à ajouter. |
| HTTPS | Redirection du forum ajoutée dans `.htaccess` (non testée sur Apache) ; ganhovbg : middleware HTTPS |
| Pages d'erreur | ganho.app : pages françaises pour 400 à 504 ; forum : `erreur.html` et vrais codes sur `/404` et `/500` |

## 9. Plan d'action priorisé

### Priorité 1 (avant ou juste après la mise en ligne)

*État au 14 novembre 2026 (voir §2 bis) :*
1. **À FAIRE PAR VOUS : envoyer les corrections de cette séance** (voir §2 et §2 bis) et vider les caches (`view:clear`, `config:cache`).
2. ✅ FAIT. **Forum : renvoyer un vrai 404** pour les URL inconnues (aujourd'hui 302 vers la connexion) : effort moyen, dans le routage des profils (`/<pseudo>`).
3. ⚠️ PARTIEL (§7 bis : poids ÷ 2, CLS corrigé, TBT non résolu). **Forum : alléger les pages publiques** : ne charger `afterglow.min.js` que sur les pages avec vidéo, retirer le CSS et le JS non utilisés des pages légales/publiques (`master.styles.css` 504 Ko), regrouper et différer les scripts. Effort moyen à élevé ; gain majeur.
4. ✅ FAIT (à retester en ligne). **Forum : supprimer le `robots.txt` statique** et tester `https://forum.ganho.app/robots.txt` en ligne (doit contenir `Sitemap:`).
5. ✅ CODE FAIT, **À LANCER EN PRODUCTION** (`index_privacy_optin.php`). **Décision de vie privée** : passer la visibilité dans la recherche à « non » par défaut pour les nouveaux comptes, ou l'assumer par écrit.

### Priorité 2
6. ✅ FAIT. **Forum : décider d'indexer les contenus éducatifs et les BD** (publics, aujourd'hui `noindex,nofollow`) : passer en `index,follow`, les ajouter au sitemap, ajouter un balisage (`Article`, ou `ComicStory`/`CreativeWork` pour les BD).
7. ✅ FAIT. **ganhovbg : remplacer Summernote sur le formulaire d'alerte public** par un champ simple (−367 Ko de JS, TBT 215 ms → ≈ 0).
8. ✅ FAIT (CLS 0). **Forum : corriger le CLS de 0,49** sur les discussions (réserver la place des images/médias avec `width`/`height`, vérifier le menu déroulant qui apparaît).
9. ✅ AJOUTÉS (à confirmer en ligne). **En-têtes de sécurité** (HSTS, `nosniff`, `X-Frame-Options`, `Referrer-Policy`, CSP) dans les `.htaccess` ; vérifier ce que l'hébergeur ajoute déjà.
10. ✅ FAIT (textes à valider). **Contenu mince** : ajouter un texte d'introduction (150 à 300 mots) aux 4 pages de catégories de violences, compléter `/faq/5`, `/faq/6` et `/contact-us`.

### Priorité 3
11. ✅ FAIT pour la limite de 60 caractères ; reste à renommer l'article « L'harcèlement Sexuel ». Raccourcir les 4 titles de 61 caractères ; renommer l'article « L'harcèlement Sexuel » en titre descriptif.
12. ⏳ OUVERT (sauf `lastmod`, fait). Forum : vraies balises `<h1>` sur les profils et discussions ; `summary_large_image` et une image 1200×630 dédiée ; `lastmod` manquants du sitemap ganho.app.
13. ⏳ OUVERT. `Organization.sameAs` quand les profils sociaux existent ; retirer le `BreadcrumbList` à un seul élément de l'accueil.
14. ⏳ OUVERT. Rendre `robots.txt` et `sitemap.xml` de ganho.app cacheables (sans cookie de session).

## 10. À faire une fois en ligne (non faisable ici)

1. Ajouter les deux sites à **Google Search Console** et **Bing Webmaster Tools** ; soumettre les deux sitemaps ; suivre « Pages » (indexation), « Expérience » (Core Web Vitals de terrain) et « Résultats enrichis ».
2. Mesurer avec **PageSpeed Insights** (accueil, un article, une discussion) en mobile et en ordinateur ; noter LCP, INP, CLS de terrain après quelques semaines.
3. Tester **Test des résultats enrichis** et **validateur Schema.org** sur 3 URL types ; tester les aperçus de partage (Facebook, LinkedIn, X, WhatsApp).
4. Vérifier les redirections : `http` → `https`, `www` → domaine sans `www`, anciennes URL `/customer/*` et `/admin/*` en 301.
5. Contrôler les en-têtes réels (`curl -I`) : compression, cache, sécurité.

## Annexe : fichiers modifiés pendant l'audit

`ganhovbg` : `resources/views/layouts/gnlean.blade.php`, `resources/views/includes/seo.blade.php`, `resources/views/includes/gc/card.blade.php`, une quinzaine de vues contenant `url('customer/login')` / `url('customer/register')`, six vues `user/auth/*` (fils d'Ariane).
`forumganho` : `themes/default/apps/main/seo.phtml`, `sitemap.php`, `robots.php`, `.htaccess`, `core/components/tools.php` (`cl_not_found`, `cl_media_dims_attr`), gestionnaires `profile`, `thread`, `comics`, `educational`, `stat_pages`, 5 chemins de création de compte (`index_privacy`), `themes/default/apps/main/{content.phtml,scripts/app_master_script.phtml}`, `thread/thread.phtml`, `timeline/post.phtml`, `statics/css/visiteurs.purged.css`, `core/seeders/index_privacy_optin.php`.
`ganhovbg` (2e passe) : `public/index.php` n'est pas concerné ; `.htaccess` racine et `public/.htaccess` (en-têtes), formulaire d'alerte public (`guestticket/*`, `includes/gn/alert*.blade.php`), `app/Support/TicketText.php`, `GuestticketController`, `CategoryIntroSeeder`, `categorypage.blade.php`, `contactus.blade.php`, `SeoController` (lastmod).
Scripts d'audit (hors dépôt) : crawler `crawl.py`, analyse `an.py`, mesure `perf.py` dans le dossier de travail temporaire de la séance.

## À faire valider (textes proposés, 14 nov. 2026)

- Introductions des pages `/category/{slug}` (violence verbale, sexuelle, psychologique, rejet familial, Pool Com, santé mentale, santé et droits) : textes PROPOSÉS dans `database/seeders/CategoryIntroSeeder.php` (stockés dans `settings`, clés `category_intro:<slug>`, modifiables ensuite en base). Les contenus de santé et de droit sont des repères généraux, sans promesse ferme : à faire relire par le client et par un professionnel (santé, juridique). En production : `php artisan db:seed --class=CategoryIntroSeeder --force`.
- Texte « Quand nous écrire ? » de `/contact-us` (délai « dès que possible », renvoi vers l'alerte et l'assistant) : proposition à valider.
- `/faq/5` et `/faq/6` : contenus issus de la base, non modifiés ; la page affiche déjà les autres thèmes de la FAQ.
