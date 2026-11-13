
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
							<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Feature Box', 'menu')}}</span></h1>
						</div>
					</div>
					<!--End Page header-->

					<!-- Feature Box Section -->
					<div class="col-xl-12 col-lg-12 col-md-12">
						<div class="card ">
							<form method="POST" action="{{url('/admin/feature-box/feature')}}" enctype="multipart/form-data">
								@csrf

								@honeypot

								<div class="card-header border-0 d-sm-max-flex">
									<h4 class="card-title">{{lang('Feature Box Section')}}</h4>
								</div>
								<div class="card-body" >
									<div class="row">
										<div class="col-sm-12 col-md-12">
											<input type="hidden" class="form-control " name="id" value="{{$basic->id}}">
											<div class="form-group">
												<label class="form-label">{{lang('Title')}} <span class="">*</span></label>
												<input type="text" class="form-control @error('featuretitle') is-invalid @enderror" name="featuretitle" value="{{$basic->featuretitle}}">
												@error('featuretitle')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
										<div class="col-sm-12 col-md-12">
											<div class="form-group">
												<label class="form-label">{{lang('Subtitle')}}</label>
												<input type="text" class="form-control @error('featuresub') is-invalid @enderror" name="featuresub" value="{{$basic->featuresub}}">
												@error('featuresub')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 card-footer ">
									<div class="form-group float-end">
										<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
									</div>
								</div>
						</form>
						</div>
					</div>
					<!-- End Feature Box Section -->

					<!-- Feature Box List -->
					<div class="col-xl-12 col-lg-12 col-md-12">
						<div class="card">
							<div class="card-header border-0 d-sm-max-flex">
								<h4 class="card-title ">{{lang('Feature Box List')}}</h4>
								<div class="card-options  mt-sm-max-2">
									@can('Feature Box Create')

									<a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-featurebox">{{lang('Add Feature')}}</a>
									@endcan

								</div>
							</div>
							<div class="card-body" >
								<div class="table-responsive spruko-delete">
									@can('Feature Box Delete')

									<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
									@endcan

									<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="featurebox">
										<thead>
											<tr>
												<th width="10" >{{lang('Sl.No')}}</th>
												@can('Feature Box Delete')

												<th width="10" >
													<input type="checkbox"  id="customCheckAll">
													<label  for="customCheckAll"></label>
												</th>
												@endcan
												@cannot('Feature Box Delete')

												<th width="10" >
													<input type="checkbox"  id="customCheckAll" disabled>
													<label  for="customCheckAll"></label>
												</th>
												@endcannot

												<th >{{lang('Title')}}</th>
												<th >{{lang('Subtitle')}}</th>
												<th >{{lang('Actions')}}</th>
											</tr>
										</thead>
										<tbody>
											@php $i = 1; @endphp
											@foreach($featureboxes as $featurebox)
												<tr>
													<td>{{$i++}}</td>
													<td>
														@if(Auth::user()->can('Feature Box Delete'))
															<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$featurebox->id}}" />
														@else
															<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$featurebox->id}}" disabled />
														@endif
													</td>
													<td>
														{{$featurebox->title ? str_limit($featurebox->title, 40, '...') : ''}}
														{{$featurebox->id}}
													</td>
													<td>
														{{$featurebox->subtitle ? str_limit($featurebox->subtitle, 40, '...') : ''}}

													</td>
													<td>
														<div class = "d-flex">
														@if(Auth::user()->can('Feature Box Edit'))

															<a href="javascript:void(0)" data-id="{{$featurebox->id}}" class="action-btns1 edit-testimonial">
																<i class="feather feather-edit text-primary" data-id="{{$featurebox->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
															</a>
														@else

															~
														@endif
														@if(Auth::user()->can('Feature Box Delete'))

															<a href="javascript:void(0)" data-id="{{$featurebox->id}}" class="action-btns1" id="delete-testimonial" >
																<i class="feather feather-trash-2 text-danger" data-id="{{$featurebox->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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
					<!-- End Feature Box List -->
					@endsection

		@section('modal')

		   @include('admin.featurebox.model')

		@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>


<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script type="text/javascript">

	"use strict";

	(function($)  {

		// Variables
		var SITEURL = '{{url('')}}';

		// Csrf field
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// Datatable
		// $('#featurebox').DataTable({
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
        $('#featurebox').dataTable({
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

		/*  When user click add featurebox button */
		$('#create-new-featurebox').on('click', function () {
			$('#nameError').html('');
			$('#descriptionError').html('');
			$('#ImageError').html('');
			$('#btnsave').val("create-feature");
			$('#featurebox_id').val('');
			$('#featurebox_form').trigger("reset");
			$('.modal-title').html("{{lang('Add New Feature Box')}}");
			$('#addfeature').modal('show');
		});

		/* When click edit featurebox */
		$('body').on('click', '.edit-testimonial', function () {
			var testimonial_id = $(this).data('id');
			$.get('feature-box/' + testimonial_id , function (data) {
				$('#nameError').html('');
				$('#descriptionError').html('');
				$('#ImageError').html('');
				$('.modal-title').html("{{lang('Edit Feature Box')}}");
				$('#btnsave').val("edit-testimonial");
				$('#addfeature').modal('show');
				$('#featurebox_id').val(data.id);
				$('#name').val(data.title);
				$('#description').val(data.subtitle);
				$('#featureboxurl').val(data.featureboxurl);
				if (data.url_checkbox == "on")
				{
					$('#url_checkbox').prop('checked', true);
				}else{
				    $('#url_checkbox').prop('checked', false);
				}

			})
		});

		// Delete Featurebox function
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
						url: SITEURL + "/admin/feature-box/delete/"+_id,
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

		// Feature Box Submit button
		$('body').on('submit', '#featurebox_form', function (e) {
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
				url: SITEURL + "/admin/feature-box/create",
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success: (data) => {
					if(data.errors){
						$('#nameError').html('');
						$('#descriptionError').html('');
						$('#ImageError').html('');
						$('#nameError').html(data.errors.title);
						$('#descriptionError').html(data.errors.subtitle);
						$('#ImageError').html(data.errors.image);
						$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
					}
					if(data.success){
						$('#nameError').html('');
						$('#descriptionError').html('');
						$('#ImageError').html('');
						$('#featurebox_form').trigger("reset");
						$('#addfeature').modal('hide');
						$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						toastr.success(data.success);
						location.reload();
					}
				},
				error: function(data){
					$('#nameError').html('');
					$('#descriptionError').html('');
					$('#ImageError').html('');
					console.log('Error:', data);
					$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
				}
			});
		});
		// Mass Delete
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
							url:"{{ route('featurebox.deleteall')}}",
							method:"post",
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

		//checkbox checkall
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
