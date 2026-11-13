@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('IP List', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- IP List-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('IP List', 'menu')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('IpBlock Add')

											<a href="javascript:void(0)" class="btn btn-secondary me-3" id="create-new-ip">{{lang('Add IP Address')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive">
											@can('IpBlock Delete')

											<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('IpBlock Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('IpBlock Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th >{{lang('IP')}}</th>
														<th >{{lang('Pays')}}</th>
														<th >{{lang('Entry')}}</th>
														<th >{{lang('Types')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($iplists as $iplist)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(auth()->user()->can('IpBlock Delete'))
																<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$iplist->id}}" />
																@else
																<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$iplist->id}}" disabled/>
																@endif
															</td>
															<td>{{$iplist->ip}}</td>
															<td>{{$iplist->country}}</td>
															<td>{{$iplist->entrytype}}</td>
															<td>{{$iplist->types}}</td>
															<td>
																<div class = "d-flex">
																@if(auth()->user()->can('IpBlock Edit'))
																	<a href="javascript:void(0)" data-id="{{$iplist->id}}" class="action-btns1 edit-iplist">
																		<i class="feather feather-edit text-primary" data-id="{{$iplist->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																	</a>
																@else
																	~
																@endif
																@if(auth()->user()->can('IpBlock Delete'))
																	<a href="javascript:void(0)" data-id="{{$iplist->id}}" class="action-btns1" id="delete-iplist" >
																		<i class="feather feather-trash-2 text-danger" data-id="{{$iplist->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
																	</a>
																@else
																	~
																@endif
																@if($iplist->types != 'Unlock')
																	<a href="javascript:void(0)" data-id="{{$iplist->id}}" class="action-btns1" id="reset-iplist" >
																		<i class="feather feather-rotate-ccw text-success" data-id="{{$iplist->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Reset')}}"></i>
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
							<!--End IP List-->
							@endsection

		@section('scripts')

		<!--INTERNAL Owl-carousel js -->
		<script src="{{gn_asset('assets/plugins/owl-carousel/owl-carousel.js')}}"></script>

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

				// DataTable
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

				/*  When user click add ip button */
				$('#create-new-ip').on('click', function () {
					$('#btnsave').val("ipblock");
					$('#IP_id').val('');
					$('#IP_form').trigger("reset");
					$('.modal-title').html("{{lang('Add New IP Address')}}");
					$('#addIP').modal('show');
				});

				/* When click edit ip */
				$('body').on('click', '.edit-iplist', function () {
					var ip_id = $(this).data('id');
					$.get('ipblocklist/' + ip_id , function (data) {
						$('#nameError').html('');
						$('#displayError').html('');
						$('.modal-title').html("{{lang('Edit IP Address')}}");
						$('#btnsave').val("edit-ip");
						$('#addIP').modal('show');
						$('#IP_id').val(data.id);
						$('#ip').val(data.ip);
						if (data.types == "Unlock")
						{
							$('.iptype1').prop('checked', true);
						}
						if (data.types == "Locked")
						{
							$('.iptype2').prop('checked', true);
						}
						if (data.types == "Blocked")
						{
							$('.iptype3').prop('checked', true);
						}

					})
				});

				// submit ip list
				$('body').on('submit', '#IP_form', function (e) {
					e.preventDefault();
					var actionType = $('#btnsave').val();
					var fewSeconds = 2;
					$('#btnipsave').html('Sending..');
					$('#btnipsave').prop('disabled', true);
						setTimeout(function(){
							$('#btnipsave').prop('disabled', false);
						}, fewSeconds*1000);
					var formData = new FormData(this);
					$.ajax({
						type:'POST',
						url: SITEURL + "/admin/ipblocklist/create",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							$('#nameError').html('');
							$('#displayError').html('');
							$('#IP_form').trigger("reset");
							$('#addIP').modal('hide');
							$('#btnipsave').html('{{lang('Sauvegarder')}}');
							toastr.success(data.success);
							location.reload();
						},
						error: function(data){
							$('#nameError').html('');
							$('#displayError').html('');
							$('#nameError').html(data.responseJSON.errors.ip);
							$('#displayError').html(data.responseJSON.errors.types);
							$('#btnipsave').html('{{lang('Sauvegarder')}}');
						}
					});
				});

				// delete ip list
				$('body').on('click', '#delete-iplist', function () {
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
								type: "delete",
								url: SITEURL + "/admin/ipblocklist/delete/"+_id,
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

				// Mass delete ip list
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
									url:"{{ route('ipblocklist.deleteall')}}",
									method:"post",
									data:{id:id},
									success:function(data)
									{
										toastr.success(data.success);
										location.reload()

									},
									error:function(data){
										console.log(data);
									}
								});
							}
						});
					}
					else{
						toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
					}

				});

				// Reset ip js
				$('body').on('click', '#reset-iplist', function(){
					var reset_id = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to reset this record?', 'alerts')}}`,
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "post",
								url: SITEURL + "/admin/ipblocklist/reset/"+reset_id,
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

				// checkbox chaeck all
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

    		@include('admin.securitysetting.ipblockmodel')

		@endsection
