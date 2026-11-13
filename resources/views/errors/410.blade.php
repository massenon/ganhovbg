@include('errors.gn-min', [
	'gcCode' => '410',
	'gcPhoto' => '13',
	'gcHeading' => 'Cette page n’existe plus',
	'gcText' => 'Le contenu a été retiré définitivement. Retrouvez votre chemin depuis l’accueil.',
	'gcActions' => [
			['Retour à l’accueil', url('/'), 'gn-btn--light'],
	],
])
