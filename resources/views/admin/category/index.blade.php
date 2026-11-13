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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Catégorie')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!--Category List -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-flex d-block">
										<h4 class="card-title">{{lang('Category List')}}</h4>
										<div class="card-options mt-sm-max-2 d-sm-flex d-block">
											@can('Category Create')

											<a href="javascript:void(0)" class="btn btn-secondary me-3 mb-sm-0 mb-2" id="create-new-testimonial">{{lang('Add Category')}}</a>
											@endcan

											@php $module = Module::all(); @endphp

											@if(in_array('Uhelpupdate', $module))
											@if(setting('ENVATO_ON') == 'on')

												<button class="btn btn-info mb-sm-0 mb-2" id="envatoapiassign">{{lang('Envato Api Assign')}}</button>
											@endif
											@endif

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Category Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan
											<table class="table table-bordered border-bottom text-nowrap w-100" id="support-category">
												<thead>
													<tr>
														<th >{{lang('Sl.No')}}</th>
														@can('Category Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Category Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot
														<th >{{lang('Category Name')}}</th>
														<th >{{lang('Ticket/Knowledge')}}</th>
														<th >{{lang('Assign To Groups')}}</th>
														<th >{{lang('Assigned Priority')}}</th>
														<th >{{lang('Status')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($categories as $category)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Category Delete'))
																	<input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$category->id}}" />
																@else
																	<input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$category->id}}" disabled />
																@endif
															</td>
															<td>{{$category->name}}</td>
															<td>{{$category->display}}</td>
															<td>
																@if(Auth::user()->can('Category Assign To Groups'))

																	@if($category->display == 'ticket' || $category->display == 'both')
																		<a href="javascript:void(0)" data-id="{{$category->id}}" id="assigneds" class="badge badge-pill badge-info mt-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Assign to group')}}">
																			{{$category->groupscategoryc()->count()}}
																		</a>
																	@endif

																@else
																	~
																@endif
															</td>
															<td>
																@if($category->priority != null)

																	@if($category->priority == "Low")

																	<span class="badge badge-success-light" >{{ gn_statut($category->priority) }}</span>


																	@elseif($category->priority == "High")

																	<span class="badge badge-danger-light">{{ gn_statut($category->priority) }}</span>

																	@elseif($category->priority == "Critical")

																	<span class="badge badge-danger-dark">{{ gn_statut($category->priority) }}</span>

																	@else

																	<span class="badge badge-warning-light">{{ gn_statut($category->priority) }}</span>

																	@endif

																@else
																	~
																@endif
															</td>
															<td>
																@if(Auth::user()->can('Category Edit'))
																	@if($category->status == '1')
																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$category->id}}" id="myonoffswitch{{$category->id}}" value="1" class="custom-switch-input tswitch" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>
																	@else
																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$category->id}}" id="myonoffswitch{{$category->id}}" value="1" class="custom-switch-input tswitch">
																			<span class="custom-switch-indicator"></span>
																		</label>
																	@endif
																@else
																	~
																@endif
															</td>
															<td>
																<div class = "d-flex">
																@if(Auth::user()->can('Category Edit'))

																	<a href="javascript:void(0)" data-id="{{$category->id}}" class="action-btns1 edit-testimonial">
																		<i class="feather feather-edit text-primary" data-id="{{$category->id}}"data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																	</a>

																@else
																	~
																@endif
																@if(Auth::user()->can('Category Delete'))

																	<a href="javascript:void(0)" data-id="{{$category->id}}" class="action-btns1 delete-category">
																		<i class="feather feather-trash-2 text-danger" data-id="{{$category->id}}"data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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
							</div>
							<!-- List Category List -->

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

		<!--File BROWSER -->
		<script src="{{gn_asset('assets/js/form-browser.js')}}"></script>

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
				// $('#support-category').dataTable({
				// 	responsive: true,
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[0,1] }
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
                $('#support-category').dataTable({
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
				$('#create-new-testimonial').on('click', function () {
					$('#btnsave').val("create-product");
					$('#testimonial_id').val('');
					$('#testimonial_form').trigger("reset");
					$('.modal-title').html("{{lang('Add New Category')}}");

					$.post('category/all', function(data){
						$('.categorysub').html(data);
						$('.categorysub').select2({
							dropdownParent: ".sprukosubcat",
							minimumResultsForSearch: '',
							width: '100%',
						});

					});
					$('#addtestimonial').modal('show');

					checkPro()
				});

				function checkPro(){
					let displayOpt = document.querySelectorAll('.display');
					displayOpt.forEach((ele, index)=>{
						ele.addEventListener('click', function(){
							let subCat = document.querySelector('#priority_hide');
							if(ele.value === 'knowledge'){
								subCat.style.display = "none";
								}
								else{

									subCat.style.display = "block";
							}
						})
					});
				}

				/* When click edit category */
				$('body').on('click', '.edit-testimonial', function () {
					var testimonial_id = $(this).data('id');
					$.get('categories/' + testimonial_id  + '/edit', function (data) {
						$('#nameError').html('');
						$('#displayError').html('');
						$('#priorityError').html('');
						$('.modal-title').html("{{lang('Edit Category')}}");
						$('#btnsave').val("edit-testimonial");
						$('#addtestimonial').modal('show');
						$('#testimonial_id').val(data.post.id);
						$('#name').val(data.post.name);
						if (data.post.display == "both")
						{
							$('#display').prop('checked', true);
						}
						if (data.post.display == "ticket")
						{
							$('#display1').prop('checked', true);
						}
						if (data.post.display == "knowledge")
						{
							$('#display2').prop('checked', true);
						}
						if (data.post.priority == "Low")
						{
							$('#priority').prop('checked', true);
						}
						if (data.post.priority == "Medium")
						{
							$('#priority1').prop('checked', true);
						}
						if (data.post.priority == "High")
						{
							$('#priority2').prop('checked', true);
						}
						if (data.post.status == "1")
						{
							$('#myonoffswitch18').prop('checked', true);
						}
						$('.categorysub').select2({
							dropdownParent: ".sprukosubcat",
							minimumResultsForSearch: '',
							width: '100%',

						});
						$('.categorysub').html(data.output);

						let displayOpt = document.querySelectorAll('.display');
						displayOpt.forEach((ele, index)=>{
							if(ele.checked){
								let subCat = document.querySelector('#priority_hide');
								if(ele.value === 'knowledge'){
									subCat.style.display = "none";
									}
									else{

										subCat.style.display = "block";
								}
							}
						})
						checkPro();


					})
				});

				/* When click delete category */
				$('body').on('click', '.delete-category', function () {
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
									url: SITEURL + "/admin/categories/"+_id,
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

				// Category status change
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "get",
						url: SITEURL + "/admin/categories/status"+_id,
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

				// Category group assign js
				$('body').on('click', '#assigneds', function () {
					var assigned_group = $(this).data('id');
					$('.select2_modalcategory').select2({
						minimumResultsForSearch: '',
						placeholder: "Search",
						width: '100%'
					});

					$.get('groupassigned/' + assigned_group , function (data) {
						$('#category_id').val(data.assign_data.id);
						$('#category_name').val(data.assign_data.name);
						$(".modal-title").text('{{lang('Assign To Groups')}}');
						$('#groupname').html('');
						$('#groupname').html(data.table_data);
						$('#addassigneds').modal('show');

					});
				});

				// Category form submit
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
						url: SITEURL + "/admin/categories/create",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							if(data.errors){
								$('#nameError').html('');
								$('#displayError').html('');
								$('#nameError').html(data.errors.name);
								$('#displayError').html(data.errors.display);
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');

							}
							if(data.success)
							{
								$('#nameError').html('');
								$('#displayError').html('');
								$('#testimonial_form').trigger("reset");
								$('#addtestimonial').modal('hide');
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
								toastr.success(data.success);
								location.reload();
							}

						},
						error: function(data){
							toastr.error('{{lang('Category name already exists', 'alerts')}}');
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						}
					});
				});

				// Assign group submit
				$('body').on('submit', '#group_form', function (e) {
					e.preventDefault();
					var assigned_id = $(this).data('id');
					var actionType = $('#btngroup').val();
					var fewSeconds = 2;
					$('#btngroup').html('Sending..');
					$('#btngroup').prop('disabled', true);
						setTimeout(function(){
							$('#btngroup').prop('disabled', false);
						}, fewSeconds*1000);
					var formData = new FormData(this);
					$.ajax({
						type:'POST',
						url: SITEURL + "/admin/groupcategory/group",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							$('#group_form').trigger("reset");
							$('#addassigneds').modal('hide');
							$('#btngroup').html('{{lang('Sauvegarder les modifications')}}');
							toastr.success(data.success);
							window.location.reload();
						},
						error: function(data){
							console.log('Error:', data);
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						}
					});
				});

				// Assign Envato api validation
				$('#envatoapiassign').on('click', function(){

					$('.modal-title').html("{{lang('Assign Envato Api')}}");
					$('#category_form').trigger("reset");
					$('.select2_envato').select2({

						minimumResultsForSearch: '',
						placeholder: "Search",
						width: '100%'

					});

					$.get('categorylist/' , function (data) {
						$('#categorys').html(data);
					});
					$('#addEnvatoapi').modal('show');

				});

				// Submit the form of envato api assigning
				$('body').on('submit', '#categoryenvato_form', function(e){
					e.preventDefault();
					var formData = new FormData(this);
					$.ajax({
						type:'POST',
						url: SITEURL + "/admin/categoryenvatoassign",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							toastr.success(data.success);
							$('#category_form').trigger("reset");
							$('#addEnvatoapi').modal('hide');
						},
						error: (data) => {
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
									url:"{{ route('category.deleteall')}}",
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

		@include('admin.category.modal')

				@include('admin.category.groupmodal')

				@include('admin.category.envatocategorylist')
		@endsection

