{{-- Bouton SOS fixe (maintien 2 s) + confirmation avant toute lecture de la position. Sobre : aucun décor arc-en-ciel. --}}
@php
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
@endphp
<button type="button" class="gn-sos" id="gn-sos" aria-describedby="gn-sos-desc" data-alert-url="{{ $gnAlertUrl }}">
	<span class="gn-sos__fill" aria-hidden="true"></span>
	<span class="gn-sos__t">SOS<small>Maintenir 2 s</small></span>
</button>
<p id="gn-sos-desc" class="gh-sr">Bouton SOS. Maintenez-le appuyé deux secondes (souris, doigt, ou touche Entrée ou Espace) pour ouvrir l’écran de confirmation. Rien n’est envoyé avant votre confirmation.</p>
<p class="gn-sos__hint" id="gn-sos-hint" role="status" hidden>Maintenez le bouton SOS appuyé 2 secondes.</p>

<dialog class="gh-dlg" id="gn-sos-dlg" aria-labelledby="gn-sos-title" aria-describedby="gn-sos-info">
	<div class="gh-dlg__in">
		<h2 id="gn-sos-title">Partager ma position avec l’équipe GANXÔ ?</h2>
		<div id="gn-sos-info">
			<p>Si vous acceptez, votre position GPS sera jointe à une <strong>alerte que vous allez créer</strong> sur cette plateforme. Elle sera visible par l’équipe GANXÔ qui traite les alertes. Vous pourrez la relire et la modifier avant d’envoyer.</p>
			<p class="gh-dlg__warn"><strong>Ce bouton ne contacte ni la police, ni les secours, ni un service d’urgence.</strong> En danger immédiat, appelez les secours de votre pays ou rendez-vous dans un lieu sûr.</p>
		</div>
		<div class="gh-dlg__acts">
			<button type="button" class="gn-btn gn-btn--danger" id="gn-sos-go">Partager ma position et créer l’alerte</button>
			<a class="gn-btn gn-btn--plain" href="{{ $gnAlertUrl }}">Créer l’alerte sans position</a>
			<button type="button" class="gn-btn gn-btn--plain" id="gn-sos-cancel">Annuler</button>
		</div>
		<p class="gh-dlg__st" id="gn-sos-st" role="status" aria-live="polite"></p>
	</div>
</dialog>
