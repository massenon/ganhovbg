@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<!-- INTERNAL Data table css -->
<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

<!-- INTERNAL Datepicker css-->
<link href="{{gn_asset('assets/plugins/modal-datepicker/datepicker.css')}}" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />


@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Announcements', 'menu')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<!--Announcement Settings -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Announcement Settings')}}</h4>
		</div>
		<form method="POST" action="{{route('settings.announcement')}}" enctype="multipart/form-data">
			<div class="card-body py-0" >
				@csrf

				@honeypot
				<div class="row">
					<div class="switch_section mt-3 mb-1">
						<div class="switch-toggle d-flex ">
							<a class="onoffswitch2">
								<input type="radio"  name="ANNOUNCEMENT_USER" id="allusers" class=" toggle-class onoffswitch2-checkbox" value="all_users" @if(setting('ANNOUNCEMENT_USER') == 'all_users') checked="" @endif>
								<label for="allusers" class="toggle-class onoffswitch2-label"></label>
							</a>
							<div class="ps-3">
								<label class="form-label">{{lang('All Users', 'setting')}}</label>
								<small class="text-muted"><i>({{lang('If you enable this "All Users" setting feature, the "Announcement" will appear to both the users, i.e., for login users as well as non login users on the "Application.', 'setting')}})</i></small>
							</div>
						</div>
					</div>
					<div class="switch_section mt-2 mb-1">
						<div class="switch-toggle d-flex ">
							<a class="onoffswitch2">
								<input type="radio"  name="ANNOUNCEMENT_USER" id="onlyloginuser" class=" toggle-class onoffswitch2-checkbox" value="only_login_user"  @if(setting('ANNOUNCEMENT_USER') == 'only_login_user') checked="" @endif>
								<label for="onlyloginuser" class="toggle-class onoffswitch2-label"></label>
							</a>
							<div class="ps-3">
								<label class="form-label">{{lang('Only Login Users', 'setting')}}</label>
								<small class="text-muted"><i>({{lang('If you enable this "Only Login Users" setting feature, the "Announcement" will appear only for the Login users on the "Application."', 'setting')}})</i></small>
							</div>
						</div>
					</div>
					<div class="switch_section mt-2 mb-2">
						<div class="switch-toggle d-flex ">
							<a class="onoffswitch2">
								<input type="radio"  name="ANNOUNCEMENT_USER" id="nonloginusers" class=" toggle-class onoffswitch2-checkbox" value="non_login_users"  @if(setting('ANNOUNCEMENT_USER') == 'non_login_users') checked="" @endif>
								<label for="nonloginusers" class="toggle-class onoffswitch2-label"></label>
							</a>
							<div class="ps-3">
								<label class="form-label">{{lang('Non Login Users', 'setting')}}</label>
								<small class="text-muted"><i>({{lang('If you enable this "Non Logi Users" setting feature, the "Announcement" will appear for the non login users on the "Application."', 'setting')}})</i></small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="form-group float-end">
					<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
				</div>
			</div>
		</form>
	</div>
</div>
<!-- End Announcement Settings -->

<!--Announcement List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">

			<h4 class="card-title mb-md-max-2">{{lang('Announcements', 'menu')}}</h4>
			<div class="card-options">
				@can('Announcements Create')

				{{-- <a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-testimonial">{{lang('Add New Announcement')}}</a> --}}
				<a href="{{route('announcement.create')}}" class="btn btn-secondary me-3">{{lang('Add New Announcement')}}</a>
				@endcan

			</div>
		</div>
		<div class="card-body" >
			<div class="table-responsive">
				@can('Announcements Delete')

				<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
				@endcan

				<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100 " id="support-articlelists">
					<thead>
						<tr>
							<th  width="10">{{lang('Sl.No')}}</th>
							@can('Announcements Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll">
								<label  for="customCheckAll"></label>
							</th>
							@endcan
							@cannot('Announcements Delete')

							<th width="10" >
								<input type="checkbox"  id="customCheckAll" disabled>
								<label  for="customCheckAll"></label>
							</th>
							@endcannot

							<th >{{lang('Title')}}</th>
							<th >{{lang('Start Date')}}</th>
							<th >{{lang('End Date')}}</th>
							<th >{{lang('Selected Day')}}</th>
							<th >{{lang('Status')}}</th>
							<th >{{lang('Actions')}}</th>
						</tr>
					</thead>
					<tbody>
						@php $i = 1; @endphp
						@foreach($announcements as $announcement)
							<tr>
								<td>{{$i++}}</td>
								<td>
									@if(Auth::user()->can('Announcements Delete'))

										<input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$announcement->id}}'" />
									@else
										<input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$announcement->id}}'" disabled />
									@endif
								</td>
								<td>{{Str::limit($announcement->title, '40')}}</td>
								@if($announcement->announcementday)
									<td>~</td>
									<td>~</td>
									<td>{{$announcement->announcementday}}</td>
								@else
									<td>{{$announcement->startdate->translatedFormat(setting('date_format'))}}</td>
									<td>{{$announcement->enddate->translatedFormat(setting('date_format'))}}</td>
									<td>~</td>
								@endif
								<td>
									@if(Auth::user()->can('Announcements Edit'))
										@if($announcement->status == '1')

											<label class="custom-switch form-switch mb-0">
												<input type="checkbox" name="status" data-id="{{$announcement->id}}" id="myonoffswitch{{$announcement->id}}" value="1" class="custom-switch-input tswitch" checked>
												<span class="custom-switch-indicator"></span>
											</label>
										@else
											<label class="custom-switch form-switch mb-0">
												<input type="checkbox" name="status" data-id="{{$announcement->id}}" id="myonoffswitch{{$announcement->id}}" value="1" class="custom-switch-input tswitch" >
												<span class="custom-switch-indicator"></span>
											</label>
										@endif
									@else
										~
									@endif
								</td>
								<td>
									<div class = "d-flex">
									@if(Auth::user()->can('Announcements Edit'))

										<a href="{{route('announcement.edit',$announcement->id)}}" data-id="{{$announcement->id}}" class="action-btns1">
											<i class="feather feather-edit text-primary" data-id="{{$announcement->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
										</a>
									@else

										~
									@endif
									@if(Auth::user()->can('Announcements Delete'))

										<a href="javascript:void(0)" data-id="{{$announcement->id}}" class="action-btns1" id="delete-testimonial" >
											<i class="feather feather-trash-2 text-danger" data-id="{{$announcement->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
										</a>
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
<!-- End Announcement List -->

@endsection

@section('modal')

@include('admin.announcement.model')

@endsection

@section('scripts')

<!-- INTERNAL Summernote js  -->
<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
<script src="{{gn_asset('assets/js/support/support-articles.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script src="{{gn_asset('assets/plugins/jquery/jquery-ui.js')}}"></script>

<!-- INTERNALdatepicker js-->

<script type="text/javascript">

	(function($)  {
		"use strict";

		// Variables
		var SITEURL = '{{url('')}}';
		var now = Date.now();

		// Csrf Field
		$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		//_____ Datatable
		// $('#support-articlelists').dataTable({
		// 	responsive: true,
		// 	language: {
		// 		searchPlaceholder: search,
		// 		scrollX: "100%",
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
        $('#support-articlelists').dataTable({
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

		/*  When user click add announcement button */
		$('#create-new-testimonial').on('click', function () {
			// Datepicker
			$('#startdate').datepicker({
				dateFormat: 'yy-mm-dd',
				prevText: '<i class="fa fa-angle-left"></i>',
				nextText: '<i class="fa fa-angle-right"></i>',
				minDate: 0,
				firstDay: {{setting('start_week')}},

				onSelect: function (selectedDate) {

					var diff = ($("#enddate").datepicker("getDate") -
						$("#startdate").datepicker("getDate")) /
						1000 / 60 / 60 / 24 + 1; // days
					if ($("#enddate").datepicker("getDate") != null) {
						$('#count').html(diff);
						$('#days').val(diff);
					}
					$('#enddate').datepicker('option', 'minDate', selectedDate);
				}
			});
			$('#enddate').datepicker({
				dateFormat: 'yy-mm-dd',
				prevText: '<i class="fa fa-angle-left"></i>',
				nextText: '<i class="fa fa-angle-right"></i>',
				firstDay: {{setting('start_week')}},
				onSelect: function (selectedDate) {

					$('#startdate').datepicker('option', 'maxDate', selectedDate);

					var diff = ($("#enddate").datepicker("getDate") -
						$("#startdate").datepicker("getDate")) /
						1000 / 60 / 60 / 24 + 1; // days
					if ($("#startdate").datepicker("getDate") != null) {
						$('#count').html(diff);
						$('#days').val(diff);
					}
				}
			});

			$('#btnsave').val("create-product");
			$('#testimonial_id').val('');
			$('#description').summernote({
				height: 100,
			});
			$('#description').summernote('code','');
			// Select2
			$('.form-select').select2({
				multiple: true,
				minimumResultsForSearch: Infinity,
				width:'100%',
				closeOnSelect: false
			});
			$('#testimonial_form').trigger("reset");
			$('.modal-title').html("{{lang('Add Announcement')}}");
			$('#addtestimonial').modal('show');

		});

		/* When click delete announcement */
		$('body').on('click', '#delete-testimonial', function () {
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
						url: SITEURL + "/admin/announcement/delete/"+_id,
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

		//Mass Delete
		$('body').on('click', '#massdeletenotify', function () {
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
							url:"{{ route('announcementall.delete')}}",
							method:"post",
							data:{id:id},
							success:function(data)
							{
								toastr.success(data.success);
								location.reload();

							},
							error:function(data){
								console.log(data);
							}
						});
					}
				});
			}else{
				toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
			}
		});

		// Announcement submit form
		$('body').on('submit', '#testimonial_form', function (e) {
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
				url: SITEURL + "/admin/announcement/create",
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success: (data) => {
					$('#testimonial_form').trigger("reset");
					$('#addtestimonial').modal('hide');
					$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
					toastr.success(data.success);
					location.reload();
					$('#nameError').html('');
					$('#descriptionError').html('');
					$('#startdateError').html('');
					$('#enddateError').html('');
				},
				error: function(data){
					$('#nameError').html('');
					$('#descriptionError').html('');
					$('#startdateError').html('');
					$('#enddateError').html('');
					$('#nameError').html(data.responseJSON.errors.title);
					$('#descriptionError').html(data.responseJSON.errors.notice);
					$('#startdateError').html(data.responseJSON.errors.startdate);
					$('#enddateError').html(data.responseJSON.errors.enddate);
					$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
				}
			});
		});

		// Announcement  status
		$('body').on('click', '.tswitch', function () {
			var _id = $(this).data("id");
			var status = $(this).prop('checked') == true ? '1' : '0';
				$.ajax({
					type: "post",
					url: SITEURL + "/admin/announcement/status"+_id,
					data: {'status': status},
					success: function (data) {
						toastr.success(data.success);
						location.reload();
					},
					error: function (data) {
						console.log('Error:', data);
					}
				});
		});

		// Check all Checkbox
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
