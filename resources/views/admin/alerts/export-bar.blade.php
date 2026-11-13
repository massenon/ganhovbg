{{-- Barre d'export des alertes : deux boutons toujours visibles + options (statut, catégorie, période, recherche). --}}
@can('Reports Export')
@php
	$gnExpCats = \App\Services\AlertReportService::categories();
	$gnExpStatuts = ['New', 'Inprogress', 'Onhold', 'Closed', 'Re-Open', 'Suspend'];
@endphp
<div class="page-rightheader ms-md-auto gn-export">
	<form method="GET" action="{{ route('admin.alerts.export.xlsx') }}" id="gn-export-form" class="d-flex flex-wrap gap-2 align-items-center" data-no-spa>
		<button type="submit" class="btn btn-primary" formaction="{{ route('admin.alerts.export.xlsx') }}" data-no-spa title="Télécharger la liste des alertes au format Excel (.xlsx)">
			<i class="fe fe-download" aria-hidden="true"></i> Exporter en Excel (.xlsx)
		</button>
		<button type="submit" class="btn btn-outline-primary" formaction="{{ route('admin.alerts.report.pdf') }}" data-no-spa title="Télécharger le rapport statistique au format PDF">
			<i class="fe fe-file-text" aria-hidden="true"></i> Rapport statistiques (PDF)
		</button>
		<button type="button" class="btn btn-outline-light" data-bs-toggle="collapse" data-bs-target="#gn-export-options" aria-expanded="false" aria-controls="gn-export-options">
			<i class="fe fe-sliders" aria-hidden="true"></i> Options
		</button>

		<div class="collapse w-100" id="gn-export-options">
			<div class="card card-body mt-2 mb-0 gn-export__opts">
				<div class="row g-2">
					<div class="col-md-3 col-6">
						<label class="form-label" for="gn-exp-periode">Période</label>
						<select name="periode" id="gn-exp-periode" class="form-select">
							<option value="tout">Toute la période (Excel)</option>
							<option value="7j">7 derniers jours</option>
							<option value="30j" selected>30 derniers jours</option>
							<option value="90j">90 derniers jours</option>
							<option value="annee">Cette année</option>
							<option value="perso">Personnalisée…</option>
						</select>
					</div>
					<div class="col-md-2 col-6"><label class="form-label" for="gn-exp-du">Du</label><input type="date" name="du" id="gn-exp-du" class="form-control"></div>
					<div class="col-md-2 col-6"><label class="form-label" for="gn-exp-au">Au</label><input type="date" name="au" id="gn-exp-au" class="form-control"></div>
					<div class="col-md-2 col-6">
						<label class="form-label" for="gn-exp-statut">Statut</label>
						<select name="statut" id="gn-exp-statut" class="form-select">
							<option value="">Tous</option>
							@foreach($gnExpStatuts as $s)<option value="{{ $s }}">{{ gn_statut($s) }}</option>@endforeach
						</select>
					</div>
					<div class="col-md-3 col-6">
						<label class="form-label" for="gn-exp-cat">Catégorie</label>
						<select name="categorie" id="gn-exp-cat" class="form-select">
							<option value="">Toutes</option>
							@foreach($gnExpCats as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
						</select>
					</div>
					<div class="col-12"><label class="form-label" for="gn-exp-q">Recherche (numéro ou objet)</label><input type="search" name="q" id="gn-exp-q" class="form-control" placeholder="SPG-24, harcèlement…"></div>
				</div>
				<p class="text-muted mt-2 mb-0 fs-12">L’export Excel respecte ces options (« Toute la période » = sans limite de dates). Le rapport PDF utilise la période choisie (30 jours par défaut). Vous n’exportez que les cas auxquels vous avez accès. Données sensibles : ne pas diffuser.</p>
			</div>
		</div>
	</form>
</div>
@endcan
