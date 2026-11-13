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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Groupes')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- Groups List-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-flex">
										<h4 class="card-title">{{lang('Groups List')}}</h4>
										<div class="card-options d-sm-flex d-block">
											@can('Groups Create')

											<a href="{{route('groups.create')}}" class="btn btn-secondary me-3 mt-sm-0 mt-2" ><i class="feather feather-users"></i> {{lang('Add Group')}}</a>
											@endcan
											@can('Category Access')

											<a href="{{url('/admin/categories')}}" class="btn btn-success me-3 mt-sm-0 mt-2" ><i class="feather feather-cpu"></i> {{lang('Category Assign')}}</a>
											@endcan
										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('Groups Delete')

											<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan
											<table class="table table-bordered border-bottom text-nowrap w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('Groups Delete')

                                                        <th width="10" >
                                                            <input type="checkbox"  id="customCheckAll">
                                                            <label  for="customCheckAll"></label>
                                                        </th>
                                                        @endcan
                                                        @cannot('Groups Delete')

                                                        <th width="10" >
                                                            <input type="checkbox"  id="customCheckAll" disabled>
                                                            <label  for="customCheckAll"></label>
                                                        </th>
                                                        @endcannot
														<th >{{lang('Group Name')}}</th>
														<th >{{lang('Count')}}</th>
														<th >{{lang('Status')}}</th>
														<th >{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($groups as $group)
													<tr>
														<td>{{$i++}}</td>
														<td>
															@if(Auth::user()->can('Groups Delete'))
																<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$group->id}}" />
															@else
																<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$group->id}}" disabled />
															@endif
														</td>
														<td>{{Str::limit($group->groupname, '40')}}</td>
														<td>
															<span class="badge badge-info">{{$group->groupsuser()->count()}}</span>
														</td>
                                                        <td>
                                                            @if(Auth::user()->can('Groups Edit'))
                                                                @if($group->groupstatus == '1')
                                                                    <label class="custom-switch form-switch mb-0">
                                                                    <input type="checkbox" name="groupstatus" data-id="{{$group->id}}" id="myonoffswitch{{$group->id}}" value="1" class="custom-switch-input tswitch" checked>
                                                                    <span class="custom-switch-indicator"></span>
                                                                    </label>
                                                                @else
                                                                    <label class="custom-switch form-switch mb-0">
                                                                    <input type="checkbox" name="groupstatus" data-id="{{$group->id}}" id="myonoffswitch{{$group->id}}" value="1" class="custom-switch-input tswitch">
                                                                    <span class="custom-switch-indicator"></span>
                                                                    </label>
                                                                @endif
                                                            @else
                                                            ~
                                                            @endif
                                                        </td>
														<td>
															<div class = "d-flex">
															@if(Auth::user()->can('Groups Edit'))

																<a href="{{url('admin/groups/view/'.$group->id)}}" data-id="{{$group->id}}" class="action-btns1 edit-testimonial">
																	<i class="feather feather-edit text-primary" data-id="{{$group->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																</a>

															@else
																~
															@endif

															@if(Auth::user()->can('Groups Delete'))

																<a href="javascript:void(0);" data-id="{{$group->id}}" class="action-btns1 delete-groups">
																	<i class="feather feather-trash-2 text-danger" data-id="{{$group->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
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
							<!-- End Groups List-->

							@endsection
		@section('modal')

		@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

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
				var SITEURL = "{{url('')}}";

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

				/* When click delete category */
				$('body').on('click', '.delete-groups', function () {
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
									url: SITEURL + "/admin/groups/delete/"+_id,
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
									url:"{{ route('groups.deleteall')}}",
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

				$('body').on('change', '.tswitch', function(e){

					let id = $(this).data('id'),
					 	status = $(this).prop('checked') == true ? '1' : '0';;

					$.ajax({
						url:"{{ route('groups.statuschange', 'id')}}",
						method:"post",
						data:{
							id:id,
							status:status,
						},
						success:function(data)
						{
							toastr.success(data.success);

						},
						error:function(data){
						}
					});

				});

			})(jQuery);

		</script>

		@endsection

