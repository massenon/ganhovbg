
@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Rôle et autorisations')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!--Role List-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('Liste des rôles')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Roles & Permission Create')

											<a href="{{url('admin/role/create')}}" class="btn btn-primary me-3" >{{lang('Ajouter Rôle et autorisations')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive role-table">
											<table class="table table-bordered border-bottom text-nowrap w-100" id="roleslist">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														<th >{{lang('Nom du rôle')}}</th>
														<th >{{lang('Nombre de prestataire')}}</th>
														<th >{{lang('Nombre d\'autorisation')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach ($roles as $role)
														<tr>
															<td>{{$i++}}</td>
															<td>{{Str::limit($role->name, '40')}}</td>
															<td>
																<span class="badge badge-primary">{{$role->users->count()}}</span>
															</td>
															<td>
																<span class="badge badge-success">{{$role->permissions->count()}}</span>
															</td>
															<td>
																<div class = "d-flex">
																@if(Auth::user()->can('Roles & Permission Edit'))

																	@if($role->name != 'superadmin')
																	<a href="{{url('/admin/role/edit/'.$role->id)}}" class="action-btns1"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"><i class="feather feather-edit text-primary"></i></a>
																	@endif
																@else

																	~
																@endif

																</div>
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
							<!--End Role List-->

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

		<script type="text/javascript">

			"use strict";

			(function($)  {

				// Csrf Field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				//Datatable

				// $('#roleslist').dataTable({
				// 	responsive: true,
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[ 0,1,4] }
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
                $('#roleslist').dataTable({
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
				$('#customCheckAll').prop('checked', false);
				$('.checkall').on('click', function(){
					if($('.checkall:checked').length == $('.checkall').length){
						$('#customCheckAll').prop('checked', true);
					}else{
						$('#customCheckAll').prop('checked', false);
					}
				});

			})(jQuery);

		</script>

		@endsection
