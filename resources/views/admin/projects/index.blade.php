@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

		<!-- INTERNAL Multiselect css -->
		<link href="{{gn_asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/multi/multi.min.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Projets')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- Project List-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-md-max-block">
										<h4 class="card-title mb-md-max-2">{{lang('Projets')}}</h4>
										<div class="card-options d-md-max-block">
											@can('Project Create')

											<a href="javascript:void(0)" class="btn btn-success me-3 mb-md-max-2 mw-10" id="create-new-projects">{{lang('Ajouter Projet')}}</a>
											@endcan
											@can('Project Assign')

											<a href="javascript:void(0)" class="btn btn-danger me-3  mb-md-max-2 mw-10" id="projectsassign">{{lang('Attribuer des projets')}}</a>
											@endcan
											@can('Project Importlist')

											<a href="{{route('projects.pcsvimports')}}" class="btn btn-info me-3  mb-md-max-2 mw-10" id="importfile"><i class="feather feather-download"></i> {{lang('Import file')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Project Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Project Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Project Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th >{{lang('Nom')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach ($projectss as $projectsss)

														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Project Delete'))
																	<input type="checkbox" name="project_checkbox[]" class="checkall" value="{{ $projectsss->id }}" />
																@else
																	<input type="checkbox" name="project_checkbox[]" class="checkall" value="{{ $projectsss->id }}" disabled />
																@endif
															</td>
															<td>
																{{Str::limit( $projectsss->name, '40')}}
															</td>
															<td>
																<div class = "d-flex">
																	@if(Auth::user()->can('Project Edit'))

																		<a href="javascript:void(0)" data-id="{{$projectsss->id}}" class="action-btns1 edit-testimonial">
																			<i class="feather feather-edit text-primary" data-id="{{$projectsss->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																		</a>
																	@else
																		~
																	@endif
																	@if(Auth::user()->can('Project Delete'))

																		<a href="javascript:void(0)" data-id="{{$projectsss->id}}" class="action-btns1" id="delete-testimonial" >
																			<i class="feather feather-trash-2 text-danger" data-id="{{$projectsss->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
																		</a>

																	@else
																		~
																	@endif
															</td>
														</tr>

													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- End Project List-->

							@endsection
	@section('modal')

    @include('admin.projects.model')


      		<!-- Add Projectz Assign-->
            <div class="modal fade"  id="projectsassigned" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" ></h5>
                            <button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST" enctype="multipart/form-data"  action="{{url('admin/projectsassigned')}}">
                            <input type="hidden" name="projects_id" id="projects_id">
                            @csrf
                            @honeypot
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Choisir une catégorie')}} </label>
                                    <div class="custom-controls-stacked d-md-flex" >
                                        <select multiple="multiple" class="form-control select2_modal"  name="category_id[]" data-placeholder="{{lang('Choisir une catégorie')}}" id="category" >
                                            @foreach ($categories as $category)

											<option value="{{$category->id}}" {{in_array($category->id,$check_category) ? 'selected':''}}>{{$category->name}}</option>

											@endforeach

                                        </select>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="form-label">{{lang('Projets')}}</label>
                                    <div class="custom-controls-stacked d-md-flex" id="projectdisable">
                                        <select multiple="multiple" class="filter-multi"  id="projects"  name="projected[]" >
                                            @foreach ($project as $item)

                                                <option value="{{$item->id}}" selected="">{{lang($item->name)}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a>
                                <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Sauvegarder')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End  Add Projectz Assign  -->

			<!-- INTERNAL Multiselect Js -->
            <script src="{{gn_asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
            <script src="{{gn_asset('assets/plugins/multipleselect/multi-select.js')}}"></script>
	@endsection

		@section('scripts')


		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
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

				// Datatable
				// $('#support-articlelists').DataTable({

				// 	responsive: true,
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[ 0,1,3] }
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
                $('#support-articlelists').DataTable({
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
                        { "orderable": false, "targets":[ 0,1,3] }
                    ],
                });


				/*  When user click add project button */
				$('#create-new-projects').on('click', function () {
					$('#btnsave').val("create-product");
					$('#projects_id').val('');
					$('#projects_form').trigger("reset");
					$('.modal-title').html("{{lang('Add New Project')}}");
					$('#addtestimonial').modal('show');
				});


				/* When click edit project */
				$('body').on('click', '.edit-testimonial', function () {
					var projects_id = $(this).data('id');
					$.get('projects/' + projects_id , function (data) {
						$('#nameError').html('');
						$('.modal-title').html("{{lang('Edit Project')}}");
						$('#btnsave').val("edit-project");
						$('#addtestimonial').modal('show');
						$('#projects_id').val(data.id);
						$('#name').val(data.name);
					})
				});

				// Delete Project
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
									url: SITEURL + "/admin/projects/delete/"+_id,
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

				//projects assign
				$('#projectsassign').on('click', function () {

					document.getElementById('projectdisable').style.pointerEvents = 'none';
					document.getElementById('projectdisable').style.opacity = '0.6';

					$('.select2_modal').select2({
						minimumResultsForSearch: '',
						placeholder: "Search",
						width: '100%'
					});

					$('#btnsave').val("create-project");
					$('#projects_id').val('');
					$('#projects_form').trigger("reset");
					$('.modal-title').html("{{lang('Attribuer des projets')}}");
					$('#projectsassigned').modal('show');
					$('#projects').hide();
					$.get('projects/' , function (data) {

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
									url:"{{ url('admin/massproject/delete')}}",
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

				// Project submit button
				$('body').on('submit', '#projects_form', function (e) {
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
						url: SITEURL + "/admin/projects/create",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {

							if(data.errors){
								$('#nameError').html('');
								$('#nameError').html(data.errors.name);
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
							}
							if(data.success){
								$('#nameError').html('');
								$('#projects_form').trigger("reset");
								$('#addtestimonial').modal('hide');
								$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
								toastr.success(data.success);
								location.reload();
							}
						},
						error: function(data){
							$('#nameError').html('');
							toastr.error('{{lang('Project Name is Already Exists', 'alerts')}}');
							$('#btnsave').html('{{lang('Sauvegarder les modifications')}}');
						}
					});


				});

				//Checkbox checkall
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
