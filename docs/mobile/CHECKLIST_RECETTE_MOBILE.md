# Checklist de recette : application mobile GANXÔ Alerte VBG

> **Statut : liste à jouer sur appareil réel**, une fois l'application construite. Rien n'a encore été exécuté.
> Chaque ligne se coche avec la date, l'appareil et la personne qui a testé. Les points « appareil seulement » ne se
> vérifient pas par des tests automatiques. Identifiants `MOB-G-xx`.

Appareils minimum : un Android récent, un Android d'entrée de gamme (≤ 3 Go de mémoire), un Android 13 ou plus (notifications), un iPhone (quand iOS sera livré).

## A. Sans compte

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-01 | Ouvrir l'application sans compte | Accueil, alerte, assistant et urgences accessibles | ☐ |
| MOB-G-02 | Créer une alerte avec vérification par code | Alerte créée, numéro affiché, suivi possible | ☐ |
| MOB-G-03 | Créer une alerte anonyme | Alerte créée, aucun e-mail réclamé, aucun e-mail envoyé | ☐ |
| MOB-G-04 | Créer une alerte avec une pièce jointe autorisée, puis avec un `.php` | La première passe ; la seconde est refusée avec un message clair | ☐ |
| MOB-G-05 | SOS : maintenir 2 s | Alerte préremplie ; position partagée seulement si acceptée ; le texte dit que rien n'est envoyé aux secours | ☐ |
| MOB-G-06 | SOS : relâcher avant 2 s | Rien ne se passe | ☐ |
| MOB-G-07 | Assistant : poser une question, créer une alerte en conversation | Réponse ; alerte créée ; aucun texte saisi dans les journaux serveur | ☐ |

## B. Hors ligne et réseau

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-10 | Rédiger une alerte sans réseau, rétablir le réseau | Envoi automatique, **un seul** dossier créé | ☐ |
| MOB-G-11 | Couper le réseau pendant l'envoi puis relancer | Pas de doublon (même `Idempotency-Key`) | ☐ |
| MOB-G-12 | Alerte avec pièce jointe sans réseau | Message clair, brouillon conservé, rien en file | ☐ |
| MOB-G-13 | Lire actualités et FAQ hors ligne | Dernière copie datée, bandeau « Hors ligne » | ☐ |
| MOB-G-14 | Mode maintenance (503) | Écran de maintenance ; l'alerte reste ouverte | ☐ |
| MOB-G-15 | Réponses 401, 403, 404, 413, 422, 429 | Message clair pour chacune | ☐ |

## C. Compte client

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-20 | Inscription, connexion, déconnexion | Parcours complet ; données locales purgées à la déconnexion | ☐ |
| MOB-G-21 | Mot de passe oublié avec un compte existant, puis inexistant | Même réponse dans les deux cas | ☐ |
| MOB-G-22 | Mes alertes : en attente, suivies, résolues | Mêmes regroupements que le site | ☐ |
| MOB-G-23 | Répondre, joindre un fichier, clôturer, évaluer | Chaque action visible aussi sur le site | ☐ |
| MOB-G-24 | Ouvrir l'alerte d'une autre personne (identifiant modifié) | 404, jamais le contenu | ☐ |
| MOB-G-25 | Supprimer mon compte | Compte supprimé côté serveur, données locales purgées | ☐ |

## D. Sécurité et vie privée (appareil seulement)

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-30 | Activer le PIN, saisir 5 codes faux | Session fermée au 5ᵉ essai ; reconnexion par mot de passe | ☐ |
| MOB-G-31 | PIN actif : ouvrir SOS, urgences, alerte sans code | Accessibles sans code | ☐ |
| MOB-G-32 | Capture d'écran sur messages, suivi, alerte | Écran noir ou capture refusée | ☐ |
| MOB-G-33 | Sélecteur d'applications (aperçu) | Contenu masqué | ☐ |
| MOB-G-34 | Révoquer l'appareil depuis le serveur | Au prochain contact : 403 `device_revoked`, **effacement complet** | ☐ |
| MOB-G-35 | Mode discret | Nom et icône neutres ; limites expliquées à l'écran | ☐ |
| MOB-G-36 | Journaux du serveur et de l'application | Aucun contenu saisi, aucun mot de passe, aucun jeton | ☐ |
| MOB-G-37 | Réseau en HTTP (version de publication) | Connexion refusée | ☐ |

## E. Notifications

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-40 | L'équipe répond à mon alerte | Notification sans le texte de la réponse ; ouvre l'alerte | ☐ |
| MOB-G-41 | Aperçus masqués | Titre et corps neutres | ☐ |
| MOB-G-42 | Application fermée, puis ouverte par la notification | Écran correct, après le PIN si actif | ☐ |
| MOB-G-43 | Refuser l'autorisation (Android 13+) | L'application fonctionne sans notification | ☐ |
| MOB-G-44 | Sans `google-services.json` | L'application démarre sans demande d'autorisation | ☐ |

## F. Espace Équipe

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-50 | Compte sans la permission `Mobile Access` | Aucun espace Équipe visible ; routes `team/*` en 403 | ☐ |
| MOB-G-51 | Prestataire : liste des alertes | Seulement ses dossiers (affectés, auto-attribués, de ses groupes) | ☐ |
| MOB-G-52 | Superadmin : liste des alertes | Tous les dossiers | ☐ |
| MOB-G-53 | Accuser, prendre en charge, répondre, note interne, statut | Visible sur le site d'administration ; la note n'est **jamais** visible par la personne | ☐ |
| MOB-G-54 | Réattribution | Demande le mot de passe (step-up) ; jeton à usage unique | ☐ |
| MOB-G-55 | Position d'une alerte | Arrondie, avertissement avant ouverture de Maps | ☐ |
| MOB-G-56 | PIN obligatoire pour entrer ; verrouillage après inactivité | Conforme à D-MOB-G7 / plan §8 | ☐ |
| MOB-G-57 | Hors ligne | Bandeau « appelez directement la personne », écritures grisées | ☐ |
| MOB-G-58 | Chercher un moyen d'attribuer un rôle depuis le mobile | Impossible | ☐ |

## G. Accessibilité (appareil seulement)

| Réf. | Parcours | Résultat attendu | Fait |
|---|---|---|---|
| MOB-G-60 | TalkBack : créer une alerte de bout en bout | Tous les champs, boutons et erreurs sont annoncés | ☐ |
| MOB-G-61 | VoiceOver (iOS) : même parcours | Idem | ☐ |
| MOB-G-62 | Taille de police du système au maximum | Aucun texte coupé, aucune cible < 48 dp | ☐ |
| MOB-G-63 | Mode nuit | Contrastes AA, aucune information portée par la seule couleur | ☐ |
| MOB-G-64 | Navigation clavier ou commutateur | Ordre logique, focus visible | ☐ |

## H. Publication

| Réf. | Contrôle | Fait |
|---|---|---|
| MOB-G-70 | AAB signé, versionCode supérieur au précédent | ☐ |
| MOB-G-71 | Aucun secret dans le dépôt (`key.properties`, `.jks`, `google-services.json`) | ☐ |
| MOB-G-72 | Fiche Play : description honnête, Data safety exacte, compte d'accès de test fourni | ☐ |
| MOB-G-73 | Âge minimal et tranche déclarés conformes à la décision du client (D-MOB-G5) | ☐ |
| MOB-G-74 | Suppression de compte accessible depuis l'application ET décrite sur une page web | ☐ |
| MOB-G-75 | Compte Apple Developer d'organisation validé (avant tout envoi iOS) | ☐ |

## Critères de sortie

Aucune ligne des sections A à F ne reste en échec ; MOB-G-32, 34, 40 et 60 réussis sur appareil réel ; aucun secret dans le dépôt ;
les décisions D-MOB-G1 à G10 du plan sont tranchées par écrit.
