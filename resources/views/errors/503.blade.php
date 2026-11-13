@include('errors.gn-min', [
	'gcCode' => '503',
	'gcPhoto' => '18',
	'gcHeading' => 'Maintenance en cours',
	'gcText' => 'Le site est momentanément indisponible pendant une opération de maintenance. Revenez dans quelques minutes.',
	'gcActions' => [
			['Réessayer', url()->current(), 'gn-btn--light'],
	],
])
