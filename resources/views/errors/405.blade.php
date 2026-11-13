@include('errors.gn-min', [
	'gcCode' => '405',
	'gcPhoto' => '15',
	'gcHeading' => 'Action non autorisée',
	'gcText' => 'Cette action n’est pas possible depuis cette page. Retournez à l’accueil et recommencez.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
