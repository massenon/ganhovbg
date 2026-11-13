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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Témoignage')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!--Testimonial Section-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<form method="POST" action="{{url('/admin/testimonial')}}" enctype="multipart/form-data">
										@csrf

										@honeypot

										<div class="card-header border-0 d-sm-max-flex">
											<h4 class="card-title">{{lang('Testmonial Section')}}</h4>
											<div class="card-options card-header-styles mt-sm-max-2">
												<small class="me-1 mt-1">{{lang('Show Section')}}</small>
												<div class="float-end mt-0">
													<div class="switch-toggle">
														<a class="onoffswitch2">
															<input type="checkbox"  name="testimonialcheck" id="testimonialchecks" class=" toggle-class onoffswitch2-checkbox" value="on" @if($basic->testimonialcheck == 'on')  checked=""  @endif>
															<label for="testimonialchecks" class="toggle-class onoffswitch2-label" ></label>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="card-body" >
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<input type="hidden" class="form-control " name="id" value="{{$basic->id}}">
													<div class="form-group">
														<label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('testimonialtitle') is-invalid @enderror" name="testimonialtitle" value="{{$basic->testimonialtitle}}">
														@error('testimonialtitle')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">{{lang('Subtitle')}}</label>
														<input type="text" class="form-control @error('testimonialsub') is-invalid @enderror" name="testimonialsub" value="{{$basic->testimonialsub}}">
														@error('testimonialsub')

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
							<!--End Testimonial Section-->

							<!--Testimonial List-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('Liste des Témoignages')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Testimonial Create')

											<a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-testimonial">{{lang('Ajouter Témoignage')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Testimonial Delete')

											<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Testimonial Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Testimonial Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th >{{lang('Nom')}}</th>
														<th >{{lang('Designation')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($testimonials as $testimonial)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Testimonial Delete'))

																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$testimonial->id}}" />
																@else

																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$testimonial->id}}" disabled />
																@endif
															</td>
															<td>
																{{Str::limit($testimonial->name, '40')}}
															</td>
															<td>{{Str::limit($testimonial->designation, '40')}}</td>
															<td>
																<div class = "d-flex">
																@if(Auth::user()->can('Testimonial Edit'))

																	<a href="javascript:void(0)" data-id="{{$testimonial->id}}" class="action-btns1 edit-testimonial">
																		<i class="feather feather-edit text-primary" data-id="{{$testimonial->id}}"data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																	</a>
																@else
																	~
																@endif
																@if(Auth::user()->can('Testimonial Delete'))

																	<a href="javascript:void(0)" data-id="{{$testimonial->id}}" class="action-btns1" id="delete-testimonial" >
																		<i class="feather feather-trash-2 text-danger" data-id="{{$testimonial->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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
							<!--End Testimonial List-->

							@endsection
	@section('modal')

   	@include('admin.testimonial.model')

	@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

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
				// $('#support-articlelists').dataTable({
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
				// })

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

				/*  When user click add testimonial button */
				$('#create-new-testimonial').on('click', function () {
					$('#btnsave').val("create-product");
					$('#testimonial_id').val('');
					$('#testimonial_form').trigger("reset");
					$('.modal-title').html("{{lang('Add New Testmonial')}}");
					$('#addtestimonial').modal('show');
					$('#image-preview').attr('src', '{{asset('assets/images/imagepreview/displayimage.png')}}');
				});

				/* When click edit testimonial */
				$('body').on('click', '.edit-testimonial', function () {
					var testimonial_id = $(this).data('id');
					$.get('testimonial/' + testimonial_id , function (data) {
						$('#nameError').html('');
						$('#descriptionError').html('');
						$('#designationError').html('');
						$('#imageError').html('');

						$('.modal-title').html("{{lang('Edit Testimonial')}}");
						$('#btnsave').val("edit-testimonial");
						$('#addtestimonial').modal('show');
						$('#testimonial_id').val(data.id);
						$('#name').val(data.name);
						$('#designation').val(data.designation);
						$('#description').val(data.description);
						$('#image-preview').attr('alt', 'No image available');
						if(data.image){
							$('#image-preview').attr('src', SITEURL +'/uploads/testimonial/'+data.image);
							$('#image').attr('src', SITEURL +'/uploads/testimonial/'+data.image);
						}
					})
				});

				// Delete Testimonial
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
								url: SITEURL + "/admin/testimonial/delete/"+_id,
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

				// Mass Delete Testimonial
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
									url:"{{ route('testimonial.deleteall')}}",
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

				// Testimonial submit form
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
					url: SITEURL + "/admin/testimonial/create",
					data: formData,
					cache:false,
					contentType: false,
					processData: false,

					success: (data) => {
						$('#nameError').html('');
						$('#descriptionError').html('');
						$('#designationError').html('');
						$('#imageError').html('');

						$('#testimonial_form').trigger("reset");
						$('#addtestimonial').modal('hide');
						$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						toastr.success(data.success);
						location.reload();
					},
					error: function(data){

						$('#nameError').html('');
						$('#descriptionError').html('');
						$('#designationError').html('');
						$('#imageError').html('');
						$('#nameError').html(data.responseJSON.errors.name);
						$('#descriptionError').html(data.responseJSON.errors.description);
						$('#designationError').html(data.responseJSON.errors.designation);
						$('#imageError').html(data.responseJSON.errors.image);
						$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
					}
					});
				});

				// Checkbox checkall
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
