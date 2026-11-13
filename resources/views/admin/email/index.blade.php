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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Email Templates', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- Email Template List -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Email Templates', 'menu')}}</h4>
									</div>
									<div class="card-body" >
										<div class="table-responsive">
											<table class="table table-bordered border-bottom text-nowrap w-100" id="support-articlelists">
												<thead  >
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														<th >{{lang('Title')}}</th>
														<th >{{lang('Last Updated on')}}</th>
														<th >{{lang('Action')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
												@foreach ($emailtemplates as $emailtemplate)

													<tr id="row_{{$emailtemplate->id}}">
														<td>{{$i++}}</td>
														<td>{{$emailtemplate->title}}</td>
														<td>{{$emailtemplate->updated_at}}</td>
														<td>
															<div class = "d-flex">
																@can('Email Template Edit')

																<a href="{{ route('settings.email.edit', $emailtemplate->id) }}"  class="action-btns1">
																	<i class="feather feather-edit text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																</a>
																@endcan

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
							<!-- End Email Template List -->
							@endsection
		@section('scripts')

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>

		<script type="text/javascript">

			"use strict";

			// Datatable
			// $('#support-articlelists').dataTable({
			// 	order:[],
			// 	responsive: true,
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
            $('#support-articlelists').dataTable({
                order:[],
				responsive: true,
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
                // order:[],
                // columnDefs: [
                //     { "orderable": false, "targets":[ 0,1,4] }
                // ],
            });

			// select2 js in datatable
			$('.form-select').select2({
				minimumResultsForSearch: Infinity,
				width: '100%'
			});
		</script>

		@endsection
