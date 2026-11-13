{{-- Newsletter GANXÔ : e-mail HTML (tables, styles en ligne) aux couleurs Ganho. Variables : $subject, $paragraphs (HTML échappé), $buttonLabel, $buttonUrl, $unsubscribeUrl, $consentDate, $preheader --}}
@php
	$gnSite = rtrim(config('app.url') ?: url('/'), '/');
	$gnLogo = $gnSite . '/assets/ganho/img/logo-lettres-blanches-416w.png';
	$bands = ['#5B2BB8', '#2B33C9', '#0A66E8', '#0F8B6D', '#3F9A1A', '#FFBE05', '#F66100', '#E8381A'];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="color-scheme" content="light">
<title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background:#F8F1FF;">
<span style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">{{ $preheader ?? '' }}</span>
<div style="margin:0;padding:24px 12px;background:#F8F1FF;font-family:Montserrat,Arial,'Liberation Sans',Roboto,sans-serif;">
<table role="presentation" width="600" align="center" style="width:600px;max-width:100%;border-collapse:collapse;margin:0 auto;">
	<tr><td align="center" style="padding:30px 24px 26px;background:#0B0B14;border-radius:24px 24px 0 0;"><a href="{{ $gnSite }}"><img src="{{ $gnLogo }}" alt="GANXÔ" width="150" style="display:block;border:0;width:150px;height:auto;"></a></td></tr>
	<tr><td style="padding:0;font-size:0;line-height:0;"><table role="presentation" width="100%" style="width:100%;border-collapse:collapse;"><tr>@foreach($bands as $c)<td height="6" style="height:6px;background:{{ $c }};font-size:0;line-height:0;">&nbsp;</td>@endforeach</tr></table></td></tr>
	<tr><td style="padding:36px 32px 32px;background:#FFFFFF;color:#17132B;">
		<h1 style="margin:0 0 22px;font-size:24px;line-height:1.25;font-weight:800;color:#0B0B14;">{{ $subject }}</h1>
		{!! $paragraphs !!}
		@if(!empty($buttonLabel) && !empty($buttonUrl))
			<p style="margin:26px 0 0;"><a href="{{ $buttonUrl }}" style="display:inline-block;padding:14px 32px;border-radius:999px;background:#2B33C9;color:#FFFFFF;font-size:15px;font-weight:700;text-decoration:none;">{{ $buttonLabel }}</a></p>
		@endif
	</td></tr>
	<tr><td align="center" style="padding:26px 24px;background:#0B0B14;border-radius:0 0 24px 24px;">
		<p style="margin:0 0 8px;font-size:13px;line-height:1.5;color:#B9B4D0;">Vous recevez cet e-mail parce que vous vous êtes abonné·e à la newsletter de GANXÔ Alerte VBG{{ !empty($consentDate) ? ' (consentement donné le ' . $consentDate . ')' : '' }}.</p>
		@if(!empty($unsubscribeUrl))<p style="margin:0 0 8px;font-size:13px;"><a href="{{ $unsubscribeUrl }}" style="color:#8FA2FF;">Me désinscrire en un clic</a></p>@endif
		<p style="margin:0;font-size:12px;color:#B9B4D0;">&copy; {{ date('Y') }} GANXÔ Alerte VBG &middot; <a href="{{ $gnSite }}" style="color:#8FA2FF;text-decoration:none;">{{ $gnSite }}</a></p>
	</td></tr>
</table>
</div>
</body>
</html>
