@include('errors.gn-min', [
	'gcCode' => '401',
	'gcPhoto' => '14',
	'gcHeading' => 'Connexion requise',
	'gcText' => 'Vous devez vous connecter pour ouvrir cette page.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
			['Se connecter', url('espace-client/connexion'), 'gn-btn--ghost'],
	],
])
