/* Libellés français pour Summernote et Dropzone : à charger juste après chaque bibliothèque, avant son initialisation. */
/* Bibliothèques tierces (éditeur de texte, dépôt de fichiers) : libellés français */
(function () {
  'use strict';
  var $ = window.jQuery;
  if ($ && $.summernote && $.summernote.lang && $.summernote.lang['en-US']) {
    var L = $.summernote.lang['en-US'];
    var set = function (g, o) { L[g] = Object.assign(L[g] || {}, o); };
    set('font', { bold: 'Gras', italic: 'Italique', underline: 'Souligné', clear: 'Effacer la mise en forme', height: 'Interligne', strikethrough: 'Barré', subscript: 'Indice', superscript: 'Exposant', size: 'Taille', name: 'Police' });
    set('style', { style: 'Style', p: 'Paragraphe', blockquote: 'Citation', pre: 'Code', h1: 'Titre 1', h2: 'Titre 2', h3: 'Titre 3', h4: 'Titre 4', h5: 'Titre 5', h6: 'Titre 6' });
    set('lists', { unordered: 'Liste à puces', ordered: 'Liste numérotée' });
    set('color', { recent: 'Dernière couleur', more: 'Plus de couleurs', background: 'Fond', foreground: 'Texte', transparent: 'Transparent', setTransparent: 'Transparent', reset: 'Réinitialiser', resetToDefault: 'Par défaut' });
    set('paragraph', { paragraph: 'Paragraphe', outdent: 'Diminuer le retrait', indent: 'Augmenter le retrait', left: 'Aligner à gauche', center: 'Centrer', right: 'Aligner à droite', justify: 'Justifier' });
    set('table', { table: 'Tableau', addRowAbove: 'Ajouter une ligne au-dessus', addRowBelow: 'Ajouter une ligne en dessous', addColLeft: 'Ajouter une colonne à gauche', addColRight: 'Ajouter une colonne à droite', delRow: 'Supprimer la ligne', delCol: 'Supprimer la colonne', delTable: 'Supprimer le tableau' });
    set('link', { link: 'Lien', insert: 'Insérer un lien', unlink: 'Supprimer le lien', edit: 'Modifier', textToDisplay: 'Texte à afficher', url: 'Adresse du lien', openInNewWindow: 'Ouvrir dans une nouvelle fenêtre' });
    set('image', { image: 'Image', insert: 'Insérer une image', resizeFull: 'Pleine taille', resizeHalf: 'Moitié', resizeQuarter: 'Quart', floatLeft: 'À gauche', floatRight: 'À droite', floatNone: 'Aucun', remove: 'Supprimer l’image', url: 'Adresse de l’image', selectFromFiles: 'Choisir un fichier' });
    set('video', { video: 'Vidéo', videoLink: 'Lien de la vidéo', insert: 'Insérer une vidéo', url: 'Adresse de la vidéo' });
    set('hr', { insert: 'Insérer une ligne' });
    set('options', { help: 'Aide', fullscreen: 'Plein écran', codeview: 'Code source' });
    set('history', { undo: 'Annuler', redo: 'Rétablir' });
    set('shortcut', { shortcuts: 'Raccourcis clavier', close: 'Fermer', textFormatting: 'Mise en forme', action: 'Action', paragraphFormatting: 'Paragraphe', documentStyle: 'Style du document', extraKeys: 'Autres touches' });
    set('help', { 'undo': 'Annule la dernière action', 'redo': 'Rétablit la dernière action annulée', 'tab': 'Augmente le retrait', 'untab': 'Diminue le retrait', 'insertParagraph': 'Insère un paragraphe', 'insertHorizontalRule': 'Insère une ligne horizontale', 'indent': 'Augmente le retrait', 'outdent': 'Diminue le retrait', 'formatPara': 'Met le bloc en paragraphe', 'formatH1': 'Met le bloc en titre 1', 'formatH2': 'Met le bloc en titre 2', 'formatH3': 'Met le bloc en titre 3', 'formatH4': 'Met le bloc en titre 4', 'formatH5': 'Met le bloc en titre 5', 'formatH6': 'Met le bloc en titre 6', 'bold': 'Gras', 'italic': 'Italique', 'underline': 'Souligné', 'strikethrough': 'Barré', 'removeFormat': 'Efface la mise en forme', 'justifyLeft': 'Aligne à gauche', 'justifyCenter': 'Centre', 'justifyRight': 'Aligne à droite', 'justifyFull': 'Justifie', 'insertUnorderedList': 'Liste à puces', 'insertOrderedList': 'Liste numérotée', 'createLink': 'Insère un lien', 'insertTable': 'Insère un tableau' });
    set('about', { about: 'À propos' }); set('color', { select: 'Choisir' });
  }
  if (window.Dropzone && Dropzone.prototype && Dropzone.prototype.defaultOptions) {
    Object.assign(Dropzone.prototype.defaultOptions, {
      dictDefaultMessage: 'Déposez vos fichiers ici ou cliquez pour les choisir',
      dictFallbackMessage: 'Votre navigateur ne permet pas le dépôt de fichiers.',
      dictFileTooBig: 'Fichier trop volumineux ({{filesize}} Mo). Maximum : {{maxFilesize}} Mo.',
      dictInvalidFileType: 'Ce type de fichier n’est pas accepté.',
      dictResponseError: 'Erreur du serveur ({{statusCode}}).',
      dictCancelUpload: 'Annuler l’envoi',
      dictRemoveFile: 'Retirer le fichier',
      dictMaxFilesExceeded: 'Vous ne pouvez pas joindre davantage de fichiers.'
    });
  }
})();
