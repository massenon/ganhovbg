@extends('layouts.adminmaster')

    @section('styles')

        <!-- INTERNAL Data table css -->
		<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

        <!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    @endsection
							@section('content')

                            <!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Canned Response')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="card ">
                                    <div class="card-header border-0 d-sm-flex">
                                        <h4 class="card-title">{{lang('Canned Response List')}}</h4>
                                        <div class="card-options mt-sm-max-2">
                                            @can('Canned Response Create')

                                            <a href="{{route('admin.cannedmessages.create')}}" class="btn btn-secondary me-3" >{{lang('Add Canned Response')}}</a>
                                            @endcan

                                        </div>
                                    </div>
                                    <div class="card-body" >
                                        <div class="table-responsive spruko-delete">
                                            @can('Canned Response Delete')

                                            <button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
                                            @endcan

                                            <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="cannedmessages">
                                                <thead>
                                                    <tr>
                                                        <th  width="10">{{lang('Sl.No')}}</th>
                                                        @can('Canned Response Delete')

                                                        <th width="10" >
                                                            <input type="checkbox"  id="customCheckAll">
                                                            <label  for="customCheckAll"></label>
                                                        </th>
                                                        @endcan
                                                        @cannot('Canned Response Delete')

                                                        <th width="10" >
                                                            <input type="checkbox"  id="customCheckAll" disabled>
                                                            <label  for="customCheckAll"></label>
                                                        </th>
                                                        @endcannot

                                                        <th class="w-50">{{lang('Title')}}</th>
                                                        <th class="w-50">{{lang('Status')}}</th>
                                                        <th class="w-50">{{lang('Actions')}}</th>
                                                    </tr>
                                                </thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach ($cannedmessages as $cannedmsg)
														<tr>
															<td>{{ $i++ }}</td>
															<td>
																@if(Auth::user()->can('Canned Response Delete'))
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$cannedmsg->id}}" />
																@else
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$cannedmsg->id}}" disabled />
																@endif
															</td>
															<td>{{$cannedmsg->title}}</td>
															<td>
																@if(Auth::user()->can('Canned Response Edit'))
																	@if($cannedmsg->status == '1')

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$cannedmsg->id}}" id="myonoffswitch{{$cannedmsg->id}}" value="1" class="custom-switch-input tswitch" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@else

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$cannedmsg->id}}" id="myonoffswitch{{$cannedmsg->id}}" value="1" class="custom-switch-input tswitch">
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@endif
																@else{
																	~
																@endif
															</td>
															<td>
																<div class = "d-flex">
																	@if(Auth::user()->can('Canned Response Edit'))

																		<a href="{{route('admin.cannedmessages.edit',$cannedmsg->id)}}" class="action-btns1 edit-testimonial"><i class="feather feather-edit text-primary" data-id="{{$cannedmsg->id}}"data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Edit')}}"></i></a>
																	@else
																		~
																	@endif
																	@if(Auth::user()->can('Canned Response Delete'))
																		<a href="javascript:void(0)" data-id="{{$cannedmsg->id}}" class="action-btns1" id="delete-cannedmessages" ><i class="feather feather-trash-2 text-danger" data-id="{{$cannedmsg->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Delete')}}"></i></a>
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

        <!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

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
				// $('#cannedmessages').dataTable({
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
                        $('#cannedmessages').dataTable({
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

                // Status change article
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';

					$.ajax({
						type: "post",
						url: SITEURL + "/administration/cannedmessages/status/"+_id,
						data: {'status': status},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

                // Delete Testimonial
				$('body').on('click', '#delete-cannedmessages', function () {
					var _id = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to continue?')}}`,
						text: "{{lang('This might erase your records permanently')}}",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "post",
								url: SITEURL + "/administration/cannedmessages/delete/"+_id,
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
							title: `{{lang('Are you sure you want to continue?')}}`,
							text: "{{lang('This might erase your records permanently')}}",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						})
						.then((willDelete) => {
							if (willDelete) {
								$.ajax({
									url:"{{ route('admin.cannedmessages.deleteall')}}",
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
						toastr.error('{{lang('Please select at least one check box.')}}');
					}
				});

            })(jQuery);
        </script>
        @endsection
