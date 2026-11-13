<?php
// Libellés français et étapes de suivi d'une alerte (espace client). Utilisation : extract(include resource_path('views/includes/user/gnmaps.php'));
return [
	'gnStatut' => ['New' => 'Reçue', 'On-Hold' => 'En pause', 'Inprogress' => 'Prise en charge', 'Re-Open' => 'Rouverte', 'Closed' => 'Clôturée'],
	'gnStatutCls' => ['New' => 'warn', 'On-Hold' => 'warn', 'Inprogress' => 'info', 'Re-Open' => 'info', 'Closed' => 'ok'],
	'gnPrio' => ['Low' => 'Faible', 'Medium' => 'Moyenne', 'High' => 'Élevée', 'Critical' => 'Critique'],
	// étape atteinte : 1 reçue, 2 intervenant·e assigné·e, 3 accompagnement en cours, 4 résolue
	'gnEtape' => function ($t) {
		if ($t->status === 'Closed') { return 4; }
		if (in_array($t->status, ['Inprogress', 'Re-Open'], true)) { return $t->toassignuser_id ? 3 : 2; }
		return $t->toassignuser_id ? 2 : 1;
	},
	'gnSteps' => ['Alerte reçue', 'Intervenant·e assigné·e', 'Accompagnement en cours', 'Résolue'],
];
