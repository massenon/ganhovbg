@include('errors.gn-min', [
	'gcCode' => '400',
	'gcPhoto' => '15',
	'gcHeading' => 'Demande incorrecte',
	'gcText' => 'Le serveur n’a pas compris la demande. Revenez à la page précédente et réessayez.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
