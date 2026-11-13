@php
	use App\Models\Apptitle;
	use Carbon\Carbon;

	$title = Apptitle::first();
	$data['title'] = $title;
	$gnSite = rtrim(config('app.url') ?: url('/'), '/');
	$gnLogo = $gnSite . '/assets/ganho/img/logo-lettres-blanches-416w.png';
	$gnSpectrum = ['#5B2BB8', '#2B33C9', '#0A66E8', '#0F8B6D', '#3F9A1A', '#FFBE05', '#F66100', '#E8381A'];
@endphp
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<meta name="color-scheme" content="light">
	<title>GANXÔ Alerte VBG</title>
	<!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
	<style>
		body{margin:0;padding:0;background:#F8F1FF}
		table,td,div,h1,h2,p,a{font-family:Montserrat,Arial,"Liberation Sans",Roboto,sans-serif}
		.gn-body p{margin:0 0 16px;font-size:16px;line-height:1.6;color:#17132B}
		.gn-body a{color:#2B33C9;word-break:break-all}
		.gn-body h1,.gn-body h2,.gn-body h3{margin:0 0 16px;line-height:1.2;color:#0B0B14}
		.gn-body ul,.gn-body ol{margin:0 0 16px;padding-left:22px;color:#17132B;line-height:1.6}
		@media (max-width:620px){.gn-wrap{width:100%!important}.gn-pad{padding:28px 20px!important}}
	</style>
</head>
<body style="margin:0;padding:0;background:#F8F1FF;">
	<table role="presentation" width="100%" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#F8F1FF;">
		<tr>
			<td align="center" style="padding:24px 12px;">
				<table role="presentation" class="gn-wrap" width="600" style="width:600px;max-width:100%;border-collapse:collapse;border:0;border-spacing:0;text-align:left;">
					{{-- En-tête nuit + logo --}}
					<tr>
						<td align="center" style="padding:32px 24px 28px 24px;background:#0B0B14;border-radius:24px 24px 0 0;">
							<a href="{{ $gnSite }}" style="text-decoration:none;"><img src="{{ $gnLogo }}" alt="GANXÔ" width="150" style="width:150px;max-width:100%;height:auto;display:block;border:0;"></a>
						</td>
					</tr>
					{{-- Bande spectre (8 couleurs du logo) --}}
					<tr>
						<td style="padding:0;font-size:0;line-height:0;">
							<table role="presentation" width="100%" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;"><tr>
								@foreach($gnSpectrum as $c)<td height="6" style="height:6px;background:{{ $c }};font-size:0;line-height:0;">&nbsp;</td>@endforeach
							</tr></table>
						</td>
					</tr>
					{{-- Contenu --}}
					<tr>
						<td class="gn-pad" style="padding:36px 36px 32px 36px;background:#FFFFFF;">
							<div class="gn-body" style="font-size:16px;line-height:1.6;color:#17132B;">{!! $emailBody !!}</div>
						</td>
					</tr>
					<tr>
						<td align="center" class="gn-pad" style="padding:0 36px 36px 36px;background:#FFFFFF;">
							<a href="{{ $gnSite }}" style="display:inline-block;padding:14px 32px;border-radius:999px;background:#2B33C9;color:#FFFFFF;font-family:Montserrat,Arial,sans-serif;font-size:15px;font-weight:700;text-decoration:none;">Ouvrir GANXÔ Alerte VBG</a>
						</td>
					</tr>
					{{-- Pied de page --}}
					<tr>
						<td align="center" style="padding:28px 24px;background:#0B0B14;border-radius:0 0 24px 24px;">
							<p style="margin:0 0 8px;font-size:13px;line-height:1.5;color:#B9B4D0;">Vous recevez ce message automatique de <a href="{{ $gnSite }}" style="color:#8FA2FF;text-decoration:none;">GANXÔ Alerte VBG</a>. Merci de ne pas y répondre directement.</p>
							<p style="margin:0;font-size:12px;line-height:1.5;color:#B9B4D0;">&copy; {{ Carbon::now()->format('Y') }} GANXÔ. Tous droits réservés.</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
