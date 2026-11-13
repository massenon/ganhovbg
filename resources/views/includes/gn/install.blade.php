{{-- Widget d'invitation à installer GANXÔ (PWA). Apparaît seulement si le navigateur le permet (ou sur iOS). --}}
<aside class="gn-install" id="gn-install" hidden aria-label="Installer l’application GANXÔ">
	<img class="gn-install__icon" src="{{ gn_asset('assets/ganho/img/icone-appli-192.png') }}" alt="" width="48" height="48" decoding="async">
	<div>
		<p class="gn-install__t">Installer GANXÔ Alerte VBG</p>
		<p class="gn-install__d">Accès direct depuis votre écran d’accueil, plus rapide et utilisable avec une connexion faible.</p>
	</div>
	<button type="button" class="gn-install__x" data-gn-install-close aria-label="Fermer">&times;</button>
	<div class="gn-install__go"><button type="button" class="gn-install__btn" data-gn-install-go hidden>Installer l’application</button></div>
	<p class="gn-install__how" data-gn-install-how hidden>Sur iPhone et iPad : touchez le bouton <strong>Partager</strong> de Safari, puis <strong>Sur l’écran d’accueil</strong>.</p>
</aside>
<script src="{{ gn_asset('assets/ganho/js/gn-install.js') }}" defer></script>
