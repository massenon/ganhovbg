@extends('layouts.adminmaster')

  		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.css')}}" rel="stylesheet" />

        <!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

  		@endsection

  							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
								<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Agent View Reports')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

                            <div class="row">
								<div class="col-xl-3 col-lg-4 col-md-12">
									<div class="card user-pro-list overflow-hidden">
										<div class="card-body">
											<div class="user-pic text-center">
												@if ($users->image == null)

                                                <img src="{{asset('uploads/profile/user-profile.png')}}" class="avatar avatar-xxl brround" alt="">

												@else
                                                <img src="{{asset('uploads/profile/'.$users->image)}}" class="avatar avatar-xxl brround" alt="">

												@endif
												<div class="pro-user mt-3">
													<h5 class="pro-user-username text-dark mb-1 fs-16">{{$users->name}}</h5>
													<h6 class="pro-user-desc text-muted fs-12">{{$users->email}}</h6>
													@if(!empty($users->getRoleNames()[0]))
													<h6 class="pro-user-desc text-muted fs-12">{{ $users->getRoleNames()[0]}}</h6>
													@endif
													{{-- <div class="profilerating" data-rating="{{$avg}}"></div> --}}
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="card-header border-0">
											<h4 class="card-title"> {{lang('Personal Details')}}</h4>
										</div>
										<div class="card-body px-0 pb-0">

											<div class="table-responsive tr-lastchild">
												<table class="table mb-0 table-information">
													<tbody>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Employee ID')}}</span>
															</td>
															<td class="py-2 ps-4">{{$users->empid}}</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Nom')}} </span>
															</td>
															<td class="py-2 ps-4">{{$users->name}}</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Nom du rôle')}} </span>
															</td>
															<td class="py-2 ps-4">
																@if(!empty($users->getRoleNames()[0]))

																 {{$users->getRoleNames()[0]}}
																 @endif

															</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Email')}} </span>
															</td>
															<td class="py-2 ps-4">{{$users->email}}</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Phone')}} </span>
															</td>
															<td class="py-2 ps-4">{{$users->phone}}</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Languages')}} </span>
															</td>
															<td class="py-2 ps-4">
																@php
																$values = explode(",", $users->languagues);

																@endphp

																<ul class="custom-ul">
																	@foreach ($values as $value)

																	<li class="tag mb-1">{{ucfirst($value)}}</li>

																	@endforeach

																</ul>
															</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50">{{lang('Skills')}} </span>
															</td>
															<td class="py-2 ps-4">
																@php
																$values = explode(",", $users->skills);
																@endphp

																<ul class="custom-ul">
																	@foreach ($values as $value)

																	<li class="tag mb-1">{{ucfirst($value)}}</li>

																	@endforeach

																</ul>
															</td>
														</tr>
														<tr>
															<td class="py-2">
																<span class="font-weight-semibold w-50"> {{lang('Location')}} </span>
															</td>
															<td class="py-2 ps-4">{{$users->country}}</td>
														</tr>

													</tbody>
												</table>
											</div>
										</div>
									</div>

								</div>
								<div class="col-xl-9 col-lg-8 col-md-12">
									<div class="card">
										<div class="card-header border-0">
											<h4 class="card-title">{{lang('Agent View Reports')}}</h4>
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table class="table border-bottom text-nowrap table-bordered w-100" id="reports">
													<thead>
														<tr>
															<th width="10">{{lang('Sl.No')}}</th>
															<th>#{{lang('ID')}}</th>
															<th>{{lang('Rating')}}</th>
															<th>{{lang('Ticket Comment')}}</th>
															<th>{{lang('Ticket Customer')}}</th>
															<th>{{lang('Actions')}}</th>
														</tr>
													</thead>
													<tbody>
														@php $i = 1; @endphp

														@foreach($employeeratings as $ticket)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>
																	<a href="{{url('admin/ticket-view/' . $ticket->ticket_id)}}">
																	{{$ticket->ticket_id}}
																	</a>
																</td>
                                                                @php

																$avgrating1 = App\Models\Userrating::where('ticket_id',$ticket->id)->where('ratingstar', '1')->count();
																$avgrating2 = App\Models\Userrating::where('ticket_id',$ticket->id)->where('ratingstar', '2')->count();
																$avgrating3 = App\Models\Userrating::where('ticket_id',$ticket->id)->where('ratingstar', '3')->count();
																$avgrating4 = App\Models\Userrating::where('ticket_id',$ticket->id)->where('ratingstar', '4')->count();
																$avgrating5 = App\Models\Userrating::where('ticket_id',$ticket->id)->where('ratingstar', '5')->count();

                                                                $ticketdata = App\Models\Userrating::where('ticket_id',$ticket->id)->get();

																$avgr = ((5*$avgrating5) + (4*$avgrating4) + (3*$avgrating3) + (2*$avgrating2) + (1*$avgrating1));
																$avggr = ($avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5);

																if($avggr == 0){
																	$avggr = 1;
																	$avg = $avgr/$avggr;
																}else{
																	$avg = $avgr/$avggr;
																}

																$rating = $avg;
																@endphp
                                                                <td>
                                                                    <div class="allemployeerating pt-1" data-rating="{{$rating}}"></div>
                                                                </td>
                                                                @forelse($ticketdata as $ticketdatas)
                                                                <td>{{$ticketdatas->ratingcomment}}</td>
                                                                <td>
																	<a href="{{url("/admin/customer/adminlogin/". $ticketdatas->customer->id)}}" target="_blank">
																	{{$ticketdatas->customer->username}}
																	</a>
																</td>
                                                                @empty
                                                                <td></td>
                                                                <td></td>
                                                                @endforelse
                                                                <td>
                                                                    <div class = "d-flex">
                                                                        @if($ticketdata->isNotEmpty())
                                                                        <a href="javascript:void(0);" class="action-btns1 delete-report" data-id="{{$ticket->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}">
                                                                            <i class="feather feather-trash-2 text-danger"></i>
                                                                        </a>
                                                                        @else
                                                                        ~
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            {{-- @endforeach --}}
                                                        @endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--Reports List-->

							<!--End Reports List-->

  							@endsection


  		@section('scripts')


		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

        <!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<script type="text/javascript">

			"use strict";

            // Variables
			var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

			$(".allemployeerating").starRating({
				readOnly: true,
				starSize: 25,
				emptyColor  :  '#ffffff',
				activeColor :  '#F2B827',
				strokeColor :  '#F2B827',
				strokeWidth :  15,
				useGradient : false
			});

			// Datatable
			// $('#reports').DataTable({
			// 	responsive: true,
			// 	language: {
			// 		searchPlaceholder: search,
			// 		scrollX: "100%",
			// 		sSearch: '',
			// 	},
			// 	order:[],
			// 	// columnDefs: [
			// 	// 	{ "orderable": false, "targets":[ 0,1,4] }
			// 	// ],
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
            $('#reports').dataTable({
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

            // Delete Testimonial
				$('body').on('click', '.delete-report', function () {
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
								type: "post",
								url: SITEURL + "/admin/reports/ratingticket/delete/"+_id,
								success: function (data) {
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

		</script>
  @endsection
