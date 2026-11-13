@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Faq Category', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->


							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('Faq Category', 'menu')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('FAQs Create')

											<a href="javascript:void(0)" class="btn btn-secondary me-3" id="addfaqcategory">{{lang('Add Faq Category')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											@can('FAQs Delete')

											<button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@can('FAQs Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('FAQs Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th>{{lang('Faq Category Name')}}</th>
														<th>{{lang('Status')}}</th>
														<th>{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
                                                    @php $i = 1 @endphp
                                                    @foreach ($faqcategorys as $faqcategory)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>
																@if(Auth::user()->can('FAQs Delete'))
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$faqcategory->id}}" />
																@else
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$faqcategory->id}}" disabled />
																@endif
															</td>
                                                            <td>{{$faqcategory->faqcategoryname}}</td>
                                                            <td>
                                                                @if(Auth::user()->can('FAQs Edit'))
																	@if($faqcategory->status == '1')

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="privatemode" data-id="{{$faqcategory->id}}"  value="1" class="custom-switch-input tswitch" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@else

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="privatemode" data-id="{{$faqcategory->id}}"  value="1" class="custom-switch-input tswitch">
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@endif
																@else
																	~
																@endif

                                                            </td>
                                                            <td>
                                                                <div class = "d-flex">
																	@if(Auth::user()->can('FAQs Edit'))

																		<a href="javascript:void(0)" data-id="{{$faqcategory->id}}" class="action-btns1" id="faqcategoryedit">
																			<i class="feather feather-edit text-primary" data-id="{{$faqcategory->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
																		</a>
																	@else
																		~
																	@endif
																	@if(Auth::user()->can('FAQs Delete'))
																		<a href="javascript:void(0)" data-id="{{$faqcategory->id}}" class="action-btns1" id="faqcategorydelete">
																			<i class="feather feather-trash-2 text-danger" data-id="{{$faqcategory->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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
	@section('modal')

   	@include('admin.faq.faqcategorymodel')

	@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Summernote js  -->
		<script src="{{asset('assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{asset('assets/js/support/support-articles.js')}}"></script>

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
                //     responsive: true,
                //     language: {
                //         searchPlaceholder: search,
                //         scrollX: "100%",
                //         sSearch: '',
                //     },
                //     order:[],
                //     columnDefs: [
                //         { "orderable": false, "targets":[ 0,1,4] }
                //     ],
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


                //Add New FAQ Category
                $('#addfaqcategory').on('click', function(){
                    $("#faqcat_id").val('');
                    $(".modal-title").text('{{lang('Add Faq Category')}}');
                    $('#faqcat_form').trigger("reset");

                    $('#addfaqcat').modal('show');
                });

                // Edit faq Category
                $('body').on('click', '#faqcategoryedit', function(){

                    let faqcat_id = $(this).data('id');
                    $('#faqcatname').val('')
                    $("#faqcat_id").val(faqcat_id);
					$(".modal-title").text('{{lang('Add Faq Category')}}');
                    $.get('faqcategory/edit/' + faqcat_id, function(data){

                        $('#faqcatname').val(data.faqcategoryname)
                        if(data.status == '1'){

                            $('#status').prop('checked', true)
                        }else{

                            $('#status').prop('checked', false)
                        }
                        $('#addfaqcat').modal('show');
                    });

                });

                // Delete Faq Category
				$('body').on('click', '#faqcategorydelete', function () {
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
								url: SITEURL + "/admin/faqcategory/delete/" + _id,
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

                // Mass Delete Faq Category
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
									url:"{{ route('faqcategory.deleteall')}}",
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


                // Status change Faq Category
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/faqcategory/status/"+_id,
						data: {'status': status},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

                // Form Submit
                $('body').on('submit', '#faqcat_form', function(e){

                    e.preventDefault();

                    var actionType = $('#faqcatbtn').val();
					var fewSeconds = 2;
					$('#faqcatbtn').html('Sending..');
					$('#faqcatbtn').prop('disabled', true);
						setTimeout(function(){
							$('#faqcatbtn').prop('disabled', false);
                            $('#faqcatbtn').html('Save');
						}, fewSeconds*1000);
					var formData = new FormData(this);
					$.ajax({
					type:'POST',
					url: SITEURL + "/admin/faqcategory/store",
					data: formData,
					cache:false,
					contentType: false,
					processData: false,

					success: (data) => {
						$('#faqcatbtn').html('Save');
						toastr.success(data.success);
						location.reload();
					},
					error: function(data){
						$('#faqcatbtn').html('Save');
						$('#faqcatnameError').html(data.responseJSON.errors.faqcategoryname)
					}
					});
                })

            })(jQuery);

        </script>

		@endsection
