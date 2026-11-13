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
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Victimes')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<!-- Customer List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0 d-md-max-block">
			<h4 class="card-title">{{lang('Liste des Victimes')}}</h4>
			<div class="card-options mt-sm-max-2 d-md-max-block">
				@can('Customers Create')

				<a href="{{url('admin/customer/create')}}" class="btn btn-success mb-md-max-2 me-3"><i class="feather feather-user-plus"></i> {{lang('Ajouter Victime')}}</a>
				@endcan
                @can('Customers Importlist')
				<a href="{{route('admin.customer.import')}}" class="btn btn-info mb-md-max-2 me-3"><i class="feather feather-download"></i> {{lang('Importer Liste des Victimes')}}</a>
                @endcan
			</div>
		</div>
		<div class="card-body" >
			<div class="table-responsive spruko-delete">
				@can('Customers Delete')

				<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
				@endcan

				<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-customerlist">
					<thead>
						<tr>
							<th  width="10">{{lang('Sl.No')}}</th>
							@can('Customers Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll">
								<label  for="customCheckAll"></label>
							</th>
							@endcan
							@cannot('Customers Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll" disabled>
								<label  for="customCheckAll"></label>
							</th>
							@endcannot

							<th >{{lang('Nom')}}</th>
							<th >{{lang('User Type')}}</th>
							<th >{{lang('Verification')}}</th>
							<th >{{lang('Date d\'enregistrement')}}</th>
							<th class="w-5">{{lang('Status')}}</th>
							<th >{{lang('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@php $i = 1; @endphp
						@foreach($customers as $customer)
							<tr>
								<td>{{$i++}}</td>
								<td>
									@if(Auth::user()->can('Customers Delete'))

										<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$customer->id}}" />
									@else

										<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$customer->id}}" disabled />
									@endif
								</td>
								<td>
									@if(auth()->user()->can('Customers Login'))

										<div>
											<h5 class="d-inline">{{Str::limit($customer->username, '40')}}</h5>
											@if($customer->voilated == 'on')
												<span class="badge badge-danger-light"><i class="fa fa-exclamation-triangle text-danger"></i> {{__('Voilation')}}</span>
											@endif
											<a class="float-xxl-end" href="{{url("/admin/customer/adminlogin/". $customer->id)}}"  target="_blank">
												<span class="badge badge-success text-white f-12">{{lang('Login as')}}</span>
											</a>
										</div>
										<small class="fs-12 text-muted">
											<span class="font-weight-normal1">{{Str::limit($customer->email, '40')}}</span>
										</small>
									@else

										<div>
											<a href="#" class="h5">{{Str::limit($customer->username, '40')}}</a>
										</div>
										<small class="fs-12 text-muted">
											<span class="font-weight-normal1">{{Str::limit($customer->email, '40')}}</span>
										</small>
									@endif
								</td>
								<td>{{$customer->userType}}</td>
								<td>
									@if($customer->verified == 1)

										{{lang('Verified')}}

									@else
										{{lang('Unverified')}}
									@endif
								</td>
								<td>
									<span class="badge badge-success-light">
										{{$customer->created_at->translatedFormat(setting('date_format'))}}
									</span>
								</td>
								<td>
									@if($customer->status == "1")
										<span class="badge badge-success">{{lang('Active')}}</span>

									@else
										<span class="badge badge-danger">{{lang('Inactive')}}</span>
									@endif
								</td>
								<td>
									<div class = "d-flex">
									@if(Auth::user()->can('Customers Edit'))

										<a href="{{url('/admin/customer/' . $customer->id)}}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}">
											<i class="feather feather-edit text-primary"></i>
										</a>
									@else
										~
									@endif
									@if(Auth::user()->can('Customers Delete'))

										<a href="javascript:void(0)" class="action-btns1" data-id="{{$customer->id}}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}">
											<i class="feather feather-trash-2 text-danger"></i>
										</a>
									@else
										~
									@endif

									@php
										$ticketCount = \App\Models\Ticket\Ticket::where('cust_id', $customer->id)->count();
									@endphp

									@if($ticketCount > 0)
										<a href="{{route('admin.customer.tickethistory', $customer->id)}}" class="action-btns1"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Tickets History')}}">
											<i class="feather-rotate-ccw text-primary"></i>
										</a>
									@endif

									@if($customer->verified != 1 && $customer->userType == 'Customer')
										<a href="javascript:void(0)" data-id="{{$customer->email}}" id="resendverification" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Send Verification Link')}}">
											<i class="feather feather-link text-primary"></i>
										</a>
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
<!-- End Customer List -->
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

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

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
		// $('#support-customerlist').DataTable({
		// 	responsive: true,
		// 	language: {
		// 		searchPlaceholder: search,
		// 		scrollX: "100%",
		// 		sSearch: '',
		// 	},
		// 	order:[],
		// 	columnDefs: [
		// 		{ "orderable": false, "targets":[ 0,1,7] }
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
        $('#support-customerlist').dataTable({
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

		// Delete the customer
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
						url: SITEURL + "/admin/customer/delete/"+_id,
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

		// Mass Delete
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
							url:"{{ url('admin/masscustomer/delete')}}",
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
			}else{
				toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
			}

		});


		// resend verification code to the customer
		$('body').on('click', '#resendverification', function () {
			var _id = $(this).data("id");

			swal({
				title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
				text: "{{lang('This is to resend email verification link to the customer', 'alerts')}}",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "get",
						url: SITEURL + "/admin/customer/resendverification/"+_id,
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

		// Checkbox check all
		$('#customCheckAll').on('click', function() {
			$('.checkall').prop('checked', this.checked);
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


