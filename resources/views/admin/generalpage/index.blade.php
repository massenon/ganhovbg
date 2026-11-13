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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Pages', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- Privacy Policy & Terms of Use List -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Pages', 'menu')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Pages Create')
                                                <a href="{{route('pages.createpage')}}" class="btn btn-secondary me-3">{{lang('Create Page')}}</a>
											@endcan
										</div>
									</div>
									<div class="card-body" >
										<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
										<div class="table-responsive">
											<table class="table table-bordered border-bottom text-nowrap w-100" id="support-articlelists">
												<thead>
													<tr>
														<th  width="10">{{lang('Sl.No')}}</th>
														@cannot('Pages Delete')

														<th>
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot
														@can('Pages Delete')

														<th>
															<input type="checkbox"  id="customCheckAll" >
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														<th >{{lang('Nom')}}</th>
														<th >{{lang('Created Date')}} </th>
														<th >{{lang('Action')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($page as $pages)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('Pages Delete'))

																<input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$pages->id}}" />
																@else

																<input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$pages->id}}" disabled />
																@endif
															</td>
															<td>{{Str::limit($pages->pagename, '40')}}</td>
															<td>{{$pages->created_at->translatedFormat(setting('date_format'))}}</td>
															<td>
																<div class = "d-flex">
																@if(Auth::user()->can('Pages Edit'))

                                                                    <a href="{{route('pages.show',$pages->id)}}" class="action-btns1">
                                                                        <i class="feather feather-edit text-primary" data-id="{{$pages->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
                                                                    </a>
																@else
																	~
																@endif
																@if(Auth::user()->can('Pages View'))

																	<a href="{{url('page/'. $pages->pageslug)}}"  class="action-btns1" target="_blank" >
																		<i class="feather feather-eye text-primary"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i>
																	</a>
																@else
																	~
																@endif
																@if(Auth::user()->can('Pages Delete'))
																	<a href="javascript:void(0)" class="action-btns1 delete-pages" data-id="{{$pages->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Supprimer')}}" aria-label="Supprimer">
																		<i class="feather feather-trash-2 text-danger" data-id="{{$pages->id}}"></i>
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
							<!-- End Privacy Policy & Terms of Use List -->

							@endsection

		@section('scripts')

		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{gn_asset('assets/js/support/support-articles.js')}}"></script>

		<!--File BROWSER -->
		<script src="{{gn_asset('assets/js/form-browser.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


        <script type="text/javascript">

			"use strict";

			(function($)  {

				// Variables
				var SITEURL = '{{url('')}}';

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

				/* When click delete pages */
				$('body').on('click', '.delete-pages', function () {
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
								url: SITEURL + "/admin/pagesdelete/"+_id,
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
				$('body').on('click', '#massdelete', function () {
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
									url:"{{ route('deleteall')}}",
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

    		@include('admin.generalpage.model')

		@endsection
