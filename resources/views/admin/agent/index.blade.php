@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Employees')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-md-max-block">
										<h4 class="card-title  mb-md-max-2">{{lang('Liste des Prestataires')}}</h4>
										<div class="card-options  d-md-max-block">
											@can('Employee Create')

											<a href="{{url('admin/employee/create')}}" class="btn btn-success me-3  mb-md-max-2 mw-12"><i class="feather feather-user-plus"></i> {{lang('Add Employee')}}</a>
											@endcan
											@can('Employee Importlist')

											<a href="{{route('user.userimport')}}" class="btn btn-info me-3  mb-md-max-2 mw-12"><i class="feather feather-download"></i> {{lang('Import Employees List')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Employee Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="supportuserlist">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Employee Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Employee Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th > {{lang('Nom du Prestataire')}}</th>
														<th > {{lang('Roles')}}</th>
														<th > {{lang('Date d\'enregistrement')}}</th>
														<th class="w-5"> {{lang('Status')}}</th>
														<th > {{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
														@php $i = 1; @endphp
														@foreach ($users as $user)
															<tr id="row_id{{$user->id}}">
																<td>{{$i++}}</td>
																<td>
																	@if(Auth::user()->can('Employee Delete'))

																	@if(Auth::check() && Auth::user()->id == '1')
																		@if(Auth::user()->id != $user->id )

																		<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$user->id}}" />
																			@endif
																		@else
																			@if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

																			<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$user->id}}" />

																		@endif

																	@endif

																	@else
																		@if(Auth::check() && Auth::user()->id == '1')
																			@if(Auth::user()->id != $user->id )
																			<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$user->id}}" disabled />

																			@endif
																		@else
																			@if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')
																				<input type="checkbox" name="customer_checkbox[]" class="checkall" value="{{$user->id}}" disabled />

																			@endif

																		@endif

																	@endif
																</td>
																<td>
																	<div>
																		<a href="#" class="h5">{{Str::limit($user->name, '40')}}</a>

																		</div>
																		<small class="fs-12 text-muted"> <span class="font-weight-normal1">{{Str::limit($user->email, '40')}}</span></small>
																</td>
																<td>
																	@if(!empty($user->getroleNames()[0]))
																		<span>{{Str::limit($user->getroleNames()[0], '40')  }}</span>
																	@else
																		~
																	@endif
																</td>
																<td>
																	<span class="badge badge-success-light">{{$user->created_at->translatedFormat(setting('date_format'))}}</span>
																</td>

																<td>
																	@if(Auth::user()->can('Employee Status'))


																	@if(Auth::check() && Auth::user()->id == '1')
																		@if(Auth::user()->id != $user->id )

																			<label class="custom-switch form-switch mb-0">
																				<input type="checkbox"  name="status" data-id="{{$user->id}}" id="myonoffswitch{{$user->id}}" value="1" class="custom-switch-input tswitch" {{$user->status == 1 ? 'checked' : ''}}>
																				<span class="custom-switch-indicator"></span>
																			</label>
																			@endif
																		@else
																			@if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

																			<label class="custom-switch form-switch mb-0">
																				<input type="checkbox"  name="status" data-id="{{$user->id}}" id="myonoffswitch{{$user->id}}" value="1" class="custom-switch-input tswitch" {{$user->status == 1 ? 'checked' : ''}}>
																				<span class="custom-switch-indicator"></span>
																			</label>
																		@endif

																	@endif


																		{{-- @if(!empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin') --}}
																		@if($user->id != '1')



																		@endif

																	@else
																		~
																	@endif
																</td>
																<td>
																	<div class = "d-flex">
																		@if(Auth::user()->can('Employee Edit'))

																			@if(Auth::check() && Auth::user()->id == '1')

																			<a href="{{url('/admin/agentprofile/' . $user->id)}}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"><i class="feather feather-edit text-primary"></i></a>
																			@else
																				@if($user->id != '1' && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

																				<a href="{{url('/admin/agentprofile/' . $user->id)}}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"><i class="feather feather-edit text-primary"></i></a>

																				@endif
																			@endif


																		@else
																			~
																		@endif
																		@if(Auth::user()->can('Employee Delete'))

																			@if(Auth::check() && Auth::user()->id == '1')
																				@if(Auth::user()->id != $user->id )

																					<a href="javascript:void(0)" class="action-btns1" data-id="{{$user->id}}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"><i class="feather feather-trash-2 text-danger"></i></a>
																				@endif
																			@else
																				@if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

																					<a href="javascript:void(0)" class="action-btns1" data-id="{{$user->id}}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"><i class="feather feather-trash-2 text-danger"></i></a>
																				@endif

																			@endif
																		@else
																			~
																		@endif
                                                                        @if(Auth::user()->can('Reset Password'))
                                                                            @if(Auth::check() && Auth::user()->id == '1')
                                                                                <a href="javascript:void(0)" class="action-btns1" data-id="{{$user->id}}" id="reset-password" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Réinitialiser le mot de passe')}}"><i class="feather feather-lock text-info"></i></a>
                                                                            @else
                                                                                @if($user->id != '1' && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

                                                                                    <a href="javascript:void(0)" class="action-btns1" data-id="{{$user->id}}" id="reset-password" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Réinitialiser le mot de passe')}}"><i class="feather feather-lock text-info"></i></a>

                                                                                @endif
                                                                            @endif
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


		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<script type="text/javascript">
			var SITEURL = '{{url('')}}';
			(function($) {
				"use strict";

				// Csrf Field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				// Datatable
				// $('#supportuserlist').DataTable({
				// 	"columnDefs": [
                //        { "orderable": false, "targets":[ 0,1] }
				// 			],
				// 	"order": [],
				// 	responsive:true,
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
                $('#supportuserlist').dataTable({
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

				// __Select2 js
				$('.form-select').select2({
					minimumResultsForSearch: -1
				});

				// __Check All checkbox
				$('#customCheckAll').on('click', function() {
					$('.checkall').prop('checked', this.checked);

				});

				// Check all js when if all selected check all
				$('.checkall').on('click', function(){
					if($('.checkall:checked').length == $('.checkall').length){
						$('#customCheckAll').prop('checked', true);
					}else{
						$('#customCheckAll').prop('checked', false);
					}
				});
				// Delete Button
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
									type: "post",
									url: SITEURL + "/admin/agent/"+_id,
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

				// status switch
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/agent/status/"+_id,
						data: {'status': status},
						success: function (data) {

						toastr.success(data.success);
						},
						error: function (data) {
						console.log('Error:', data);
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
									url:"{{ url('admin/massuser/deleteall')}}",
									method:"post",
									data:{id:id},
									success:function(data)
									{

									//for client side
									$.each(id, function( index, value ) {
										$('#row_id'+ value).remove();
									});

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

				$('body').on('click', '#reset-password', function(){
					let psdsprukochange = $(this).data('id');

					$('.modal-title').html('{{lang('Change Password')}}')
					$('#sprukopasswordreset_form').trigger('reset')

					$('.sprukopasswordreset_id').val(psdsprukochange)
					$('#addpasswordreset').modal('show');
				});
				$('body').on('submit', '#sprukopasswordreset_form', function(e){
					e.preventDefault();

					var actionType = $('#sprukoempchange').val();
					var fewSeconds = 2;
					$('#sprukoempchange').html('Sending..');
					$('#sprukoempchange').prop('disabled', true);
						setTimeout(function(){
							$('#sprukoempchange').prop('disabled', false);
							$('#sprukoempchange').html('Save');
						}, fewSeconds*1000);
					var formData = new FormData(this);
					$.ajax({
						type:'POST',
						url: '{{url("admin/employeepasswordreset")}}',
						data: formData,
						cache:false,
						contentType: false,
						processData: false,

						success: (data) => {
							toastr.success(data.success);
							location.reload();
						},
						error: function(data){

						}
					});

				})

			})(jQuery);

		</script>
		@endsection

		@section('modal')
			@include('admin.agent.passwordresetmodal')
		@endsection

