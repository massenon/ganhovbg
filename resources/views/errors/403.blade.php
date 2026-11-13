@extends('errors.gn-lean')
@php
	$gcCode = '403';
	$gcPhoto = '14';
	$gcHeading = 'Accès refusé';
	$gcText = 'Vous n’avez pas l’autorisation d’ouvrir cette page. Si vous pensez qu’il s’agit d’une erreur, connectez-vous ou écrivez à l’équipe GANXÔ.';
	$gcLinks = true;
	$gcActions = [
		['Retour à l’accueil', url('/'), 'gn-btn--light'],
		['Se connecter', url('espace-client/connexion'), 'gn-btn--ghost'],
	];
@endphp
