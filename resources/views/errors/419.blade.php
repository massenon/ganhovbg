@extends('errors.gn-lean')
@php
	$gcCode = '419';
	$gcPhoto = '14';
	$gcHeading = 'Votre session a expiré';
	$gcText = 'Pour votre sécurité, le formulaire n’a pas été envoyé. Revenez en arrière, rechargez la page, puis recommencez.';
	$gcLinks = false;
	$gcActions = [
		['Revenir en arrière', url()->previous(), 'gn-btn--light'],
		['Retour à l’accueil', url('/'), 'gn-btn--ghost'],
	];
@endphp
