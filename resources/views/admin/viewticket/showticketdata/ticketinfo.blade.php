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

																			@else
																				<span class="font-weight-semibold">~</span>
																			@endif
																		@else
																				<span class="font-weight-semibold">~</span>
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

																		@elseif($ticket->priority == "High")

																			<span class="badge badge-danger-light">{{ lang($ticket->priority)}}</span>

																		@elseif($ticket->priority == "Critical")

																			<span class="badge badge-danger-dark">{{ lang($ticket->priority)}}</span>

																		@else

																			<span class="badge badge-warning-light">{{ lang($ticket->priority) }}</span>

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
																		~
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
											</div>
