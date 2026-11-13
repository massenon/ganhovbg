{{-- Photo en arche : <picture> WebP 640/1200 + repli JPEG, alt/title/width/height. Params : $n (numéro), $sizes, $lcp (bool). Crédits : Pexels (voir MANIFEST du design system). --}}
@php
	$gnPhotos = [
		'01' => ['alt' => "Portrait de profil d'un homme noir aux cheveux courts, une écharpe arc-en-ciel sur le col de sa veste, devant un mur bleu écaillé", 'title' => "Portrait d’un homme noir à l’écharpe arc-en-ciel", 'credit' => "Anete Lusina / Pexels"],
		'03' => ['alt' => "Jeune homme noir de profil, drapé dans un drapeau arc-en-ciel aux tons orangés, devant un mur de béton", 'title' => "Jeune homme drapé dans un drapeau arc-en-ciel", 'credit' => "Anete Lusina / Pexels"],
		'12' => ['alt' => "Personne drapée d'un drapeau arc-en-ciel, tenant une pancarte « Respect LGBT rights », sur fond vert kaki", 'title' => "Personne drapée d’un drapeau arc-en-ciel, pancarte Respect LGBT rights", 'credit' => "Pavel Danilyuk / Pexels", 'pos' => '22% 40%'],
		'19' => ['alt' => "Gros plan en noir et blanc sur le visage d'une femme noire aux tresses : une main est plaquée sur sa bouche, une larme brille sur sa joue", 'title' => "Une main sur la bouche : le silence imposé par la violence", 'credit' => "Nduwa Siachaba / Pexels", 'pos' => '30% 35%'],
		'18' => ['alt' => "Personne aux cheveux bouclés, de dos, drapée d'un drapeau arc-en-ciel, à la lumière dorée du coucher de soleil", 'title' => "Personne drapée d’un drapeau arc-en-ciel au coucher du soleil", 'credit' => "Vk Moraes / Pexels"],
		'15' => ['alt' => "Deux femmes noires qui se tiennent par la taille devant un mur en parpaings, l'une en robe rose, l'autre en chemise à motifs", 'title' => "Deux femmes noires qui se tiennent par la taille", 'credit' => "RDNE Stock project / Pexels"],
		'14' => ['alt' => "Deux amies noires qui rient aux éclats, en tee-shirts à rayures arc-en-ciel, sur une passerelle devant une maison", 'title' => "Deux amies qui rient aux éclats", 'credit' => "RDNE Stock project / Pexels"],
		'13' => ['alt' => "Drapeau arc-en-ciel dans un gobelet et carte « I will stand with you » posés sur un fond corail", 'title' => "Un drapeau arc-en-ciel et une carte de soutien", 'credit' => "RDNE Stock project / Pexels"],
	];
	$ph = $gnPhotos[$n];
	$base = asset('assets/ganho/img/photos/ph-' . $n);
@endphp
<picture>
	<source type="image/webp" srcset="{{ $base }}-640.webp 640w, {{ $base }}-1200.webp 1200w" sizes="{{ $sizes ?? '(max-width: 900px) 45vw, 300px' }}">
	<img src="{{ $base }}-900.jpg" alt="{{ $ph['alt'] }}" title="{{ $ph['title'] }} (photo : {{ $ph['credit'] }})" width="640" height="960" decoding="async" @if(!empty($ph['pos'])) style="object-position:{{ $ph['pos'] }}" @endif @if(!empty($lcp)) fetchpriority="high" @else loading="lazy" @endif>
</picture>
