@extends('errors.gn-lean')
@php
	$gcCode = '404';
	$gcPhoto = '13';
	$gcHeading = 'Cette page est introuvable';
	$gcText = 'L’adresse est peut-être incorrecte ou la page a été déplacée. Retrouvez votre chemin avec les liens ci-dessous.';
	$gcLinks = true;
	$gcActions = [
		['Retour à l’accueil', url('/'), 'gn-btn--light'],
		['Créer une alerte VBG', (setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('espace-client/connexion')), 'gn-btn--ghost'],
	];
@endphp
