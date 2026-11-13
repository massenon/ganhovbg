@extends('errors.gn-lean')
@php
	$gcCode = '429';
	$gcPhoto = '14';
	$gcHeading = 'Trop de demandes';
	$gcText = 'Vous avez envoyé trop de demandes en peu de temps. Patientez une minute puis réessayez.';
	$gcLinks = false;
	$gcActions = [
		['Retour à l’accueil', url('/'), 'gn-btn--light'],
	];
@endphp
