@include('errors.gn-min', [
	'gcCode' => '504',
	'gcPhoto' => '18',
	'gcHeading' => 'Le serveur met trop de temps à répondre',
	'gcText' => 'Réessayez dans quelques instants. Si le problème continue, revenez plus tard.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
