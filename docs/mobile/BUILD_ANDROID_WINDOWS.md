# Construire et lancer l'application Android depuis Windows

> **Statut : guide préparatoire.** L'application n'existe pas encore (voir [`PLAN_MOBILE_APP.md`](PLAN_MOBILE_APP.md)). Ce guide
> décrit la procédure à suivre une fois le dossier `mobile/` créé. Il reprend la structure du guide de Xomiton ;
> les noms propres au projet (identifiant d'application, adresses) sont des **propositions**.

## 1. Ce qu'il faut avoir

| Élément | Remarque |
|---|---|
| Windows 10 ou 11 | 8 Go de mémoire minimum, 16 Go recommandés |
| Flutter SDK stable | Dart ^3 fourni avec le SDK |
| Android Studio | SDK Android, outils de ligne de commande, émulateur |
| JDK 17 | Livré avec Android Studio |
| Un téléphone Android | Mode développeur et débogage USB, ou un émulateur |
| API locale | Laravel de `ganhovbg` lancé en local |

## 2. Installer les outils

1. Installer Flutter (archive du SDK, dossier sans espace ni accent, ex. `C:\dev\flutter`) et l'ajouter au `PATH`.
2. Installer Android Studio, ouvrir **SDK Manager** : SDK Platform récent, *Android SDK Command-line Tools*, *Platform-Tools*.
3. Accepter les licences : `flutter doctor --android-licenses`.
4. Vérifier : `flutter doctor` ne doit signaler aucune croix pour Android.

## 3. Récupérer le code

```bash
cd C:\Projects\ganho\mobile
flutter pub get
flutter analyze
flutter test
```

Ne pas lancer `dart format` sur tout `lib/` d'un coup si le dépôt a un style antérieur : formater seulement les fichiers neufs.

## 4. L'API locale

L'émulateur Android voit l'ordinateur à l'adresse **`10.0.2.2`** (pas `localhost`).

```bash
# dans C:\Projects\ganho\ganhovbg\public
php -S 0.0.0.0:8005 ..\server.php
```

Adresse passée à l'application : `--dart-define=API_BASE_URL=http://10.0.2.2:8005/api/mobile/v1`.
Pour un vrai téléphone sur le même Wi-Fi : l'adresse IP de l'ordinateur à la place de `10.0.2.2`, et autoriser le port dans le pare-feu.
Le HTTP en clair n'est autorisé que par la configuration **debug** (`src/debug/res/xml/network_security_config.xml`) ; la version de publication n'accepte que HTTPS.

## 5. Lancer l'application

```bash
flutter devices
flutter run --dart-define=API_BASE_URL=http://10.0.2.2:8005/api/mobile/v1
```

Les captures d'écran sont interdites sur les écrans sensibles. Pour une recette, un build de test peut les autoriser avec
`--dart-define=ALLOW_SCREENSHOTS=true` ; cette option **doit être ignorée en `release`**.

## 6. L'APK de test

```bash
flutter build apk --debug --dart-define=API_BASE_URL=http://10.0.2.2:8005/api/mobile/v1
```

Fichier produit : `build/app/outputs/flutter-apk/app-debug.apk`. Ne jamais distribuer un APK debug au public.

## 7. Signer la version de publication

1. Créer une clé : `keytool -genkey -v -keystore ganho-upload.jks -keyalg RSA -keysize 2048 -validity 10000 -alias upload`.
2. La ranger **hors dépôt**, avec une sauvegarde chiffrée : sa perte empêche toute mise à jour sous le même nom.
3. Copier `android/key.properties.example` en `android/key.properties` (ignoré par git) et renseigner mot de passe, alias, chemin.
4. Ne jamais committer le fichier `.jks`, `key.properties` ni `google-services.json`.

## 8. Le paquet de publication

```bash
flutter build appbundle --release --obfuscate --split-debug-info=build/symbols \
  --dart-define=API_BASE_URL=https://ganho.app/api/mobile/v1
```

- Sortie : `build/app/outputs/bundle/release/app-release.aab`.
- Garder `build/symbols` et le fichier `mapping.txt` de R8 **hors dépôt** mais sauvegardés : ils servent à lire les rapports d'erreur.
- `versionName` et `versionCode` viennent de `pubspec.yaml` (`version: 1.0.0+1`) ; le code de version doit augmenter à chaque envoi au Play Store.

## 9. Intégration continue (proposition)

Un workflow GitHub Actions qui exécute `flutter analyze`, `flutter test`, construit un APK debug et vérifie un budget de taille. Aucun secret dans le workflow : la signature se fait en local ou avec des secrets du dépôt, jamais dans le code.

## 10. Notifications : Firebase

1. Console Firebase : créer le projet, ajouter l'application Android (identifiant proposé : `app.ganho.alerte`).
2. Télécharger `google-services.json` et le placer dans `android/app/` (**ignoré par git**).
3. Côté serveur : compte de service FCM dans l'environnement du serveur (`.env`), jamais dans le dépôt.
4. Tester : connexion, accepter l'autorisation (Android 13+), vérifier que l'abonnement est enregistré.
5. iOS : `GoogleService-Info.plist`, capacité « Push Notifications » et clé APNs (compte Apple Developer requis).

Sans `google-services.json`, l'application doit démarrer normalement, sans demande d'autorisation de notification.

## 11. Permissions déclarées

| Permission | Pourquoi |
|---|---|
| `INTERNET` | Appels à l'API |
| `ACCESS_FINE_LOCATION` | Position du SOS, **uniquement à la demande** de la personne |
| `POST_NOTIFICATIONS` | Notifications (Android 13+) |
| Aucune autre | Les fichiers sont choisis par le sélecteur système, sans permission de stockage large |

## 12. Dépannage

| Symptôme | Piste |
|---|---|
| `flutter doctor` : licences Android non acceptées | `flutter doctor --android-licenses` |
| L'application n'atteint pas l'API en local | `10.0.2.2` (émulateur) ; serveur lancé sur `0.0.0.0` ; pare-feu ; HTTP autorisé en debug seulement |
| Erreur de certificat en production | Vérifier que le certificat SSL de `ganho.app` est actif |
| Les notifications n'arrivent pas | `google-services.json` absent, jeton FCM non enregistré, aperçus masqués, canal désactivé dans les réglages du téléphone |
| Le build échoue après une mise à jour de Flutter | `flutter clean`, puis `flutter pub get` |
| Écran noir en capture d'écran | Normal : `FLAG_SECURE` est actif sur les écrans sensibles |
