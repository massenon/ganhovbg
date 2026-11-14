# API mobile GANXÔ Alerte VBG : contrat proposé (v1)

> **Statut : proposition, rien n'est implémenté.** Aujourd'hui, `routes/api.php` ne contient que `/api/forum/*`
> (serveur à serveur, signature HMAC). Ce document décrit le groupe `api/mobile/v1/*` à créer (lots MOB-S1 à MOB-S5 de
> [`PLAN_MOBILE_APP.md`](PLAN_MOBILE_APP.md)). Les noms de champs sont des propositions, à figer avant le premier code mobile.
> Chaque route s'appuie sur du code **déjà existant** (colonne « Réutilise »).

Base : `https://ganho.app/api/mobile/v1` · JSON UTF-8 · français · HTTPS seul.

## 1. Conventions

| Sujet | Règle |
|---|---|
| Authentification | `Authorization: Bearer <jeton>` (Sanctum). Les routes « ouvertes » n'en ont pas besoin. |
| Appareil | `X-Device-Id: <uuid>` sur toute requête authentifiée (créé par `POST me/devices`). |
| Idempotence | `Idempotency-Key: <uuid>` obligatoire sur les écritures marquées ✱. Même clé + même corps = même réponse, sans doublon. |
| Version | `X-App-Version: 1.0.0+1` ; `426 upgrade_required` si la version est refusée. |
| Cache | Réponses privées en `Cache-Control: no-store, private`, sauf contenus publics (`max-age` court + `ETag`). |
| Pagination | `?page=` / `?per_page=` (max 50) ; réponse `{ data: [], meta: { page, per_page, total, has_more } }`. |
| Dates | ISO 8601 UTC ; l'application affiche en heure locale. |
| Limitation | `429 too_many_requests` avec `Retry-After` en secondes. |
| Aucun secret ni donnée personnelle dans l'URL | Identifiants opaques uniquement. |

### Format d'erreur

```json
{ "error": "validation_failed", "message": "Le message est trop court.", "errors": { "message": ["Au moins 20 caractères."] } }
```

| Statut | `error` | Sens pour l'application |
|---|---|---|
| 401 | `unauthenticated` | Session expirée : purge locale, retour à la connexion |
| 403 | `forbidden` | Droit retiré : relire `GET me`, fermer l'espace |
| 403 | `device_required` / `device_revoked` | Enregistrer l'appareil / **effacement local complet** |
| 404 | `not_found` | Ressource absente ou hors périmètre (jamais de différence entre les deux) |
| 409 | `conflict` | Alerte modifiée entre-temps : relire |
| 413 | `payload_too_large` | Fichier trop lourd |
| 422 | `validation_failed` | Erreurs par champ |
| 426 | `upgrade_required` | Mise à jour obligatoire |
| 429 | `too_many_requests` | Attendre `Retry-After` |
| 503 | `maintenance` | Écran de maintenance (`message`, `ends_at`) ; l'alerte reste ouverte |

## 2. Routes

### 2.1 Ouvertes (sans compte)

| Méthode et chemin | Rôle | Réutilise |
|---|---|---|
| `GET status` | Santé, mode maintenance, version minimale | `settings` |
| `GET alert-config` | Catégories, bornes `subject_max`, `desc_min`, `desc_max`, `ok`, `reason` | `AssistantEngine::alertConfig()` |
| `POST alerts` ✱ | Créer une alerte (compte non requis) | `GuestticketController@gueststore1` (sans code) ou `@gueststore` (avec code) |
| `POST alerts/email/validate` | Envoyer un code à l'adresse | `GuestticketController@emailsvalidateguest` |
| `POST alerts/email/verify` | Vérifier le code, renvoie un **jeton de suivi limité à cette alerte** | `GuestticketController@verifyotp` |
| `GET alerts/{ticket}` | Suivre l'alerte avec le jeton de suivi | `Ticket`, `Comment` |
| `POST alerts/{ticket}/messages` ✱ | Répondre | `CommentsController` / `ForumAlertController@reply` |
| `POST assistant/ask` | Question à l'assistant | `AssistantController@ask` (`throttle:30,1`) |
| `GET content/news`, `GET content/news/{slug}` | Actualités : chapô, corps, FAQ, méta | `knowledge-view` (`articles`) |
| `GET content/faq` | FAQ | `faq` |
| `GET content/pages/{slug}` | Pages légales | table `pages` |
| `GET content/emergency` | Contacts d'urgence, consultation psychologue | réglages du site |

Corps de `POST alerts` :

```json
{ "category": 3, "subject": "Harcèlement", "message": "…", "email": null, "anonymous": true,
  "location": { "lat": 6.3703, "lng": 2.3912, "accuracy_m": 800 }, "attachments": [] }
```

Réponse `201` : `{ "data": { "id": "SPG-31", "status": "New", "tracking_token": "…" } }`. Alerte anonyme : le serveur crée l'adresse technique `alerte-<hex>@anonyme.invalid` (aucun e-mail n'y est envoyé). Pièces jointes : `POST alerts/{ticket}/attachments` ✱ (multipart, liste blanche d'extensions de `gn_safe_upload_name()`, taille plafonnée).

### 2.2 Compte (client)

| Méthode et chemin | Rôle | Réutilise |
|---|---|---|
| `POST auth/register` | Inscription (âge minimal lu dans `referentials`) | Inscription de `/espace-client` |
| `POST auth/login` | Connexion → jeton + `me` | Connexion de `/espace-client` |
| `POST auth/logout` | Révoque le jeton et l'appareil courant | — |
| `POST auth/password/forgot`, `POST auth/password/reset` | Mot de passe oublié (réponse identique que le compte existe ou non) | Flux existant |
| `GET me` | Profil, `roles[]`, `permissions[]`, `spaces[]` (`client`, `team`) | Spatie |
| `PATCH me` | Nom, préférences | Profil de `/espace-client` |
| `PATCH me/security` | État du PIN (booléen seulement), aperçus de notification | — |
| `DELETE me` | Suppression du compte (exigée par les stores) | À créer, journalisée |
| `POST me/devices`, `GET me/devices`, `DELETE me/devices/{uuid}` | Appareils | À créer |
| `POST me/devices/{uuid}/wiped` | Accusé d'effacement | À créer |
| `POST me/push-subscriptions`, `DELETE …/{id}` | Jeton FCM | À créer |
| `GET me/alerts?state=pending\|followed\|resolved` | Mes alertes (mêmes regroupements que le web : en attente = New + On-Hold, suivies = Inprogress + Re-Open, résolues = Closed) | `User\GnClientController` |
| `GET me/alerts/{id}` | Détail, frise, pièces jointes | idem |
| `POST me/alerts/{id}/messages` ✱ | Répondre | `CommentsController` |
| `POST me/alerts/{id}/close`, `POST me/alerts/{id}/rating` ✱ | Clôturer, évaluer | contrôleurs d'espace client |
| `GET me/notifications` | Notifications | Notifications de l'espace client |
| `GET attachments/{id}/url` | URL signée courte (≈ 5 min) | `ImageController` (mêmes règles d'accès) |

### 2.3 Équipe (permission `Mobile Access`)

Toute route `team/*` exige un jeton **et** la permission. Le périmètre reprend celui de `AlertExportController` et de la recherche d'administration : superadmin = tout ; prestataire = dossiers affectés, auto-attribués ou de ses groupes.

| Méthode et chemin | Rôle |
|---|---|
| `GET team/dashboard` | Compteurs (nouvelles, en cours, en attente, en retard) |
| `GET team/alerts?status=&priority=&q=` | Liste paginée, périmètre du rôle |
| `GET team/alerts/{id}` | Détail ; position **arrondie** ; historique |
| `POST team/alerts/{id}/ack` ✱ | Accusé de réception |
| `POST team/alerts/{id}/take` ✱ | Prise en charge |
| `POST team/alerts/{id}/messages` ✱ | Réponse à la personne |
| `POST team/alerts/{id}/notes` ✱ | Note interne (jamais visible par la personne) |
| `POST team/alerts/{id}/status` ✱ | Statut parmi `New`, `Inprogress`, `On-Hold`, `Closed`, `Re-Open` |
| `POST team/step-up` | Mot de passe → jeton à usage unique (`X-Step-Up-Token`) |
| `POST team/alerts/{id}/reassign` ✱ | Réattribution (**step-up**) |

L'attribution de rôles, les exports, les rapports et la configuration restent **sur le site d'administration** : aucune route mobile n'accepte un nom de rôle.

## 3. Push FCM

Message « données seules », priorité haute : `data = { type, id }` et rien d'autre ; **pas de bloc `notification`**. Types : `alert_reply`, `alert_status`, `team_assigned`, `team_new_alert` (voir le plan, §9). `id` : `[A-Za-z0-9_-]{1,64}`. Le texte est choisi par l'application ; il est neutre quand les aperçus sont masqués.

## 4. Sécurité côté serveur (à tester)

- Chaque route `team/*` : 403 sans la permission, 404 hors périmètre (même réponse qu'une ressource absente).
- `GET alerts/{ticket}` : jeton de suivi valable pour **une seule** alerte.
- Journal d'audit des actions d'équipe (qui, quoi, quand), sans contenu de message.
- Limiteurs : création d'alerte, envoi de code, connexion, assistant, réinitialisation de mot de passe.
- Aucune donnée personnelle dans les journaux Laravel.
- En-têtes : `Cache-Control: no-store, private` sur tout ce qui est privé ; `X-Content-Type-Options: nosniff`.

## 5. Tests serveur attendus

Pour chaque route : 200, 401, 403, 404, 422, 429. En plus : rejeu idempotent (même clé = même réponse), jeton de suivi d'une autre alerte refusé, appareil révoqué (403 `device_revoked`), périmètre par rôle (un prestataire ne voit pas le dossier d'un autre), alerte anonyme sans e-mail sortant vers `*.invalid`.
