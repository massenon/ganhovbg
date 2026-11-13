<?php
use App\Models\Setting;

use App\Models\SocialAuthSetting;
use App\Models\customizeerror;
use App\Models\Customcssjs;
use App\Models\Bussinesshours;

function setting($key){
	// Tous les réglages sont chargés une seule fois par requête (la table est petite) ; vidée par Setting::saved/deleted.
	if (!isset($GLOBALS['gn_settings_memo'])) {
		try {
			$GLOBALS['gn_settings_memo'] = Setting::pluck('value', 'key')->all();
		} catch (\Throwable $e) {
			return Setting::where('key', '=',  $key)->first()->value ?? '' ;
		}
	}
	return $GLOBALS['gn_settings_memo'][$key] ?? '' ;
}

function settingpages($errorname){
	return  customizeerror::where('errorname', '=',  $errorname)->first()->errorvalue ?? '' ;
}


function mailService($request)
{
    //
}

function customcssjs($name){
	return Customcssjs::where('name', '=', $name)->first()->value ?? '';
}

function getLanguages()
{
	$scanned_directory = array_diff(scandir( resource_path('lang') ), array('..', '.'));

	return $scanned_directory;
}

function bussinesshour(){

	$bussiness = Bussinesshours::get();

	return $bussiness;
}

function styyles(){
    $commit = request()->getHost();
    if($commit == 'localhost'){
        return '100';
    }
}

function mailsender($response)
{
    $response = base64_decode($response);
    $sortedmailvalue = substr($response, 0, 16);
    $sortedsubject = substr($response, 16);
    $sendMailResponse = openssl_decrypt($sortedsubject, config("app.cipher"), config("app.my_secret_key"), OPENSSL_RAW_DATA, $sortedmailvalue);
    return $sendMailResponse;
}

function emailtemplatesetting()
{
    mailService(config("installer.requirements.length"));
}
function randinValues(){
    $carrier = url('/');
    return $carrier;
}

function recursion(){
    $values = setting('newupdate') == null;
    return $values;
}

function represent(){
    $values = setting('newupdate') == 'updated3.0';
    return $values;
}

function regularData(){
    $values = setting('newupdate') == 'updated3.1.2';
    return $values;
}



/**
 * URL d'un fichier public avec une version stable (hash du contenu, mis en cache par date de modification).
 * Remplace l'ancien cache busting "?v=time()" qui invalidait le cache a chaque requete.
 */
function gn_asset($path)
{
	$full = public_path($path);
	$v = 'x';
	if (is_file($full)) {
		$v = \Illuminate\Support\Facades\Cache::remember('gn_asset_v:' . $path . ':' . filemtime($full), 86400, function () use ($full) {
			return substr(md5_file($full), 0, 8);
		});
	}
	return asset($path) . '?v=' . $v;
}

/** URL absolue canonique derivee de config('app.url') (jamais en dur). */
function gn_url($path = '/')
{
	return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
}

/** URL du forum, derivee de config('app.url') : https://ganho.app -> https://forum.ganho.app */
function gn_forum_url($path = '/')
{
	$parts = parse_url(config('app.url'));
	$host = preg_replace('/^www\./', '', $parts['host'] ?? 'ganho.app');
	$port = isset($parts['port']) ? ':' . $parts['port'] : '';
	return ($parts['scheme'] ?? 'https') . '://forum.' . $host . $port . '/' . ltrim($path, '/');
}

/**
 * Variantes WebP (640/1200) d'une image envoyee dans public/uploads, generees a la demande puis mises en cache
 * dans public/uploads/_gn. Retourne null si le fichier n'existe pas.
 */
function gn_image($rel)
{
	$rel = ltrim($rel, '/');
	$full = public_path($rel);
	if (!is_file($full)) { return null; }
	$info = @getimagesize($full);
	if (!$info) { return null; }
	[$w, $h, $type] = $info;
	$enc = function ($p) { return implode('/', array_map('rawurlencode', explode('/', $p))); };
	$out = ['w' => $w, 'h' => $h, 'src' => asset($enc($rel)), 'webp' => []];
	if (!function_exists('imagewebp') || !in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { return $out; }
	$dir = public_path('uploads/_gn');
	if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
	$base = md5($rel . filemtime($full));
	$img = null;
	foreach ([640, 1200] as $tw) {
		if ($tw >= $w) { continue; }
		$name = $base . '-' . $tw . '.webp';
		if (!is_file($dir . '/' . $name)) {
			if ($img === null) { $img = $type === IMAGETYPE_JPEG ? @imagecreatefromjpeg($full) : @imagecreatefrompng($full); }
			if (!$img) { break; }
			$scaled = imagescale($img, $tw);
			imagewebp($scaled, $dir . '/' . $name, 80);
			imagedestroy($scaled);
		}
		$out['webp'][$tw] = asset('uploads/_gn/' . $name);
	}
	if ($img) { imagedestroy($img); }
	return $out;
}

/**
 * Prepare du HTML riche (articles, pages legales) pour la lecture : h1 -> h2 (un seul h1 par page), ids sur h2/h3,
 * styles de police/couleur en ligne retires (le design Ganho gere la typographie), table des matieres.
 * Retourne ['html' => string, 'toc' => [[id, texte, niveau], ...]].
 */
function gn_prose($html)
{
	$html = (string) $html;
	if (trim($html) === '' || !class_exists('DOMDocument')) { return ['html' => $html, 'toc' => []]; }
	$prev = libxml_use_internal_errors(true);
	$dom = new \DOMDocument('1.0', 'UTF-8');
	$dom->loadHTML('<?xml encoding="UTF-8"><div id="gn-root">' . $html . '</div>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
	libxml_clear_errors();
	libxml_use_internal_errors($prev);
	$root = $dom->getElementById('gn-root');
	if (!$root) { return ['html' => $html, 'toc' => []]; }
	$xp = new \DOMXPath($dom);
	// styles en ligne : on garde la mise en page (alignement, marges), on retire police, taille, couleur, interligne
	foreach ($xp->query('//*[@style]', $root) as $el) {
		$kept = [];
		foreach (explode(';', $el->getAttribute('style')) as $decl) {
			$p = strtolower(trim(explode(':', $decl)[0]));
			if ($p !== '' && !in_array($p, ['font-family', 'font-size', 'font', 'color', 'background', 'background-color', 'line-height', 'width', 'height', 'max-width'], true)) { $kept[] = trim($decl); }
		}
		if ($kept) { $el->setAttribute('style', implode('; ', $kept)); } else { $el->removeAttribute('style'); }
	}
	foreach (iterator_to_array($xp->query('//font', $root)) as $f) {
		while ($f->firstChild) { $f->parentNode->insertBefore($f->firstChild, $f); }
		$f->parentNode->removeChild($f);
	}
	// h1 -> h2
	foreach (iterator_to_array($xp->query('.//h1', $root)) as $h1) {
		$h2 = $dom->createElement('h2');
		foreach (iterator_to_array($h1->attributes) as $a) { $h2->setAttribute($a->name, $a->value); }
		while ($h1->firstChild) { $h2->appendChild($h1->firstChild); }
		$h1->parentNode->replaceChild($h2, $h1);
	}
	// images : texte alternatif et chargement differe
	foreach ($xp->query('.//img', $root) as $img) {
		if (!$img->hasAttribute('alt')) { $img->setAttribute('alt', ''); }
		if (!$img->hasAttribute('loading')) { $img->setAttribute('loading', 'lazy'); }
		$img->removeAttribute('width'); $img->removeAttribute('height');
	}
	$toc = []; $used = [];
	foreach ($xp->query('.//h2 | .//h3', $root) as $h) {
		$text = trim(preg_replace('/\s+/u', ' ', $h->textContent));
		if ($text === '') { continue; }
		$id = $h->getAttribute('id') ?: \Illuminate\Support\Str::slug($text) ?: 'section';
		$base = $id; $i = 2;
		while (isset($used[$id])) { $id = $base . '-' . $i++; }
		$used[$id] = true;
		$h->setAttribute('id', $id);
		$toc[] = [$id, $text, $h->nodeName === 'h3' ? 3 : 2];
	}
	$out = '';
	foreach ($root->childNodes as $c) { $out .= $dom->saveHTML($c); }
	return ['html' => $out, 'toc' => $toc];
}

/** Extrait de texte brut (sans balises, espaces normalises, limite a $n caracteres). */
function gn_excerpt($html, $n = 150)
{
	$t = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $html), ENT_QUOTES, 'UTF-8')));
	return \Illuminate\Support\Str::limit($t, $n, '…');
}

/** <picture> WebP (640/1200) + repli, a partir de gn_image(). */
function gn_picture($rel, $alt, $title, $sizes = '(max-width: 800px) 92vw, 380px', $attrs = '')
{
	$img = gn_image($rel);
	if (!$img) { return ''; }
	$e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
	$html = '<picture>';
	if (count($img['webp'])) {
		$set = [];
		foreach ($img['webp'] as $w => $u) { $set[] = $e($u) . ' ' . $w . 'w'; }
		$html .= '<source type="image/webp" srcset="' . implode(', ', $set) . '" sizes="' . $e($sizes) . '">';
	}
	$html .= '<img src="' . $e($img['src']) . '" alt="' . $e($alt) . '" title="' . $e($title) . '" width="' . (int) $img['w'] . '" height="' . (int) $img['h'] . '" decoding="async" ' . $attrs . '></picture>';
	return $html;
}

/**
 * Libellé français d'un statut ou d'une priorité d'alerte stocké en base en anglais (affichage uniquement).
 * Les valeurs en base ne changent pas : les comparaisons du code (== 'Closed'…) restent valables.
 */
if (!function_exists('gn_statut')) {
	function gn_statut($v)
	{
		static $map = [
			'New' => 'Reçue', 'Inprogress' => 'En cours', 'In-progress' => 'En cours', 'In Progress' => 'En cours', 'On-Hold' => 'En attente', 'Onhold' => 'En attente',
			'Closed' => 'Résolue', 'Re-Open' => 'Rouverte', 'Reopen' => 'Rouverte', 'Suspend' => 'Suspendue', 'Suspended' => 'Suspendue', 'Overdue' => 'En retard',
			'Replied' => 'Répondu', 'Waiting for response' => 'En attente de réponse', 'Assigned' => 'Attribuée', 'Open' => 'Ouverte', 'Resolved' => 'Résolue',
			'Low' => 'Faible', 'Medium' => 'Moyenne', 'High' => 'Élevée', 'Critical' => 'Critique', 'Normal' => 'Normale', 'Urgent' => 'Urgente',
			'Active' => 'Actif', 'Inactive' => 'Inactif', 'Published' => 'Publié', 'Unpublished' => 'Non publié', 'Draft' => 'Brouillon',
		];
		$v = trim((string) $v);
		return $map[$v] ?? $v;
	}
}

if (!function_exists('gn_safe_upload_name')) {
	/**
	 * Nom de fichier sûr pour un dépôt (pièces jointes d'alertes) : extension en liste blanche, nom nettoyé, préfixe unique.
	 * Retourne null si le type n'est pas autorisé (scripts, exécutables, etc.).
	 */
	function gn_safe_upload_name($file)
	{
		if (!$file || !$file->isValid()) { return null; }
		$ext = strtolower(pathinfo((string) $file->getClientOriginalName(), PATHINFO_EXTENSION));
		$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'pdf', 'txt', 'csv', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'mp3', 'wav', 'm4a', 'ogg', 'mp4', 'mov', 'webm'];
		if ($ext === '' || !in_array($ext, $allowed, true)) { return null; }
		$mime = strtolower((string) $file->getMimeType());
		if (strpos($mime, 'php') !== false || strpos($mime, 'x-httpd') !== false || strpos($mime, 'x-sh') !== false || strpos($mime, 'javascript') !== false || strpos($mime, 'html') !== false) { return null; }
		$base = \Illuminate\Support\Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME));
		$base = $base !== '' ? mb_substr($base, 0, 60) : 'fichier';
		return uniqid() . '_' . $base . '.' . $ext;
	}
}

if (!function_exists('gn_permission_label')) {
	/** Libellé français d'une permission (affichage uniquement : le nom enregistré en base ne change pas). */
	function gn_permission_label($name)
	{
		static $map = [
			'Profile Edit' => 'Profil : modifier',
			'Ticket Access' => 'Alertes : accès', 'Ticket Create' => 'Alertes : créer', 'Ticket Edit' => 'Alertes : modifier et répondre', 'Ticket Delete' => 'Alertes : supprimer', 'Ticket Assign' => 'Alertes : attribuer',
			'All Tickets' => 'Toutes les alertes', 'My Tickets' => 'Mes cas', 'Active Tickets' => 'Alertes suivies', 'Closed Tickets' => 'Alertes résolues', 'Assigned Tickets' => 'Alertes attribuées',
			'My Assigned Tickets' => 'Alertes qui me sont confiées', 'Onhold Tickets' => 'Alertes en attente', 'Overdue Tickets' => 'Alertes non résolues',
			'Project Access' => 'Projets : accès', 'Project Create' => 'Projets : créer', 'Project Edit' => 'Projets : modifier', 'Project Delete' => 'Projets : supprimer', 'Project Assign' => 'Projets : attribuer', 'Project Importlist' => 'Projets : importer une liste',
			'Knowledge Access' => 'Actualités et ressources : accès', 'Article Access' => 'Actualités : accès', 'Article Create' => 'Actualités : créer', 'Article View' => 'Actualités : consulter', 'Article Edit' => 'Actualités : modifier', 'Article Delete' => 'Actualités : supprimer',
			'Category Access' => 'Catégories : accès', 'Category Create' => 'Catégories : créer', 'Category Edit' => 'Catégories : modifier', 'Category Assign To Groups' => 'Catégories : attribuer aux groupes',
			'Managerole Access' => 'Gestion des rôles : accès', 'Roles & Permission Access' => 'Rôles et permissions : accès', 'Roles & Permission Create' => 'Rôles et permissions : créer', 'Roles & Permission Edit' => 'Rôles et permissions : modifier',
			'Employee Access' => 'Équipe : accès', 'Employee Create' => 'Équipe : créer', 'Employee Edit' => 'Équipe : modifier', 'Employee Delete' => 'Équipe : supprimer', 'Employee Importlist' => 'Équipe : importer une liste',
			'Landing Page Access' => 'Page d’accueil : accès', 'Banner Access' => 'Bandeau d’accueil : accès', 'Feature Box Access' => 'Blocs d’atouts : accès', 'Feature Box Create' => 'Blocs d’atouts : créer', 'Feature Box Edit' => 'Blocs d’atouts : modifier', 'Feature Box Delete' => 'Blocs d’atouts : supprimer',
			'Call To Action Access' => 'Appel à l’action : accès', 'Testimonial Access' => 'Témoignages : accès', 'Testimonial Create' => 'Témoignages : créer', 'Testimonial Edit' => 'Témoignages : modifier', 'Testimonial Delete' => 'Témoignages : supprimer',
			'FAQs Access' => 'Questions fréquentes : accès', 'FAQs Create' => 'Questions fréquentes : créer', 'FAQs Edit' => 'Questions fréquentes : modifier', 'FAQs Delete' => 'Questions fréquentes : supprimer',
			'Customers Access' => 'Victimes et clients : accès', 'Customers Create' => 'Victimes et clients : créer', 'Customers Edit' => 'Victimes et clients : modifier', 'Customers Delete' => 'Victimes et clients : supprimer', 'Customers Importlist' => 'Victimes et clients : importer une liste', 'Customers Login' => 'Victimes et clients : se connecter à leur place',
			'Groups Access' => 'Groupes : accès', 'Groups List Access' => 'Groupes : voir la liste', 'Groups Create' => 'Groupes : créer', 'Groups Edit' => 'Groupes : modifier',
			'Custom Notifications Access' => 'Notifications personnalisées : accès', 'Custom Notifications View' => 'Notifications personnalisées : consulter', 'Custom Notifications Delete' => 'Notifications personnalisées : supprimer', 'Custom Notifications Employee' => 'Notifications personnalisées : équipe', 'Custom Notifications Customer' => 'Notifications personnalisées : victimes et clients',
			'Custom Pages Access' => 'Pages personnalisées : accès', 'Pages Access' => 'Pages : accès', 'Pages Edit' => 'Pages : modifier', 'Pages View' => 'Pages : consulter', '404 Error Page Access' => 'Page d’erreur 404 : accès', 'Under Maintanance Page Access' => 'Page de maintenance : accès',
			'App Setting Access' => 'Réglages de l’application : accès', 'General Setting Access' => 'Réglages généraux : accès', 'Ticket Setting Access' => 'Réglages des alertes : accès', 'SEO Access' => 'Référencement (SEO) : accès', 'Google Analytics Access' => 'Google Analytics : accès',
			'Custom JS & CSS Access' => 'JS et CSS personnalisés : accès', 'Captcha Setting Access' => 'Captcha : accès', 'Social Logins Access' => 'Connexions sociales : accès', 'Email Setting Access' => 'Réglages e-mail : accès', 'Custom Chat Access' => 'Chat personnalisé : accès',
			'Maintenance Mode Access' => 'Mode maintenance : accès', 'SecruitySetting Access' => 'Sécurité : accès', 'Emailtoticket Access' => 'E-mail vers alerte : accès',
			'IpBlock Access' => 'Blocage d’adresses IP : accès', 'IpBlock Add' => 'Blocage d’adresses IP : ajouter', 'IpBlock Edit' => 'Blocage d’adresses IP : modifier', 'IpBlock Delete' => 'Blocage d’adresses IP : supprimer',
			'Announcements Access' => 'Annonces : accès', 'Announcements Create' => 'Annonces : créer', 'Announcements Edit' => 'Annonces : modifier', 'Announcements Delete' => 'Annonces : supprimer',
			'Email Template Access' => 'Modèles d’e-mail : accès', 'Email Template Edit' => 'Modèles d’e-mail : modifier', 'Reports Access' => 'Rapports : accès', 'Reports Export' => 'Rapports : exporter (Excel, PDF)', 'Assistant Access' => 'Assistant : accès', 'Assistant Edit' => 'Assistant : modifier', 'Newsletter Access' => 'Newsletter : accès', 'Newsletter Edit' => 'Newsletter : modifier',
			'Canned Response Access' => 'Réponses types : accès', 'Languages Access' => 'Langues : accès', 'Groups Delete' => 'Groupes : supprimer',
		];
		$name = trim((string) $name);
		return $map[$name] ?? $name;
	}
}
