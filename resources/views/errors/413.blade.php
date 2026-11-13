@include('errors.gn-min', [
	'gcCode' => '413',
	'gcPhoto' => '15',
	'gcHeading' => 'Fichier trop volumineux',
	'gcText' => 'Le fichier envoyé dépasse la taille autorisée. Choisissez un fichier plus léger et recommencez.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
