{{-- Section d'accueil « Consultation en ligne avec le psychologue » + fenêtre modale (dialog natif, accessible). --}}
@php
	$gnPsyWa = 'https://wa.me/22963608513?text=' . rawurlencode('Bonjour, je souhaite prendre une consultation en ligne avec le psychologue.');
	$gnPsyPoster = 'uploads/featureimage/gn-consultation-en-ligne-psychologue.jpg';
@endphp
<section class="gn-section gh-psy" id="consultation" aria-labelledby="psy-title">
	<div class="gn-container gh-psy__in">
		<div class="gh-psy__txt">
			<span class="gn-eyebrow">Consultation en ligne</span>
			<h2 id="psy-title">Consultation en ligne avec le psychologue&nbsp;: parler sans se déplacer</h2>
			<p class="gh-psy__lead">Se sentir à bout, ruminer, mal dormir, avoir peur de ne pas être compris·e&nbsp;: ces signaux ne sont pas de la faiblesse. Ils disent qu’une charge est devenue trop lourde pour être portée seul·e.</p>
			<h3>Pourquoi parler à un psychologue&nbsp;?</h3>
			<ul class="gh-psy__list">
				<li><strong>Un espace d’écoute confidentiel</strong>, sans jugement, où l’on peut tout poser.</li>
				<li><strong>Des repères concrets</strong> pour le stress, l’angoisse, le deuil, les violences vécues ou les discriminations.</li>
				<li><strong>Sans déplacement</strong>&nbsp;: l’échange se fait en ligne, depuis un lieu où vous vous sentez à l’aise.</li>
			</ul>
			<div class="gh-actions">
				<button type="button" class="gn-btn gn-btn--lg" data-gn-consult-open>Prendre contact</button>
				<a class="gn-btn gn-btn--ghost gn-btn--lg" href="{{ url('/article/consultation-en-ligne-psychologue') }}">Lire l’article</a>
			</div>
			<p class="gh-psy__note">Ce n’est pas un service d’urgence. En danger immédiat, contactez d’abord les secours de votre pays.</p>
		</div>
		<div class="gh-psy__img">
			{!! gn_picture($gnPsyPoster, 'Affiche : consultation en ligne avec le psychologue de BESYP. Contactez-le sur WhatsApp au +229 63 60 85 13.', 'Consultation en ligne avec le psychologue', '(max-width: 900px) 92vw, 420px', 'loading="lazy"') !!}
		</div>
	</div>
</section>

<dialog class="gh-modal" id="gn-consult" aria-labelledby="gn-consult-title" aria-describedby="gn-consult-desc">
	<div class="gh-modal__bar" aria-hidden="true"></div>
	<button type="button" class="gh-modal__x" data-gn-consult-close aria-label="Fermer la fenêtre">&times;</button>
	<div class="gh-modal__b">
		<span class="gn-eyebrow">Consultation en ligne</span>
		<h2 id="gn-consult-title">Parler à un psychologue, sans vous déplacer</h2>
		<p id="gn-consult-desc">Écrivez-nous sur WhatsApp : quelques mots suffisent pour commencer. Les horaires et les modalités vous sont précisés lors de ce premier contact.</p>
		<ol class="gh-modal__steps">
			<li><strong>Vous écrivez</strong> au <strong>+229 63 60 85 13</strong> (WhatsApp).</li>
			<li><strong>Vous convenez d’un créneau</strong> avec le professionnel.</li>
			<li><strong>Vous échangez en ligne</strong>, à l’abri des regards.</li>
		</ol>
		<div class="gh-modal__act">
			<a class="gn-btn gn-btn--lg" href="{{ $gnPsyWa }}" target="_blank" rel="noopener">Écrire sur WhatsApp</a>
			<a class="gn-btn gn-btn--ghost gn-btn--lg" href="tel:+22963608513">Appeler</a>
		</div>
		<p class="gh-modal__note">Ce service n’est pas une urgence. Si vous êtes en danger immédiat, contactez d’abord les secours de votre pays. <a href="{{ url('/article/consultation-en-ligne-psychologue') }}">En savoir plus</a></p>
	</div>
</dialog>
