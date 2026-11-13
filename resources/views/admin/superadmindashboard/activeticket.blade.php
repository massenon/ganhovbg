@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />


		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Total Alertes VBG suivies')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->


							<!--- Ticket List Blocks --->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="row">
									<div class="col-12 col-sm-6 col-lg-3">
										<div class="card">
											<a href="{{route('admin.activeticket.allactiveinprogresstickets')}}" class="admintickets"></a>
											<div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
												<div>
													<p class="fs-13 mb-1 font-weight-semibold">{{lang('En cours')}}</p>
													<h4 class="mb-0">{{$allactiveinprogresstickets}}
														<span class="text-muted fs-11">
															<span class="text-danger fs-11 mt-2 me-1">
																<i class="feather feather-arrow-up-right me-1 bg-danger-transparent p-1 brround"></i>
																{{$allactiveoverduetickets}}
															</span>
															{{lang('En retard')}}
														</span>
													</h4>
												</div>
												<div class="ticket-state-svg">
													<svg xmlns="http://www.w3.org/2000/svg" class="svg-secondary" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path opacity="0.2" d="M15.87793,9.53613l0.65771-0.65723C17.47644,7.94305,18.00372,6.66974,18,5.34277V2c0.00012-0.55212-0.44733-0.99988-0.99945-1C17.00037,1,17.00018,1,17,1H7C6.44788,0.99988,6.00012,1.44733,6,1.99945C6,1.99963,6,1.99982,6,2v3.34277C5.9964,6.6701,6.52386,7.94373,7.46484,8.87988l0.65674,0.65527C8.68597,10.09686,9.00232,10.86096,9,11.65723v1.00977c-0.00146,0.64874-0.21167,1.27979-0.59961,1.7998l-1.40088,1.86719C6.35272,17.20032,6.00226,18.25189,6,19.33301V22c-0.00012,0.55212,0.44733,0.99988,0.99945,1C6.99963,23,6.99982,23,7,23h2c-0.55212,0.00012-0.99988-0.44733-1-0.99945C8,22.00037,8,22.00018,8,22v-2.66699c-0.00024-0.56793,0.0965-1.13171,0.28613-1.66699C8.42761,17.26672,8.80536,16.99988,9.229,17H14.771c0.42365-0.00012,0.80139,0.26672,0.94287,0.66602C15.9035,18.20129,16.00024,18.76508,16,19.33301V22c0.00012,0.55212-0.44733,0.99988-0.99945,1H17c0.55212,0.00012,0.99988-0.44733,1-0.99945c0-0.00018,0-0.00037,0-0.00055v-2.66699c-0.0022-1.08118-0.35284-2.13287-1-2.99902l-1.3999-1.86719c-0.38824-0.51984-0.59869-1.15094-0.6001-1.7998v-1.00977C14.99756,10.86133,15.31372,10.09753,15.87793,9.53613z M14.53564,8.87891l-0.65771,0.65723C13.3158,10.09784,13,10.85986,13,11.65454V12h-2v-0.34277c0-0.79742-0.31726-1.56213-0.88177-2.12543L9.46484,8.87988C8.69171,8.10162,8.19904,7.08862,8.06415,6h7.8717C15.80115,7.08832,15.30859,8.10101,14.53564,8.87891z"/><path d="M13,12v-0.34547c0-0.79464,0.31582-1.55671,0.87793-2.1184l0.65771-0.65722C15.3086,8.10102,15.80114,7.08832,15.93585,6h-7.8717c0.13488,1.08865,0.62757,2.10165,1.40069,2.87988l0.6534,0.65194C10.68275,10.09508,11,10.85977,11,11.65723V12H13z"/><path opacity="0.2" d="M19,3H5C4.44772,3,4,2.55228,4,2s0.44772-1,1-1h14c0.55228,0,1,0.44772,1,1S19.55228,3,19,3z"/><path d="M15,23H9c-0.55214,0.00014-0.99986-0.44734-1-0.99948C8,22.00035,8,22.00017,8,22v-2.667c-0.00026-0.56791,0.09651-1.13169,0.28613-1.667C8.42762,17.2667,8.80537,16.99987,9.229,17h5.542c0.42363-0.00013,0.80138,0.2667,0.94287,0.666c0.18962,0.53531,0.28639,1.09909,0.28613,1.667V22c0.00014,0.55214-0.44734,0.99986-0.99948,1C15.00035,23,15.00017,23,15,23z"/><path opacity="0.2" d="M19,23H5c-0.55228,0-1-0.44772-1-1s0.44772-1,1-1h14c0.55228,0,1,0.44772,1,1S19.55228,23,19,23z"/></svg>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-6 col-lg-3">
										<div class="card">
											<a href="{{route('admin.activeticket.allactivereopentickets')}}" class="admintickets"></a>
											<div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
												<div>
													<p class="fs-13 mb-1 font-weight-semibold">{{lang('Re-ouvrir')}}</p>
													<h4 class="mb-0">{{$allactivereopentickets}}</h4>
												</div>
												<div class="ticket-state-svg">
													<svg xmlns="http://www.w3.org/2000/svg" class="svg-danger" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" opacity="0.2"/><path d="M10 17a.99943.99943 0 0 1-1-1V8a1 1 0 0 1 2 0v8A.99943.99943 0 0 1 10 17zM14 17a.99943.99943 0 0 1-1-1V8a1 1 0 0 1 2 0v8A.99943.99943 0 0 1 14 17z"/></svg>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-6 col-lg-3">
										<div class="card">
											<a href="{{route('admin.activeticket.allactiveonholdtickets')}}" class="admintickets"></a>
											<div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
												<div>
													<p class="fs-13 mb-1 font-weight-semibold">{{lang('En attente')}}</p>
													<h4 class="mb-0">{{$allactiveonholdtickets}}</h4>
												</div>
												<div class="ticket-state-svg">
													<svg xmlns="http://www.w3.org/2000/svg"  class="svg-info" viewBox="0 0 24 24"><path d="M8.625,8.5h-4.5a.99943.99943,0,0,1-1-1V3a1,1,0,0,1,2,0V6.5h3.5a1,1,0,0,1,0,2Z"/><path d="M21 13a.99943.99943 0 0 1-1-1A7.995 7.995 0 0 0 5.0791 8.001.99981.99981 0 0 1 3.34863 6.999 9.99473 9.99473 0 0 1 22 12 .99943.99943 0 0 1 21 13zM19.875 22a.99943.99943 0 0 1-1-1V17.5h-3.5a1 1 0 0 1 0-2h4.5a.99943.99943 0 0 1 1 1V21A.99943.99943 0 0 1 19.875 22z"/><path opacity="0.2" d="M12,22A10.01177,10.01177,0,0,1,2,12a1,1,0,0,1,2,0,7.995,7.995,0,0,0,14.9209,3.999.99981.99981,0,0,1,1.73047,1.002A10.03228,10.03228,0,0,1,12,22Z"/></svg>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-6 col-lg-3">
										<div class="card">
											<a href="{{route('admin.activeticket.allactiveassignedtickets')}}" class="admintickets"></a>
											<div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
												<div>
													<p class="fs-13 mb-1 font-weight-semibold">{{lang('Attribué')}}</p>
													<h4 class="mb-0">{{$allactiveassignedtickets}}</h4>
												</div>
												<div class="ticket-state-svg">
													<svg xmlns="http://www.w3.org/2000/svg" class="svg-orange" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" opacity="0.2"/><circle cx="12" cy="16" r="1"/><path d="M12,13a1,1,0,0,1-1-1V8a1,1,0,0,1,2,0v4A1,1,0,0,1,12,13Z"/></svg>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<!--- End Ticket List Blocks --->


							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Total Alertes VBG suivies')}}</h4>
									</div>
									<div class="card-body overflow-scroll">
										<div class="spruko-delete">
											@can('Ticket Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan
											<div>
												<table
													class="table table-bordered border-bottom text-nowrap  ticketdeleterow supportticket-list w-100"
													id="activeticket">
													<thead>
														<tr>
															<th class="wpx-40 text-center">{{lang('Sl.No')}}</th>
															@can('Ticket Delete')

															<th class="wpx-40 text-center">
																<input type="checkbox"  id="customCheckAll">
																<label  for="customCheckAll"></label>
															</th>
															@endcan
															@cannot('Ticket Delete')

															<th class="wpx-40 text-center">
																<input type="checkbox"  id="customCheckAll" disabled>
																<label  for="customCheckAll"></label>
															</th>
															@endcannot

															<th >{{lang('Détails Arlette')}}</th>
															<th>{{lang('Victime')}}</th>
															<th>{{lang('Status')}}</th>
															<th >{{lang('Assign To')}}</th>
															<th >{{lang('Actions')}}</th>
														</tr>
													</thead>
													<tbody id="refresh">
														@php $i = 1; @endphp
														@foreach ($allactivetickets as $tickets)

														<tr {{$tickets->replystatus == 'Replied'? 'class=bg-success-transparent': ''}}>
															<td class="wpx-40 text-center">
																{{$i++}}
															</td>
															<td class="wpx-40 text-center">
																@if(Auth::user()->can('Ticket Delete'))
																	<input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$tickets->id}}" />
																@else
																	<input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$tickets->id}}" disabled />
																@endif
															</td>
															<td class="overflow-hidden ticket-details">
																<div class="d-flex align-items-center">
																	<div class="">
																		@if($tickets->ticketnote->isEmpty())
																			@if($tickets->overduestatus != null)

																			<div class="ribbon ribbon-top-right1 text-danger">
																				<span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
																			</div>

																			@endif
																		@else

																			<div class="ribbon ribbon-top-right text-warning">
																				<span class="bg-warning text-white">{{lang('Note')}}</span>
																			</div>

																			@if($tickets->overduestatus != null)
																			<div class="ribbon ribbon-top-right1 text-danger">
																				<span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
																			</div>
																			@endif

																		@endif

																		<a href="{{url('admin/ticket-view/' . $tickets->ticket_id)}}" class="fs-14 d-block font-weight-semibold">{{$tickets->subject}}</a>

																		<ul class="fs-13 font-weight-normal d-flex custom-ul">
																			<li class="pe-2 text-muted">#{{$tickets->ticket_id}}</span>
																			<li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i> {{$tickets->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('date_format'))}}</li>

																			@if($tickets->priority != null)
																				@if($tickets->priority == "Low")
																					<li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

																				@elseif($tickets->priority == "High")
																					<li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

																				@elseif($tickets->priority == "Critical")
																					<li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

																				@else
																					<li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>
																				@endif
																			@else
																				~
																			@endif

																			@if($tickets->category_id != null)
																				@if($tickets->category != null)

																				<li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Catégorie')}}"><i class="fe fe-grid me-1 fs-14" ></i>{{Str::limit($tickets->category->name, '40')}}</li>

																				@else

																				~
																				@endif
																			@else

																				~
																			@endif

																			@if($tickets->last_reply == null)
																				<li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$tickets->created_at->diffForHumans()}}</li>

																			@else
																			<li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$tickets->last_reply->diffForHumans()}}</li>

																			@endif

																			@if($tickets->purchasecodesupport != null)
																			@if($tickets->purchasecodesupport == 'Supported')

																			<li class="px-2 text-success font-weight-semibold">{{lang('Assistance active')}}</li>
																			@endif
																			@if($tickets->purchasecodesupport == 'Expired')

																			<li class="px-2 text-danger-dark font-weight-semibold">{{lang('Assistance expirée')}}</li>
																			@endif
																			@endif

																		</ul>
																	</div>
																</div>
															</td>
															<td>
																{{$tickets->cust->username}}
															</td>
															<td>
																@if($tickets->status == "New")

																<span class="badge badge-burnt-orange">{{lang($tickets->status)}}</span>

																@elseif($tickets->status == "Re-Open")

																<span class="badge badge-teal">{{lang($tickets->status)}}</span>

																@elseif($tickets->status == "Inprogress")

																<span class="badge badge-info">{{lang($tickets->status)}}</span>

																@elseif($tickets->status == "On-Hold")

																<span class="badge badge-warning">{{lang($tickets->status)}}</span>

																@else

																<span class="badge badge-danger">{{lang($tickets->status)}}</span>

																@endif
															</td>
															<td>
																@if(Auth::user()->can('Ticket Assign'))
																	@if($tickets->status == 'Suspend' || $tickets->status == 'Closed')
																		<div class="btn-group">
																			@if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)

																			<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" disabled>{{lang('Multi Assign')}} <span class="caret"></span></button>
																			<button data-id="{{$tickets->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
																			@elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)

																			<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{$tickets->selfassign->name}} (self) <span class="caret"></span></button>
																			<button data-id="{{$tickets->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
																			@else

																			<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{lang('Attribuer')}}<span class="caret"></span></button>
																			@endif

																		</div>
																	@else
																		@if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id == null)

																			<div class="btn-group">
																				<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
																				<ul class="dropdown-menu" role="menu">
																					<li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
																					<li>
																						<a href="javascript:void(0);" id="selfassigid" data-id="{{$tickets->id}}">{{lang('Self Assign')}}</a>
																					</li>
																					<li>
																						<a href="javascript:void(0)" data-id="{{$tickets->id}}" id="assigned">
																						{{lang('Attribuer à un prestataire')}}
																						</a>
																					</li>
																				</ul>
																			</div>
																		@else
																			<div class="btn-group">
																				@if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)
																					@if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
																					<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
																					@else
																					<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Multi Assign')}} <span class="caret"></span></button>
																					<a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
																					@endif

																				@elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)
																				@if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
																				<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
																				@else
																				<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{$tickets->selfassign->name}} (self) <span class="caret"></span></button>
																				<a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
																				@endif
																				@else

																				<button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
																				@endif

																				<ul class="dropdown-menu" role="menu">
																					<li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
																					<li>
																						<a href="javascript:void(0);" id="selfassigid" data-id="{{$tickets->id}}">{{lang('Self Assign')}}</a>
																					</li>
																					<li>
																						<a href="javascript:void(0)" data-id="{{$tickets->id}}" id="assigned">
																						{{lang('Attribuer à un prestataire')}}
																						</a>
																					</li>
																				</ul>
																			</div>

																		@endif
																	@endif
																@else
																	~
																@endif
															</td>
															<td>
																@if(Auth::user()->can('Ticket Edit'))

																<a href="{{url('admin/ticket-view/' . $tickets->ticket_id)}}" class="btn btn-sm action-btns edit-testimonial"><i class="feather feather-eye text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i></a>
																@else
																	~
																@endif
																@if(Auth::user()->can('Ticket Delete'))
																<a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-sm action-btns" id="show-delete" ><i class="feather feather-trash-2 text-danger" data-id="{{$tickets->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i></a>
																@else
																~
																@endif
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>

							@endsection


		@section('scripts')



		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{gn_asset('assets/js/support/support-admindash.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<script src="{{gn_asset('assets/js/select2.js')}}"></script>


		<script type="text/javascript">

			"use strict";

			(function($)  {

				// Variables
				var SITEURL = '{{url('')}}';

				// Csrf Field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				// Datatable
				// var table =  $('#activeticket').dataTable({
				// 	language: {
				// 		searchPlaceholder: search,

				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[ 0,1,6] }
				// 	],
				// });

                let prev = {!! json_encode(lang("Previous")) !!};
                let next = {!! json_encode(lang("Next")) !!};
                let nodata = {!! json_encode(lang("No data available in table")) !!};
                let noentries = {!! json_encode(lang("No entries to show")) !!};
                let showing = {!! json_encode(lang("showing page")) !!};
                let ofval = {!! json_encode(lang("of")) !!};
                let maxRecordfilter = {!! json_encode(lang("- filtered from ")) !!};
                let maxRecords = {!! json_encode(lang("records")) !!};
                let entries = {!! json_encode(lang("entries")) !!};
                let show = {!! json_encode(lang("Show")) !!};
                let search = {!! json_encode(lang("Search...")) !!};
                // Datatable
                $('#activeticket').dataTable({
                    language: {
                        searchPlaceholder: search,
                        scrollX: "100%",
                        sSearch: '',
                        paginate: {
                        previous: prev,
                        next: next
                        },
                        emptyTable : nodata,
                        infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                        info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                        infoEmpty: noentries,
                        lengthMenu: `${show} _MENU_ ${entries} `,
                    },
                    order:[],
                    columnDefs: [
                        { "orderable": false, "targets":[ 0,1,4] }
                    ],
                });

				$('.form-select').select2({
					minimumResultsForSearch: Infinity,
					width: '100%'
				});


				// TICKET DELETE SCRIPT
				$('body').on('click', '#show-delete', function () {
					var _id = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
						text: "{{lang('This might erase your records permanently', 'alerts')}}",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
					if (willDelete) {
						$.ajax({
								type: "get",
								url: SITEURL + "/admin/delete-ticket/"+_id,
								success: function (data) {
									toastr.success(data.success);
									var oTable = $('#activeticket').dataTable();
									oTable.fnDraw(false);
									location.reload();
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					});

				});

				//Mass Delete
				$('body').on('click', '#massdelete', function () {

					var id = [];

					$('.checkall:checked').each(function(){
						id.push($(this).val());
					});
					if(id.length > 0){
						swal({
							title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
							text: "{{lang('This might erase your records permanently', 'alerts')}}",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						})
						.then((willDelete) => {
							if (willDelete) {
								$.ajax({
									url:"{{ url('admin/ticket/delete/tickets')}}",
									method:"GET",
									data:{id:id},
									success:function(data)
									{

										toastr.success(data.success);
										location.reload();

									},
									error:function(data){

									}
								});
							}
						});
					}
					else{
						toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
					}

				});


				// when user click its get modal popup to assigned the ticket
				$('body').on('click', '#assigned', function () {
					var assigned_id = $(this).data('id');

					$('.select2_modalassign').select2({
						dropdownParent: ".sprukosearch",
						minimumResultsForSearch: '',
						placeholder: "Search",
						width: '100%'
					});

					$.get('assigned/' + assigned_id , function (data) {
						$('#AssignError').html('');
						$('#assigned_id').val(data.assign_data.id);
						$(".modal-title").text('{{lang('Attribuer à un prestataire')}}');
						$('#username').html(data.table_data);
						$('#addassigned').modal('show');
					});
				});

				// Assigned Submit button
				$('body').on('submit', '#assigned_form', function (e) {
					e.preventDefault();
					var actionType = $('#btnsave').val();
					var fewSeconds = 2;
					$('#btnsave').html('Sending..');
					$('#btnsave').prop('disabled', true);
						setTimeout(function(){
							$('#btnsave').prop('disabled', false);
						}, fewSeconds*1000);
					var formData = new FormData(this);

					$.ajax({
						type:'POST',
						url: SITEURL + "/admin/assigned/create",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,

						success: (data) => {
							$('#AssignError').html('');
							$('#assigned_form').trigger("reset");
							$('#addassigned').modal('hide');
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
							var oTable = $('#activeticket').dataTable();
							oTable.fnDraw(false);
							toastr.success(data.success);
							location.reload();
						},
						error: function(data){
							$('#AssignError').html('');
							$('#AssignError').html(data.responseJSON.errors.assigned_user_id);
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						}
					});
				});

				// Remove the assigned from the ticket
				$('body').on('click', '#btnremove', function () {
					var asid = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to unassign this agent?', 'alerts')}}`,
						text: "{{lang('This agent may no longer exist for this ticket.', 'alerts')}}",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "get",
								url: SITEURL + "/admin/assigned/update/"+asid,
								success: function (data) {
									var oTable = $('#activeticket').dataTable();
									oTable.fnDraw(false);
									toastr.success(data.success);
									location.reload();
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});

						}
					});

				});

				// Checkbox checkall
				$('#customCheckAll').on('click', function() {
					$('.checkall').prop('checked', this.checked);
				});

				$('body').on('click','#selfassigid', function(e){

					e.preventDefault();

					let id = $(this).data('id');

					$.ajax({
						method:'POST',
						url: '{{route('admin.selfassign')}}',
						data: {
							id : id,
						},
						success: (data) => {
							toastr.success(data.success);
							location.reload();
						},
						error: function(data){

						}
					});
				})

			})(jQuery);


		</script>

		@endsection
		@section('modal')

		@include('admin.modalpopup.assignmodal')

		@endsection
