<div class="card">
	<div class="card-header  border-0">
		<div class="card-title">{{lang('Arlete VBG Information')}}</div>
	</div>
	<div class="card-body pt-2 ps-0 pe-0 pb-0">
		<div class="table-responsive tr-lastchild">
			<table class="table mb-0 table-information">
				<tbody>

					<tr>
						<td>
							<span class="w-50">{{lang('Arlete ID')}}</span>
						</td>
						<td>:</td>
						<td>
							<span class="font-weight-semibold">#{{ $ticket->ticket_id }}</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="w-50">{{lang('Catégorie')}}</span>
						</td>
						<td>:</td>
						<td>

							@if($ticket->category_id != null)
								@if($ticket->category != null)

								    <span class="font-weight-semibold">{{ $ticket->category->name}}</span>
									@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')

										<a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 sprukocategory border border-primary br-7 text-white bg-primary ms-2"> <i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Change Category')}}"></i></a>


									@endif
								@else
									@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
									<a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 border border-primary br-7 text-white bg-primary" > <i class="feather feather-plus" data-toggle="tooltip" data-bs-placement="top" title="{{lang('Add Category')}}"></i></a>
									@endif
								@endif
							@else
								@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
									<a href="javascript:void(0)" data-id="{{$ticket->ticket_id}}" class="p-1 border border-primary br-7 text-white bg-primary" > <i class="feather feather-plus" data-toggle="tooltip" data-bs-placement="top" title="{{lang('Add Category')}}"></i></a>
								@endif
							@endif

						</td>
					</tr>
					@if ($ticket->subcategory != null)
					<tr>
						<td>
							<span class="w-50">{{lang('Sous-catégorie')}}</span>
						</td>
						<td>:</td>
						<td>
							<span class="font-weight-semibold">{{$ticket->subcategoriess->subcategoryname}}</span>

						</td>
					</tr>
					@endif

					@if ($ticket->project != null)

					<tr>
						<td>
							<span class="w-50">{{lang('Project')}}</span>
						</td>
						<td>:</td>
						<td>
							<span class="font-weight-semibold">{{ $ticket->project }}</span>
						</td>
					</tr>
					@endif
					@if($ticket->priority != null)
					<tr>
						<td>
							<span class="w-50">{{lang('Priorité')}}</span>
						</td>
						<td>:</td>
						<td id="priorityid">
							@if($ticket->priority == "Low")

								<span class="badge badge-success-light" >{{ lang($ticket->priority) }}</span>
								@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
									<a href="javascript:void(0)"  id="priority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
										<i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Changer la priorité" aria-label="Ajouter une priorité"></i>
									</a>
								@endif
							@elseif($ticket->priority == "High")

								<span class="badge badge-danger-light">{{ lang($ticket->priority)}}</span>
								@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
								<a href="javascript:void(0)"  id="priority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
									<i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Changer la priorité" aria-label="Ajouter une priorité"></i>
								</a>
								@endif
							@elseif($ticket->priority == "Critical")

								<span class="badge badge-danger-dark">{{ lang($ticket->priority)}}</span>
								@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
									<a href="javascript:void(0)"  id="priority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
										<i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Changer la priorité" aria-label="Ajouter une priorité"></i>
									</a>
								@endif
							@else

								<span class="badge badge-warning-light">{{ lang($ticket->priority) }}</span>
								@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
									<a href="javascript:void(0)"  id="priority" class="p-1 border border-primary br-7 text-white bg-primary ms-2">
										<i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Changer la priorité" aria-label="Ajouter une priorité"></i>
									</a>
								@endif
							@endif
						</td>
					</tr>
					@else

					<tr>
						<td>
							<span class="w-50">{{lang('Priorité')}}</span>
						</td>
						<td>:</td>
						<td id="priorityid">
							@if ($ticket->status != 'Closed' && $ticket->status != 'Suspend')
							<a href="javascript:void(0)"  id="priority" class="p-1 border border-primary br-7 text-white bg-primary">
								<i class="feather feather-plus" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Changer la priorité" aria-label="Ajouter une priorité"></i>
							</a>
							@endif
						</td>
					</tr>
					@endif

					<tr>
						<td>
							<span class="w-50">{{lang('Open Date')}}</span>
						</td>
						<td>:</td>
						<td>
							<span class="font-weight-semibold">{{ $ticket->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('date_format'))}}</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="w-50">{{lang('Status')}}</span>
						</td>
						<td>:</td>
						<td>
							@if($ticket->status == "New")

							<span class="badge badge-burnt-orange">{{ lang($ticket->status) }}</span>
							@elseif($ticket->status == "Re-Open")

							<span class="badge badge-teal">{{ lang($ticket->status) }}</span>
							@elseif($ticket->status == "Inprogress")

							<span class="badge badge-info">{{ lang($ticket->status) }}</span>
							@elseif($ticket->status == "On-Hold")

							<span class="badge badge-warning">{{ lang($ticket->status) }}</span>
							@else

							<span class="badge badge-danger">{{ lang($ticket->status) }}</span>
							@endif

						</td>
					</tr>
					@if($ticket->replystatus != null && $ticket->replystatus == "Solved" || $ticket->replystatus == "Unanswered" || $ticket->replystatus == "Waiting for response")

					<tr>
						<td>
							<span class="w-50">{{lang('Reply Status')}}</span>
						</td>
						<td>:</td>
						<td>
							@if($ticket->replystatus == "Solved")

                                <span class="badge badge-success">{{ lang($ticket->replystatus) }}</span>

							@elseif($ticket->replystatus == "Unanswered")

                                <span class="badge badge-danger-light">{{ lang($ticket->replystatus) }}</span>

							@elseif($ticket->replystatus == "Waiting for response")

                                <span class="badge badge-warning">{{ lang($ticket->replystatus) }}</span>

							@else
							@endif

						</td>
					</tr>
					@endif

					@php $customfields = $ticket->ticket_customfield()->get(); @endphp
					@if($customfields->isNotEmpty())
					@foreach ($customfields as $customfield)
					@if($customfield->fieldtypes != 'textarea')
						@if($customfield->privacymode == '1')
							@php
								$extrafiels = decrypt($customfield->values);
							@endphp
							<tr>
								<td>{{$customfield->fieldnames}}</td>
								<td>: </td>
								<td>{{$extrafiels}} </td>
							</tr>
						@else

							<tr>
								<td>{{$customfield->fieldnames}}</td>
								<td>:</td>
								<td>{{$customfield->values}} </td>
							</tr>

						@endif
					@endif
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<div class="card-footer ticket-buttons">
		@if($ticket->status == 'Closed')

			<button class="btn btn-secondary my-1" id="reopen" data-id="{{$ticket->id}}"> <i class="feather feather-rotate-ccw"></i> {{lang('Re-ouvrir')}}</button>

		@endif
		@can('Ticket Assign')
				@if($ticket->status == 'Suspend' || $ticket->status == 'Closed')
					<div class="btn-group">
						@if($ticket->ticketassignmutliples->isNotEmpty() && $ticket->selfassignuser_id == null)

						<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" disabled>{{lang('Multi Assign')}} <span class="caret"></span></button>
						<button data-id="{{$ticket->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
						@elseif($ticket->ticketassignmutliples->isEmpty() && $ticket->selfassignuser_id != null)

						<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{$ticket->selfassign->name}} (self) <span class="caret"></span></button>
						<button data-id="{{$ticket->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
						@else

						<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{lang('Attribuer')}}<span class="caret"></span></button>
						@endif

					</div>
				@else
					@if($ticket->ticketassignmutliples->isEmpty() && $ticket->selfassignuser_id == null)

						<div class="btn-group">
							<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
							<ul class="dropdown-menu" role="menu">
								<li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
								<li>
									<a href="javascript:void(0);" id="selfassigid" data-id="{{$ticket->id}}">{{lang('Self Assign')}}</a>
								</li>
								<li>
									<a href="javascript:void(0)" data-id="{{$ticket->id}}" id="assigned">
									{{lang('Attribuer à un prestataire')}}
									</a>
								</li>
							</ul>
						</div>
					@else
						<div class="btn-group">
							@if($ticket->ticketassignmutliples->isNotEmpty() && $ticket->selfassignuser_id == null)
								@if($ticket->ticketassignmutliples->isEmpty() && $ticket->selfassign == null)
								<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
								@else
								<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Multi Assign')}} <span class="caret"></span></button>
								<a href="javascript:void(0)" data-id="{{$ticket->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
								@endif

							@elseif($ticket->ticketassignmutliples->isEmpty() && $ticket->selfassignuser_id != null)
							@if($ticket->ticketassignmutliples->isEmpty() && $ticket->selfassign == null)
							<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
							@else
							<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{$ticket->selfassign->name}} (self) <span class="caret"></span></button>
							<a href="javascript:void(0)" data-id="{{$ticket->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
							@endif
							@else

							<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
							@endif

							<ul class="dropdown-menu" role="menu">
								<li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
								<li>
									<a href="javascript:void(0);" id="selfassigid" data-id="{{$ticket->id}}">{{lang('Self Assign')}}</a>
								</li>
								<li>
									<a href="javascript:void(0)" data-id="{{$ticket->id}}" id="assigned">
									{{lang('Attribuer à un prestataire')}}
									</a>
								</li>
							</ul>
						</div>

					@endif
				@endif
			@endcan
	</div>
</div>
