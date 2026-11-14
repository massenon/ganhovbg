# Application mobile GANXÔ Alerte VBG : dossier de conception

**Statut : conception. Aucun code mobile n'existe encore.** Ce dossier prépare l'application mobile de ganho.app en suivant
la méthode du projet mobile de Xomiton (plan par lots, contrat d'API, guide de build, checklist de recette, maquettes validées
avant le code).

| Document | Contenu |
|---|---|
| [`PLAN_MOBILE_APP.md`](PLAN_MOBILE_APP.md) | Plan principal : exigences, existant, architecture Flutter, espaces et rôles, sécurité, hors ligne, push, lots, décisions à valider, risques |
| [`API_MOBILE.md`](API_MOBILE.md) | Contrat proposé du groupe `api/mobile/v1/*` (à créer) et réutilisation du code Laravel existant |
| [`BUILD_ANDROID_WINDOWS.md`](BUILD_ANDROID_WINDOWS.md) | Construire, signer et publier l'application Android depuis Windows |
| [`CHECKLIST_RECETTE_MOBILE.md`](CHECKLIST_RECETTE_MOBILE.md) | Parcours de recette sur appareil réel (MOB-G-01 et suivants) |

## Par où commencer

1. Le client lit le plan et tranche les décisions **D-MOB-G1 à G10** (§13 du plan).
2. Maquettes mobiles (lot MOB-0) à valider, sur le modèle de `prototype_mobile` de Xomiton.
3. En parallèle, lot serveur MOB-S1 (jetons, appareils, `api/mobile/v1`) : il ne dépend pas des écrans.
4. Ensuite seulement, code Flutter (lot MOB-M1).

## Ce qui existe déjà (et qu'on réutilise)

Le site ganho.app couvre déjà l'alerte (avec ou sans compte), l'assistant, les actualités, l'espace client et l'administration.
L'application mobile **ne duplique aucune règle métier** : elle appelle les mêmes contrôleurs et services derrière une API par jeton.
Aujourd'hui, seule l'API serveur à serveur du forum (`/api/forum/*`, signature HMAC) existe.

## Règles de rédaction du client

Ne jamais écrire « GANXÔ n'est pas un service d'urgence ». Écrire « LGBTQ », pas « LGBTQIA+ ». Textes en français uniquement.
Le bouton « Quitter vite » a été retiré du web à la demande du client et n'est pas repris.
