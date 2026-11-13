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
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Cas VBG supprimé')}}</span></h1>
	</div>
</div>
<!--End Page header-->


<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Cas VBG supprimé')}}</h4>
		</div>
		<div class="card-body" >
			<div class="table-responsive spruko-delete">
				<div class="data-table-btn">
					@can('Ticket Delete')

					<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 ticketdeleterow"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
					@endcan
					<button id="massrestore" class="btn btn-outline-light btn-sm mb-4 ticketdeleterow"><i class="feather feather-rotate-ccw"></i> {{lang('Restore')}}</button>
				</div>
				<table class="table table-bordered border-bottom text-nowrap w-100" id="overduetickets">
					<thead >
						<tr >
							<th >{{lang('Sl.No')}}</th>
							@can('Ticket Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll">
								<label  for="customCheckAll"></label>
							</th>
							@endcan
							@cannot('Ticket Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll" disabled>
								<label  for="customCheckAll"></label>
							</th>
							@endcannot

							<th class="ticket-dets">
								{{lang('Détails Arlette')}}
							</th>
							<th>{{lang('Victime')}}</th>
							<th>{{lang('Status')}}</th>
							<th>{{lang('Actions')}}</th>
						</tr>
					</thead>
					<tbody id="refresh">
						@php $i = 1; @endphp
						@foreach ($tickettrashed as $tickets)
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

											<a href="{{route('admin.tickettrashedview', $tickets->id)}}" class="fs-14 d-block font-weight-semibold">{{$tickets->subject}}</a>

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
												@if($tickets->purchasecodesupport == 'Expired')

												<li class="px-2 text-danger-dark font-weight-semibold">{{lang('Assistance expirée')}}</li>
												@endif
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
									<div class="d-flex">
										<a href="{{route('admin.tickettrashedview', $tickets->id)}}"  class="action-btns1" ><i class="feather feather-eye text-info" data-id="{{$tickets->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i></a>
										<a href="javascript:void(0)" data-id="{{$tickets->id}}" class="action-btns1" id="show-delete" ><i class="feather feather-trash-2 text-danger" data-id="{{$tickets->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i></a>
										<a href="javascript:void(0)" data-id="{{$tickets->id}}" class="action-btns1" id="show-restore" ><i class="feather feather-rotate-ccw text-success" data-id="{{$tickets->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Restore')}}"></i></a>
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

@endsection


@section('scripts')


<!-- INTERNAL Data tables -->
<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>


<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
<script src="{{gn_asset('assets/js/support/support-admindash.js')}}"></script>
<script src="{{gn_asset('assets/js/select2.js')}}"></script>

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

		// DataTable
		// $('#overduetickets').DataTable({
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
                $('#overduetickets').dataTable({
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

		// TICKET RESTORE SCRIPT
		$('body').on('click', '#show-restore', function () {
			var _id = $(this).data("id");
			swal({
				title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
				text: "{{lang('This might restore your record', 'alerts')}}",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/tickettrashedrestore/"+_id,
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
		// TICKET RESTORE SCRIPT END

		// TICKET DELETE SCRIPT
		$('body').on('click', '#show-delete', function () {
			var _id = $(this).data("id");
			swal({
				title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
				text: "{{lang('This might delete your records permanently', 'alerts')}}",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/tickettrasheddestroy/"+_id,
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
		// TICKET DELETE SCRIPT END

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
							url:"{{ url('admin/trashedticket/delete')}}",
							method:"POST",
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

		// Checkbox checkall
		$('#customCheckAll').on('click', function() {
			$('.checkall').prop('checked', this.checked);
		});
		$('#customCheckAll').prop('checked', false);
		$('.checkall').on('click', function(){
			if($('.checkall:checked').length == $('.checkall').length){
				$('#customCheckAll').prop('checked', true);
			}else{
				$('#customCheckAll').prop('checked', false);
			}
		});

		//Mass retore
		$('body').on('click', '#massrestore', function () {
			var id = [];
			$('.checkall:checked').each(function(){
				id.push($(this).val());
			});
			if(id.length > 0){
				swal({
					title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
					text: "{{lang('This might restore your record', 'alerts')}}",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							url:"{{ url('admin/trashedticket/restore')}}",
							method:"POST",
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

	})(jQuery);

</script>

@endsection
