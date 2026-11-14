# Plan : application mobile GANXÔ Alerte VBG (Flutter)

> **Statut (14 novembre 2026) : document de conception. Aucun code mobile n'existe.** Tout ce qui est écrit au futur ou
> marqué « proposition » est à valider par le client avant le premier lot. Ce qui est décrit comme « existant » a été
> constaté dans `ganhovbg/` le jour de la rédaction.
>
> Ce plan suit la méthode et le plan du projet mobile de Xomiton (`xomiton-final/mobile`, `prototype_mobile`,
> `docs/PLAN_MOBILE_APP_UNIQUE.md`, `docs/BUILD_ANDROID_WINDOWS.md`), adaptés à GANXÔ. Les chiffres de Xomiton
> (nombre de tests, durées) ne sont **pas** repris : ils ne décrivent pas ce projet.

Documents du dossier : [`README.md`](README.md) · [`API_MOBILE.md`](API_MOBILE.md) (contrat serveur proposé) ·
[`BUILD_ANDROID_WINDOWS.md`](BUILD_ANDROID_WINDOWS.md) · [`CHECKLIST_RECETTE_MOBILE.md`](CHECKLIST_RECETTE_MOBILE.md).

**Ordre de réalisation (celui de Xomiton) : 1. le client valide des maquettes mobiles ; 2. seulement ensuite, le code
Flutter. Le code serveur indépendant des écrans (API mobile, jetons, appareils) peut démarrer avant.**

---

## 1. Résumé en dix lignes

1. **Une seule application** (Android d'abord, iOS ensuite), qui reprend les parcours déjà livrés sur ganho.app : alerte VBG,
   assistant, actualités, FAQ, espace client, et un espace **Équipe** pour le personnel qui traite les alertes.
2. **Aucun compte n'est nécessaire pour demander de l'aide** : créer une alerte (avec ou sans vérification par code),
   parler à l'assistant, lire les contenus et joindre un contact d'urgence restent ouverts à tous.
3. Les espaces affichés dépendent de ce que **le serveur déclare** (`GET /api/mobile/v1/me`) : le client affiche, le serveur
   autorise. Toute route est revérifiée côté Laravel (rôles et permissions `spatie/laravel-permission` déjà en place).
4. L'application **n'ajoute aucune règle métier** : elle réutilise les contrôleurs et services existants
   (`GuestticketController`, `AssistantEngine`, `AlertReportService`…) derrière un nouveau groupe `api/mobile/v1/*`.
5. Aujourd'hui, ganhovbg n'a **aucune API publique pour mobile** : seule existe l'API serveur à serveur du forum
   (`/api/forum/*`, signature HMAC). Le groupe `api/mobile/v1/*` est donc à créer (lot MOB-S1, le plus gros risque de calendrier côté serveur).
6. **Confidentialité d'abord** : code PIN, mode discret, captures d'écran interdites sur les écrans sensibles, effacement
   local à la déconnexion et à distance, notifications sans contenu (« Nouvelle notification », jamais le texte d'une alerte).
7. **Hors ligne utile mais prudent** : l'alerte rédigée sans réseau part au retour de la connexion (file d'envoi, rejeu sans
   doublon) ; l'assistant, la FAQ et les contacts d'urgence restent lisibles. L'espace Équipe ne conserve **aucune donnée de
   dossier** hors ligne en v1.
8. **Design system Ganho** (jetons `--gn-*`, Montserrat, mode nuit, WCAG AA, cibles de 48 dp) et textes **en français uniquement**.
9. Règles de rédaction du client : ne jamais écrire « GANXÔ n'est pas un service d'urgence » ; écrire « LGBTQ », pas « LGBTQIA+ ».
   Le bouton « Quitter vite » a été retiré du web à la demande du client : il n'est pas repris dans l'application (§8).
10. Décisions à trancher avant le code : §13.

---

## 2. Exigences

### 2.1 Fonctionnelles

| Réf. | Exigence | Origine dans ganho.app |
|---|---|---|
| F-01 | Créer une alerte VBG : catégorie, objet, description, pièces jointes, avec ou sans compte | `GuestticketController` (`guest/openticket`, `openticketnootp`), `AssistantEngine::alertConfig()` |
| F-02 | Vérifier une adresse e-mail par code (OTP) quand le réglage l'exige ; alerte anonyme possible (adresse technique `*.invalid`) | `guest/emailsvalidate`, `guest/verifyotp`, écouteur `SkipInvalidRecipients` |
| F-03 | Suivre une alerte, répondre, joindre un fichier, clôturer, évaluer | Espace client `/espace-client/alertes/*` |
| F-04 | Assistant à base de connaissances, avec création d'alerte en conversation | `POST /assistant/ask`, `GET /assistant/alert-config` |
| F-05 | Lire les actualités (chapô, sommaire, FAQ), la FAQ, les pages légales | `/knowledge`, `/faq`, pages `pages` |
| F-06 | Contacts d'urgence et consultation psychologue (WhatsApp) | Section « Consultation » de l'accueil |
| F-07 | Bouton SOS : maintien 2 s puis préremplissage d'une alerte avec la position (au choix) ; il ne contacte aucun service d'urgence | SOS du site |
| F-08 | Notifications : réponse à mon alerte, changement de statut | Notifications de l'espace client |
| F-09 | Espace Équipe : liste des alertes qui me concernent, détail, accusé, prise en charge, réponse, note interne, statut, réattribution (selon les droits) | Administration `/administration/*` |
| F-10 | Compte : connexion, inscription, mot de passe oublié, profil, préférences, suppression locale des données | `/espace-client/*` |
| F-11 | Mode nuit, taille de texte du système respectée | Design system |

### 2.2 Non fonctionnelles

| Réf. | Exigence | Cible |
|---|---|---|
| NF-01 | Accessibilité | WCAG 2.2 AA, TalkBack et VoiceOver, cibles ≥ 48 dp, contrastes AA clair et nuit, `Semantics` sur toute icône |
| NF-02 | Vie privée | Données minimales, rien de sensible dans les journaux, les notifications ni les captures du sélecteur d'applications |
| NF-03 | Réseau instable | Délais explicites, reprise, file d'envoi idempotente, messages d'erreur clairs par statut (401, 403, 404, 413, 422, 429, 503) |
| NF-04 | Performance | Démarrage à froid < 3 s sur un téléphone d'entrée de gamme, APK/AAB aussi léger que possible (budget à fixer au lot MOB-M1) |
| NF-05 | Sécurité | HTTPS seul, jetons à durée limitée, appareil enregistré et révocable, limitation de débit, journal d'audit côté serveur |
| NF-06 | Maintenabilité | Même vocabulaire que le web ; textes dans un fichier ARB unique ; aucune règle métier dans le client |

---

## 3. Existant constaté dans `ganhovbg`

| Sujet | État |
|---|---|
| Application | Laravel 8, PHP 7.3 à 8.1, MySQL, modules `nwidart/laravel-modules` (Uhelpupdate) |
| URL | Public `/`, espace client `/espace-client/*`, administration `/administration/*` (anciennes URL redirigées) |
| Rôles | Spatie : `superadmin` et rôles de prestataires (Agent de Documentation, Agent de Santé, Community Manager, Investigateur, Juriste, Médecin, Psychologue) ; client = compte utilisateur sans rôle d'équipe |
| Alertes | Table `tickets` ; statuts `New`, `Inprogress`, `On-Hold`, `Closed`, `Re-Open` (libellés français via `gn_statut()`), priorités, catégories, commentaires, pièces jointes |
| Pièces jointes | `ImageController` : accès réservé au propriétaire, au personnel habilité ou à l'invité vérifié pour cette alerte |
| Assistant | `AssistantEngine`, base `assistant_kb`, journaux anonymisés (jamais le texte saisi) |
| API | `routes/api.php` : uniquement `/api/forum/*` (middleware `forum.sig`, HMAC-SHA256) ; garde `api` de `config/auth.php` en pilote `token` **non utilisé** |
| Limitation de débit | `throttle:30,1` sur l'assistant ; 6/min sur les exports ; 60/min sur la recherche admin |
| E-mails | Gabarit unique, transport lu dans la table `settings` ; jamais d'envoi vers `*.invalid` |
| PWA web | `public/sw.js`, page `/offline`, installable (ce n'est pas une application mobile native) |
| Design | Jetons `--gn-*` (`design-system/tokens`), Montserrat, mode nuit, charte `Charte-graphique-Ganho-v1.0.pdf` |

**Conséquence** : la couche d'accès mobile (authentification par jeton, appareils, API) n'existe pas. C'est le premier chantier.

---

## 4. Architecture

### 4.1 Schéma d'ensemble

```
Application Flutter (Android, puis iOS)
 ├─ features/…  écrans par fonctionnalité (Riverpod, go_router)
 ├─ core/network   Dio, intercepteurs : jeton, X-Device-Id, Idempotency-Key, erreurs
 ├─ core/storage   coffres chiffrés (Hive + flutter_secure_storage)
 ├─ core/outbox    file d'envoi des alertes et réponses
 └─ core/security  PIN, verrouillage, FLAG_SECURE, effacement
        │ HTTPS
        ▼
https://ganho.app/api/mobile/v1/*   (à créer : middleware auth jeton + appareil)
        │ réutilise
        ▼
GuestticketController · AssistantEngine · services d'alertes · Spatie (rôles et permissions)
```

### 4.2 Choix techniques (proposition, alignés sur Xomiton)

| Couche | Choix | Raison |
|---|---|---|
| Framework | Flutter stable (Dart ^3), un seul code Android et iOS | Une équipe, deux stores |
| État | `flutter_riverpod` | Testable, déjà éprouvé sur Xomiton |
| Routage | `go_router` avec gardes d'espace | Gardes déclaratives, liens profonds |
| Réseau | `dio` | Intercepteurs, annulation, multipart |
| Stockage | `hive` (données) + `flutter_secure_storage` (clés, jeton, PIN) | Chiffrement par clé en coffre système |
| Réseau d'état | `connectivity_plus` | Reprise de la file d'envoi |
| Localisation | `geolocator` | Position du SOS, uniquement avec accord |
| Fichiers | `file_picker` | Pièces jointes d'alerte |
| Notifications | `firebase_messaging` + `flutter_local_notifications` | Push FCM « données seules » |
| Liens | `url_launcher` | Téléphone, WhatsApp, pages légales (https seulement) |
| Textes | `flutter_localizations` + `lib/l10n/app_fr.arb` | Français uniquement |

### 4.3 Arborescence proposée

```
mobile/                      (nouveau dossier à la racine de C:\Projects\ganho, jamais dans ganhovbg/public)
├── lib/
│   ├── app/                 app.dart, router.dart, thème Ganho, shells (visiteur, client, équipe)
│   ├── core/                network, storage, outbox, security, push, l10n, ui, maintenance
│   ├── features/
│   │   ├── alert/           créer (assistant 3 étapes), brouillon, suivi, conversation
│   │   ├── assistant/       chat, création d'alerte en conversation
│   │   ├── content/         actualités, FAQ, pages légales, urgences, consultation
│   │   ├── auth/            connexion, inscription, mot de passe oublié, profil
│   │   └── team/            alertes de l'équipe, détail, actions
│   └── l10n/app_fr.arb
├── android/  ios/  test/  tool/
└── pubspec.yaml
```

### 4.4 Routage et espaces

| Espace | Qui | Entrée |
|---|---|---|
| **Visiteur** | Tout le monde, sans compte | Accueil, alerte, assistant, actualités, FAQ, urgences |
| **Client** | Compte `customer` | Tableau de bord, mes alertes (en attente, suivies, résolues), notifications, profil |
| **Équipe** | Compte avec rôle d'équipe et permission `Mobile Access` (nouvelle) | Alertes qui me concernent, détail, actions |

Un compte d'équipe est aussi client. Le sélecteur d'espace n'apparaît que si le serveur en déclare plusieurs
(`spaces[]` dans `GET me`). Un 403 relit `GET me` et ferme l'espace concerné.

---

## 5. Authentification et appareils (à créer côté serveur)

- **Jeton** : Laravel Sanctum (compatible Laravel 8) ou, à défaut, jetons maison hachés dans une table dédiée. Proposition :
  Sanctum, durée de vie courte (client 30 jours glissants, équipe 7 jours glissants).
- **Appareil** : `POST me/devices` enregistre un UUID d'appareil ; `X-Device-Id` sur chaque requête ; révocation par l'équipe
  ou par la personne ; un 403 `device_revoked` déclenche l'effacement local complet.
- **Step-up** (équipe) : les actions à fort impact (réattribution, clôture massive) redemandent le mot de passe ;
  `POST team/step-up` renvoie un jeton à usage unique envoyé en `X-Step-Up-Token`.
- **Idempotence** : `Idempotency-Key` sur toute écriture (création d'alerte, réponse, pièce jointe) ; le serveur rejoue la même réponse.
- **Alerte invitée** : jeton de suivi limité à CETTE alerte (comme l'« invité vérifié » du web), sans compte.

---

## 6. Fonctionnalités par module

### 6.1 Alerte VBG
Assistant en trois étapes (catégorie ; message et pièces jointes ; contact facultatif et consentement), brouillon local
sans pièce jointe, mêmes bornes que le serveur (`subject_max`, `desc_min`, `desc_max` lues dans `alert-config`, jamais codées
en dur). Le 422 reste juge ; chaque erreur est rattachée à son champ. Alerte anonyme : adresse technique `alerte-<hex>@anonyme.invalid`.

### 6.2 Assistant
Même moteur que le web (`POST /assistant/ask`), mêmes limites. Création d'alerte en conversation via `alert-config`.
Aucun texte saisi n'est conservé dans les journaux.

### 6.3 Suivi des alertes
Listes par état, détail avec frise, réponses, pièces jointes servies par une URL signée courte (jamais l'URL publique), clôture, évaluation.

### 6.4 Contenus
Actualités (méta, FAQ), FAQ, pages légales, contacts d'urgence, consultation psychologue. Lecture seule, copie locale de la dernière réponse pour la lecture hors ligne.

### 6.5 SOS
Maintien 2 secondes, annulation possible, puis préremplissage de l'alerte ; position partagée **uniquement avec l'accord** de la personne.
Le texte à l'écran dit ce que le bouton fait et ne fait pas (il ne contacte aucun service d'urgence).

### 6.6 Espace Équipe
Liste filtrée sur le périmètre du rôle (superadmin : tout ; prestataire : dossiers affectés, auto-attribués ou de ses groupes,
règle identique à `AlertExportController` et à la recherche d'administration), détail, accusé, prise en charge, réponse, note
interne, statut, réattribution. Position éventuelle **arrondie**. Aucune écriture hors ligne. Les rôles ne s'attribuent
jamais depuis le mobile.

---

## 7. Hors ligne et synchronisation

| Donnée | Hors ligne | Règle |
|---|---|---|
| Alerte rédigée | Mise en file | Rejeu avec la même `Idempotency-Key`, sans doublon ; **sans pièce jointe** hors ligne (avec pièce jointe : réseau obligatoire, brouillon conservé) |
| Réponse à une alerte | Mise en file | Idem |
| Actualités, FAQ, urgences, assistant (réponses connues) | Lecture de la dernière copie | Copie datée, bandeau « Hors ligne », purgée à la déconnexion |
| Dossiers de l'équipe | **Rien** en v1 | Bandeau « appelez directement la personne », boutons grisés, aucune requête d'écriture |

Reprise : à l'ouverture, au retour du réseau et sur « Synchroniser maintenant ». Après échec : attente exponentielle avec gigue (5 s à 5 min). Pas de tâche d'arrière-plan en v1.

---

## 8. Sécurité et vie privée

| Mesure | Détail |
|---|---|
| Code PIN | 4 chiffres, local, PBKDF2-HMAC-SHA256 avec sel, comparaison à temps constant, 5 essais puis fermeture de session. Seul l'état « PIN actif » est envoyé au serveur. Alerte, SOS et urgences restent accessibles sans code. **Obligatoire pour l'espace Équipe** |
| Captures d'écran | `FLAG_SECURE` sur messages, suivi, alerte et tout l'espace Équipe ; recouvrement opaque dans le sélecteur d'applications (iOS) |
| Mode discret | Nom et icône neutres au choix de la personne ; à proposer **désactivé par défaut** pour les clients, **activé par défaut** pour l'Équipe. À valider (§13, D-MOB-G7) |
| Effacement | Déconnexion, 401, 5 codes faux, `device_revoked`, ordre distant (`wipe`), expiration locale après 14 jours sans contact (Équipe). Le téléphone éteint ne reçoit pas l'ordre : l'expiration locale est le filet |
| Réseau | HTTPS seul (`network_security_config`), HTTP uniquement vers l'émulateur en debug |
| Notifications | Message FCM « données seules » `{type, id}` ; texte choisi par l'application ; **jamais** le texte, le lieu ni le motif d'une alerte ; titre et corps neutres si les aperçus sont masqués |
| Journaux | Aucun contenu saisi ; identifiants techniques seulement |
| Permissions | Localisation précise (SOS, à la demande), notifications, fichiers choisis par la personne ; tout le reste retiré explicitement |
| « Quitter vite » | Retiré du web à la demande du client ; non repris. Le mode discret, le PIN et le verrouillage répondent au même besoin de discrétion |

---

## 9. Notifications push

Firebase : aucun secret dans le dépôt. Sans configuration, l'application démarre avec un service « sans effet » (aucune demande d'autorisation).
Avec `google-services.json` (ignoré par git), le service FCM est choisi au démarrage.

| `type` | Pour qui | Ouvre |
|---|---|---|
| `alert_reply` | Client | Détail de l'alerte |
| `alert_status` | Client | Détail de l'alerte |
| `team_assigned` | Équipe | Détail du dossier, après le PIN |
| `team_new_alert` | Équipe | Liste des alertes |

Canaux Android : « Alertes » (importance haute) et « Informations ». Le texte est composé sur l'appareil. Le serveur ne met **aucun bloc `notification`**.

---

## 10. Design, accessibilité et textes

- Jetons Ganho repris de `design-system/tokens` (couleurs, espacements, rayons) ; Montserrat ; spectre arc-en-ciel utilisé avec parcimonie (il décore, il ne porte jamais seul une information).
- Mode clair et nuit, taille de texte du système respectée (aucun texte de taille fixe), cibles ≥ 48 dp, focus visible, ordre de lecture logique, annonces `liveRegion` pour les erreurs et les statuts.
- Écrans d'alerte et de SOS **sobres** : peu d'éléments, aucun décor, un seul appel à l'action.
- Textes en français dans `app_fr.arb` ; tournures du client respectées (voir §1, point 9) ; langage simple, sans jargon.

---

## 11. Plan par lots (proposition)

Convention : **MOB-S** = serveur Laravel, **MOB-M** = mobile Flutter. Les tailles sont des **estimations indicatives à confirmer** (S ≈ 1 à 3 j, M ≈ 3 à 6 j, L ≈ 7 j et plus, par développeur·se connaissant le dépôt) ; elles ne reposent sur aucune mesure faite sur ce projet.

| Lot | Contenu | Taille | Dépend de |
|---|---|---|---|
| MOB-0 | Maquettes mobiles (HTML comme `prototype_mobile` de Xomiton) : visiteur, client, équipe, SOS, PIN ; validation du client | M | — |
| MOB-S1 | **Socle API** : Sanctum, `api/mobile/v1`, `me`, appareils, limiteurs, journal d'audit, tests 401/403 | L | — |
| MOB-S2 | Alertes : création (avec et sans compte, OTP), suivi, réponses, pièces jointes signées, idempotence | L | MOB-S1 |
| MOB-S3 | Contenus et assistant : actualités, FAQ, pages, urgences, `assistant/ask`, `alert-config` | M | MOB-S1 |
| MOB-S4 | Équipe : permission `Mobile Access`, liste, détail, actions, `step-up`, périmètre par rôle | L | MOB-S1 |
| MOB-S5 | Push : tables d'abonnements, envoi FCM « données seules », types ci-dessus | M | MOB-S1 |
| MOB-M1 | Socle Flutter : thème Ganho, routeur, shells, réseau, coffres, textes, budget de taille | M | MOB-0 |
| MOB-M2 | Sécurité : PIN, verrouillage, `FLAG_SECURE`, effacement, mode discret | M | MOB-M1 |
| MOB-M3 | Alerte : assistant 3 étapes, brouillon, file d'envoi, SOS | L | MOB-M1, MOB-S2 |
| MOB-M4 | Assistant, actualités, FAQ, urgences, lecture hors ligne | M | MOB-M1, MOB-S3 |
| MOB-M5 | Compte et suivi : connexion, inscription, profil, mes alertes, conversation, notifications | L | MOB-M3, MOB-S2 |
| MOB-M6 | Espace Équipe | L | MOB-M2, MOB-S4 |
| MOB-M7 | Push (Android puis iOS) | M | MOB-S5 |
| MOB-Q | Tests d'intégration contre une API simulée, recette sur appareil, accessibilité | L | tous |
| MOB-R | Livraison : signature, AAB, fiche Play, Data safety, accès de test, iOS (compte Apple) | M | MOB-Q |

Chemin critique : MOB-0 → MOB-M1 → MOB-M3 côté mobile ; MOB-S1 → MOB-S2 côté serveur. **Une seule version publiée** : les espaces sont livrés ensemble,
un interrupteur serveur peut couper l'espace Équipe en cas de problème.

---

## 12. Tests et recette

- Serveur : tests PHPUnit sur chaque route `api/mobile/v1/*` (200, 401, 403, 404, 422, 429), périmètre par rôle, idempotence, révocation d'appareil.
- Mobile : tests unitaires (validation identique au serveur, file d'envoi, PIN), tests d'écrans (`Semantics`, grande police, thème sombre), et scénarios d'intégration contre un serveur simulé à état unique (coupure réseau, maintenance, droit retiré, appareil révoqué, rejeu).
- Recette sur appareil réel : [`CHECKLIST_RECETTE_MOBILE.md`](CHECKLIST_RECETTE_MOBILE.md) (MOB-G-01 et suivants). TalkBack, VoiceOver, `FLAG_SECURE`, push et position **ne se vérifient que sur appareil**.

---

## 13. Décisions à valider avant le code

| Réf. | Question | Recommandation |
|---|---|---|
| D-MOB-G1 | Une application unique (visiteur, client, équipe) ou deux ? | Une seule, comme Xomiton (une fiche, une mise à jour) |
| D-MOB-G2 | L'espace Équipe est-il dans la v1 ? | Oui si le personnel traite déjà les alertes sur téléphone ; sinon v2 |
| D-MOB-G3 | Qui reçoit les push d'équipe ? | Le personnel affecté au dossier, plus les superadmins de garde |
| D-MOB-G4 | Position du SOS visible par l'équipe | Arrondie (~1 km), jamais exacte |
| D-MOB-G5 | Âge minimal et tranche Play | **À fixer par le client avec son conseil juridique** : le web et le forum indiquent 15 ans |
| D-MOB-G6 | Comptes Google Play et Apple Developer : au nom de quelle organisation ? | Association BESYP ; prévoir le dossier d'organisation Apple (délais de vérification) |
| D-MOB-G7 | Mode discret (nom et icône neutres) | Proposé, avec explication honnête de ses limites ; à confronter aux règles Play et App Store |
| D-MOB-G8 | Identifiant d'application | Proposition : `app.ganho.alerte` (à confirmer, non modifiable après publication) |
| D-MOB-G9 | Un client peut-il supprimer son compte depuis l'application ? | Oui (exigé par les deux stores) ; à relier à la suppression côté serveur |
| D-MOB-G10 | Lecture hors ligne des actualités | Oui, copie datée de 30 jours maximum |

---

## 14. Risques

| Risque | Parade |
|---|---|
| Aucune API mobile aujourd'hui | MOB-S1 en premier, tests 401/403 dès le départ |
| Données très sensibles (orientation, violences) | Données minimales, rien dans les notifications ni les journaux, effacement distant |
| Refus de fiche Play ou App Store (mode discret, données sensibles) | Fiche honnête, Data safety exacte, accès de test fourni, conseil juridique |
| Compte Apple d'organisation (délai de vérification) | Lancer la démarche dès la validation du plan |
| Hébergement mutualisé (limites de connexions MySQL, pas de file d'attente) | Pas de tâche d'arrière-plan lourde ; envois FCM en lots courts ; limiteurs |
| TalkBack, VoiceOver, push et position invérifiables hors appareil | Recette sur appareil obligatoire avant la publication |
| `FLAG_SECURE` sans équivalent iOS | Recouvrement opaque en quittant le premier plan, limite connue à documenter |

---

## 15. Sources

Projet : `ganhovbg/routes/web.php`, `routes/api.php`, `app/Http/Controllers/{GuestticketController,AssistantController,Api/ForumAlertController}.php`,
`app/Services/AssistantEngine.php`, `config/auth.php`, `CLAUDE.md` (règles de rédaction et décisions du client).
Référence Xomiton : `mobile/README.md`, `prototype_mobile/README.md`, `docs/PLAN_MOBILE_APP_UNIQUE.md`, `docs/BUILD_ANDROID_WINDOWS.md`.
