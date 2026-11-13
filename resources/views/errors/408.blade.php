@include('errors.gn-min', [
	'gcCode' => '408',
	'gcPhoto' => '18',
	'gcHeading' => 'Délai dépassé',
	'gcText' => 'La demande a mis trop de temps à arriver. Vérifiez votre connexion puis réessayez.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
