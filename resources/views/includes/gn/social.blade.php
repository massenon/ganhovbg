{{-- Connexion sociale (si activée dans les réglages). Les variables JS facebook/google/twitter/envato sont définies par la page. --}}
@if($socialAuthSettings->envato_status == 'enable' || $socialAuthSettings->google_status == 'enable' || $socialAuthSettings->facebook_status == 'enable' || $socialAuthSettings->twitter_status == 'enable')
<div class="gnf-social">
	@if($socialAuthSettings->envato_status == 'enable')
	<button type="button" title="{{lang('Login with Envato')}}" onclick="window.location.href = envato;">Envato</button>
	@endif
	@if($socialAuthSettings->google_status == 'enable')
	<button type="button" title="{{lang('Login with Google')}}" onclick="window.location.href = google;">Google</button>
	@endif
	@if($socialAuthSettings->facebook_status == 'enable')
	<button type="button" title="{{lang('Login with Facebook')}}" onclick="window.location.href = facebook;">Facebook</button>
	@endif
	@if($socialAuthSettings->twitter_status == 'enable')
	<button type="button" title="{{lang('Login with Twitter')}}" onclick="window.location.href = twitter;">Twitter</button>
	@endif
</div>
@if(empty($gnNoOr))<p class="gnf-or"><span>ou avec un e-mail</span></p>@endif
@endif
