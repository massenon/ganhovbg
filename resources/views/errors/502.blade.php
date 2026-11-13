@include('errors.gn-min', [
	'gcCode' => '502',
	'gcPhoto' => '18',
	'gcHeading' => 'Service momentanément indisponible',
	'gcText' => 'Le serveur a reçu une réponse invalide. Réessayez dans quelques instants.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
