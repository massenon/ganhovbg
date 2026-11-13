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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Sub-Category')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->


							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex ">
										<h4 class="card-title">{{lang('Subcategory List')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Subcategory Create')

												<a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-subcategory">{{lang('Add SubCategory')}}</a>
											@endcan


										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive  spruko-delete">
											@can('Subcategory Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan
											<table class="table table-bordered border-bottom text-nowrap w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Subcategory Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Subcategory Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot
														<th >{{lang('Subcategory Name')}}</th>
														<th >{{lang('Parent Category list')}}</th>
														<th >{{lang('Status')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp

													@foreach($subcategory as $subcats)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Subcategory Delete'))
																	<input type="checkbox" name="subcategory_checkbox[]" class="checkall" value="{{$subcats->id}}" />
																@else
																	<input type="checkbox" name="subcategory_checkbox[]" class="checkall" value="{{$subcats->id}}" disabled />
																@endif
															</td>
															<td>{{$subcats->subcategoryname}}</td>
															<td>
																@foreach($subcats->subcategorylist as $subcatlist)
																<span class="badge badge-info-light">
																	{{$subcatlist->subcatlistss->name}}
																</span>
																@endforeach
															</td>
															<td>
																<label class="custom-switch form-switch mb-0">
																	<input type="checkbox" name="status" data-id="{{$subcats->id}}" id="myonoffswitch{{$subcats->id}}" value="1" class="custom-switch-input stswitch" {{$subcats->status == 1 ? 'checked' : ''}}>
																	<span class="custom-switch-indicator"></span>
																</label>

															</td>
															<td>
																<div class = "d-flex">
																	@can('Subcategory Edit')

																	<a href="javascript:void(0)" class="action-btns1 edit-subcategory" data-id="{{$subcats->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"><i class="feather feather-edit text-primary"></i></a>
																	@endcan
																	@can('Subcategory Delete')

																	<a href="javascript:void(0)" class="action-btns1 delete-subcategory" data-id="{{$subcats->id}}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"><i class="feather feather-trash-2 text-danger"></i></a>
																	@endcan
																	@cannot('Subcategory Edit')
																	~
																	@endcannot
																	@cannot('Subcategory Delete')
																	~
																	@endcannot
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

				// select2 js
				$('.select2').select2({
					minimumResultsForSearch: Infinity
				});

				// Csrf field
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
				// 		{ "orderable": false, "targets":[0,1,5] }
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

                /*  When user click add category button */
				$('#create-new-subcategory').on('click', function () {
					$('#btnsave').val("create-product");
					$('#subcategory_id').val('');
					$('#nameError').html('');
					$('#displayError').html('');
					$('#priorityError').html('');
					$('.categorysub').html('');
					$('#subcategory_form').trigger("reset");
					$('.modal-title').html("{{lang('Add New SubCategory')}}");
					$('.categorysub').select2({
						minimumResultsForSearch: '',
					});
					$.post('category/all', function(data){
						$('.categorysub').html(data);
					});

					$('#addsubcategory').modal('show');


				});

				/* When click edit category */
				$('body').on('click', '.edit-subcategory', function () {
					var subcategory_id = $(this).data('id');
					$('.categorysub').select2({
						minimumResultsForSearch: '',
					});
					$.get('subcategories/' + subcategory_id  + '/edit', function (data) {
						$('#subcategory_form').trigger("reset");
						$('#nameError').html('');
						$('#displayError').html('');
						$('#priorityError').html('');
						$('.categorysub').html('');
						$('.modal-title').html("{{lang('Edit Subcategory')}}");
						$('#btnsave').val("edit-testimonial");
						$('#addsubcategory').modal('show');
						$('#subcategory_id').val(data.subcategory.id);
						$('#name').val(data.subcategory.subcategoryname);
						$('.categorysub').html(data.categorylist);
						if (data.subcategory.status == "1")
						{
							$('#myonoffswitch18').prop('checked', true);
						}
					})
				});


				// Category form submit
				$('body').on('submit', '#subcategory_form', function (e) {
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
						url: "{{route('subcategorys.store')}}",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							if(data.errors){
								$('#nameError').html('');
								$('#displayError').html('');
								$('#priorityError').html('');
								$('#nameError').html(data.errors.subcategoryname);
								$('#displayError').html(data.errors.display);
								$('#priorityError').html(data.errors.priority);
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');

							}
							if(data.success){
								$('#nameError').html('');
								$('#displayError').html('');
								$('#priorityError').html('');
								$('#subcategory_form').trigger("reset");
								$('#addsubcategory').modal('hide');
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
								toastr.success(data.success);
								location.reload();
							}

						},
						error: function(data){
							toastr.error('{{lang('SubCategory name already exists', 'alerts')}}');
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						}
					});
				});

				//status On Off
				$('body').on('change', '.stswitch', function(){
					let id = $(this).data('id');
					let status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "POST",
						dataType: "json",
						url: '{{route('category.admin.subcategorystatusupdate')}}',
						data: {
							'status': status,
							'id': id,
						},
						success:function(data){
							toastr.success(data.success);
						},
						error:function(data){

						}
					});
				});

				// Delete subcategory
				$('body').on('click', '.delete-subcategory', function(){
					let id = $(this).data('id');
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
								type: "POST",
								dataType: "json",
								url: '{{route('category.admin.subcategorydelete')}}',
								data: {
									'id': id,
								},
								success:function(data){
									toastr.success(data.success);
									location.reload();
								},
								error:function(data){

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
									url:"{{ url('admin/subcategory/deleteall')}}",
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


		@section('modal')

		@include('admin.category.subcategorymodal')

		@endsection

