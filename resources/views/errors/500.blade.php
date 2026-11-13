@include('errors.gn-min', [
	'gcCode' => '500',
	'gcPhoto' => '18',
	'gcHeading' => 'Une erreur est survenue de notre côté',
	'gcText' => 'Ce n’est pas votre faute. Réessayez dans quelques instants. Si le problème continue, revenez plus tard.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
