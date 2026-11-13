{{-- Bandeau d'aide : maillage FAQ -> contact / alerte --}}
@php $gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login'); @endphp
<section class="gn-section gc-help-sec" aria-labelledby="gc-help-title">
	<div class="gn-container">
		<div class="gc-help">
			<div>
				<h2 id="gc-help-title">Vous ne trouvez pas votre réponse&nbsp;?</h2>
				<p>Écrivez-nous ou créez une alerte : l’équipe GANXÔ vous répond. Si vous êtes en danger immédiat, joignez d’abord les secours de votre pays.</p>
			</div>
			<div class="gc-help__a">
				<a class="gn-btn gn-btn--primary" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
				@if (setting('CONTACT_ENABLE') == 'yes')<a class="gn-btn gn-btn--ghost" href="{{ url('/contact-us') }}">Nous contacter</a>@endif
			</div>
		</div>
	</div>
</section>
