@php
	// pages d'erreur : les variables du contrôleur peuvent manquer
	$page = $page ?? \App\Models\Pages::all();
	$footertext = $footertext ?? \App\Models\Footertext::first();
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
	$gnFaqCats = \App\Models\FaqCategory::where('status', '1')->orderBy('id')->take(5)->get();
@endphp
<footer class="gh-foot">
	<div class="gn-container">
		<h2 class="gh-sr">Plan du site</h2>
		<div class="gh-foot__top">
			<div>
				<a class="gh-foot__logo" href="{{ url('/') }}" title="GANXÔ Alerte VBG : accueil" aria-label="GANXÔ Alerte VBG, accueil">
					<picture>
						<source type="image/webp" srcset="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.webp') }}">
						<img src="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.png') }}" alt="Logo GANXÔ Alerte VBG" title="GANXÔ Alerte VBG" width="168" height="48" loading="lazy" decoding="async">
					</picture>
				</a>
				<p>Plateforme d’assistance pour les victimes ou témoins de violences basées sur le genre et les personnes en détresse psychologique.</p>
			</div>
			<div>
				<h3>Agir</h3>
				<ul>
					<li><a href="{{ $gnAlertUrl }}">Créer une alerte VBG</a></li>
					<li><a href="{{ url('/assistant') }}" title="Parler à l’assistant GANXÔ" data-gn-open-chat>Parler à l’assistant</a></li>
					<li><a href="{{ url('/#comment-ca-marche') }}">Comment ça marche</a></li>
					<li><a href="{{ url('/#consultation') }}">Consultation en ligne</a></li>
				</ul>
			</div>
			<div>
				<h3>S’informer</h3>
				<ul>
					@if (setting('KNOWLEDGE_ENABLE') == 'yes')<li><a href="{{ url('/knowledge') }}">Actualités et ressources</a></li>@endif
					@if (setting('FAQ_ENABLE') == 'yes')
						<li><a href="{{ url('/faq') }}">Questions fréquentes</a></li>
					@endif
					<li><a href="#newsletter">Newsletter</a></li>
				</ul>
			</div>
			<div>
				<h3>Communauté et contact</h3>
				<ul>
					<li><a href="{{ gn_forum_url() }}" title="Forum d’entraide GANXÔ">Forum d’entraide</a></li>
					@if (setting('CONTACT_ENABLE') == 'yes')<li><a href="{{ url('/contact-us') }}">Nous contacter</a></li>@endif
				</ul>
			</div>
		</div>
		<section class="gh-foot__news" id="newsletter" aria-labelledby="gh-news-title">
			<div class="gh-foot__news-text">
				<h3 id="gh-news-title">Newsletter</h3>
				<p>Recevez les actualités, ressources et rendez-vous de GANXÔ. Pas de publicité, désinscription en un clic.</p>
			</div>
			<form class="gh-foot__news-form" method="POST" action="{{ route('newsletter.subscribe') }}" novalidate>
				@csrf
				<div class="gh-sr" aria-hidden="true"><label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
				<div class="gh-foot__news-row">
					<label class="gh-sr" for="newsletter_email">Votre adresse e-mail</label>
					<input id="newsletter_email" name="newsletter_email" type="email" inputmode="email" autocomplete="email" placeholder="vous@exemple.org" value="{{ old('newsletter_email') }}" required>
					<button type="submit">S’abonner</button>
				</div>
				<label class="gh-foot__news-consent"><input type="checkbox" name="newsletter_consent" value="1" required> J’accepte de recevoir la newsletter de GANXÔ. Voir la <a href="{{ url('page/politique-de-confidentialite') }}">politique de confidentialité</a>.</label>
				@if (session('newsletter_status'))<p class="gh-foot__news-msg gh-foot__news-msg--ok" role="status">{{ session('newsletter_status') }}</p>@endif
				@if (session('newsletter_error'))<p class="gh-foot__news-msg gh-foot__news-msg--err" role="alert">{{ session('newsletter_error') }}</p>@endif
			</form>
		</section>
		<div class="gh-foot__legal">
			<div class="gh-foot__copy">{!! $footertext->copyright ?? '' !!}</div>
			@php $gnLegal = collect($page ?? [])->filter(function ($pg) { return $pg->status == '1' && ($pg->viewonpages == 'both' || $pg->viewonpages == 'footer'); }); @endphp
			@if($gnLegal->count())
			<nav class="gh-foot__links" aria-label="Informations légales">
				<ul>
					@foreach ($gnLegal as $pg)
						<li><a href="{{ url('page/' . $pg->pageslug) }}">{{ $pg->pagename }}</a></li>
					@endforeach
				</ul>
			</nav>
			@endif
		</div>
	</div>
</footer>
