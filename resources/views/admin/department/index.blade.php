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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Département')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('Département')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Department Create')

											<a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-department" onclick="adddepartment()">{{lang('Ajouter Département')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Department Delete')

											<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Department Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Department Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th >{{lang('Nom du département')}}</th>
														<th class="w-5">{{lang('Status')}}</th>
														<th class="w-5">{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													 @foreach($departments as $department)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Department Delete'))
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$department->id}}" />
																@else
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$department->id}}" disabled />
																@endif
															</td>
															<td>{{Str::limit($department->departmentname)}}</td>
															<td>
																@if(Auth::user()->can('Department Edit'))
																	@if($department->status == '1')

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$department->id}}" id="myonoffswitch{{$department->id}}" value="1" class="custom-switch-input tswitch" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>
																	@else

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$department->id}}" id="myonoffswitch{{$department->id}}" value="1" class="custom-switch-input tswitch">
																			<span class="custom-switch-indicator"></span>
																		</label>
																	@endif
																@else
																	~
																@endif
															</td>
															<td>
																<div class = "d-flex">
																	@if(Auth::user()->can('Department Edit'))

																		<a href="javascript:void(0)" data-id="{{$department->id}}" onclick="editdepartment(event.target)" class="action-btns1">
																			<i class="feather feather-edit text-primary" data-id="{{$department->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																		</a>
																	@else
																		~
																	@endif
																	@if(Auth::user()->can('Department Delete'))
																		<a href="javascript:void(0)" data-id="{{$department->id}}" class="action-btns1"  onclick="deletedepartment(event.target)">
																			<i class="feather feather-trash-2 text-danger" data-id="{{$department->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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
							@endsection


                            @section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

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
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[ 1,3,4] }
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
									url:"{{ route('department.deleteall')}}",
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
				//Mass Delete

				// checkbox check all
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

				// Status change department
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/department/status"+'/'+_id,
						data: {'status': status},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});



			})(jQuery);

			// Add department
			function adddepartment() {
                $("#department_id").val('');
                $(".modal-title").text('{{lang('Add New Department')}}');
				$('#department_form').trigger("reset");
                $('#adddepartment').modal('show');
            }

			// edit department
            function editdepartment(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/department/edit/${id}')}}`;
                $('#departmentnameError').text('');

				$(".modal-title").text('{{lang('Edit department')}}');
                $.ajax({
                	url: _url,
               		type: "GET",
                	success: function(response) {
                    	if(response) {
							$('#departmentnameError').text('');
                			$('#answerError').text('');
                        	$("#department_id").val(response.id);
                        	$("#departmentname").val(response.departmentname);
							if (response.status == "1")
							{
								$('#status').prop('checked', true);
							}

                        }
                        $('#adddepartment').modal('show');
                	}
                });
            }

			// Delete department
            function deletedepartment(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/department/delete/${id}')}}`;
                let _token   = $('meta[name="csrf-token"]').attr('content');
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
							url: _url,
							type: 'DELETE',
							data: {
								_token: _token
							},
							success: function(response) {
								toastr.success(response.success);
								location.reload();
							},
							error: function (data) {
								console.log('Error:', data);
							}
						});
					}
				});
            }

			// create the department
            function createdepartment() {
				$('#departmentnameError').text('');
                $('#answerError').text('');
                var departmentname = $('#departmentname').val();
				var status = $('#status').prop('checked') == true ? '1' : '0';

                var id = $('#department_id').val();
				var actionType = $('#btnsave').val();
				var fewSeconds = 2;
				$('#btnsave').prop('disabled', true);
					setTimeout(function(){
						$('#btnsave').prop('disabled', false);
					}, fewSeconds*1000);
                let _url = `{{route('department.create')}}`;
                let _token   = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url:_url,
                    type:"POST",
                    data:{
                        id: id,
                        departmentname: departmentname,
                        status: status,
                        _token: _token
                    },
                    success: function(response) {
                        if(response.code == 200) {
							$('#departmentnameError').text('');
							$('#department_form').trigger("reset");
							$('#adddepartment').modal('hide');
                            toastr.success(response.success);
							location.reload();

                        }
                    },
                    error: function(response) {
						$('#departmentnameError').text('');
                        $('#departmentnameError').text(response.responseJSON.errors.departmentname);

                    }
                });

            }

			// cancel department
			function canceldepartment() {
				$('#department_form').trigger("reset");
			}

		</script>

		@endsection


        @section('modal')

   	@include('admin.department.model')

	@endsection
