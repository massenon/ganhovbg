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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Articles', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->


							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<form method="POST" action="{{url('/admin/article')}}" enctype="multipart/form-data">
										@csrf

										@honeypot

										<div class="card-header d-sm-max-flex  border-0">
											<h4 class="card-title">{{lang('Article Section')}}</h4>
											<div class="card-options mt-sm-max-2 card-header-styles">
												<small class="me-1 mt-1">{{lang('Hide Section')}}</small>
												<div class="float-end mt-0">
													<div class="switch-toggle">
														<a class="onoffswitch2">
															<input type="checkbox"  name="articlecheck" id="articlechecks" class=" toggle-class onoffswitch2-checkbox" value="on" @if($basic->articlecheck == 'on')  checked=""  @endif>
															<label for="articlechecks" class="toggle-class onoffswitch2-label" ></label>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="card-body" >
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<input type="hidden" class="form-control " name="id" value="{{$basic->id}}">
													<div class="form-group">
														<label class="form-label">{{lang('Title')}}</label>
														<input type="text" class="form-control @error('articletitle') is-invalid @enderror" name="articletitle" value="{{$basic->articletitle}}">
														@error('articletitle')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">{{lang('Subtitle')}}</label>
														<input type="text" class="form-control @error('articlesub') is-invalid @enderror" name="articlesub" value="{{$basic->articlesub}}">
														@error('articlesub')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12 card-footer ">
											<div class="form-group float-end">
												<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card mb-0">
									<div class="card-header d-sm-max-flex border-0">
										<h4 class="card-title">{{lang('Article List')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('Article Create')

											<a href="{{url('/admin/article/create')}}" class="btn btn-secondary me-3" >{{lang('Add Article')}}</a>
											@endcan

										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive spruko-delete">
											@can('Article Delete')

											<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
											@endcan

											<table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="articlelist">
												<thead>
													<tr>
														<th  width="9">{{lang('Sl.No')}}</th>
														@can('Article Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll">
															<label  for="customCheckAll"></label>
														</th>
														@endcan
														@cannot('Article Delete')

														<th width="10" >
															<input type="checkbox"  id="customCheckAll" disabled>
															<label  for="customCheckAll"></label>
														</th>
														@endcannot

														<th  >{{lang('Article Title')}}</th>
														<th >{{lang('Catégorie')}}</th>
														<th >{{lang('Privacy Mode')}}</th>
														<th class="w-5">{{lang('Status')}}</th>
														<th class="w-5">{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach ($articles as $article)
													<tr>
														<td>{{$i++}}</td>
														<td>
															@if(Auth::user()->can('Project Delete'))
																<input type="checkbox" name="article_checkbox[]" class="checkall" value="{{$article->id}}" />
															@else
																<input type="checkbox" name="article_checkbox[]" class="checkall" value="{{$article->id}}" disabled />
															@endif
														</td>
														<td>{{Str::limit($article->title, '40')}}</td>
														<td>
															@if($article->category != null)

																{{Str::limit($article->category->name, '40')}}
															@else
																~
															@endif
														</td>
														<td>
															@if(Auth::user()->can('Article Edit'))
																@if($article->privatemode == '1')

																	<label class="custom-switch form-switch mb-0">
																		<input type="checkbox" name="privatemode" data-id="{{$article->id}}" id="privatemode{{$article->id}}" value="1" class="custom-switch-input tswitch1" checked>
																		<span class="custom-switch-indicator"></span>
																	</label>
																@else

																	<label class="custom-switch form-switch mb-0">
																		<input type="checkbox" name="privatemode" data-id="{{$article->id}}" id="privatemode{{$article->id}}" value="1" class="custom-switch-input tswitch1">
																		<span class="custom-switch-indicator"></span>
																	</label>

																@endif
															@else
																~
															@endif
														</td>
														<td>
															@if(Auth::user()->can('Article Edit'))
																@if($article->status == 'Published')

																<label class="custom-switch form-switch mb-0">
																	<input type="checkbox" name="status" data-id="{{$article->id}}" id="myonoffswitch{{$article->id}}" value="Published" class="custom-switch-input tswitch" checked>
																	<span class="custom-switch-indicator"></span>
																</label>

																@else

																<label class="custom-switch form-switch mb-0">
																	<input type="checkbox"  name="status" data-id="{{$article->id}}" id="myonoffswitch{{$article->id}}" class="custom-switch-input tswitch" value="Published">
																	<span class="custom-switch-indicator"></span>
																</label>

																@endif
															@else
																~
															@endif
														</td>
														<td>
															<div class = "d-flex">
															@if(Auth::user()->can('Article Edit'))
															<a href="{{url('/admin/article/'.$article->id .'/edit')}}" class="action-btns1" >
																<i class="feather feather-edit text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
															</a>
															@else
																~
															@endif
															@if(Auth::user()->can('Article View'))
																@if($article->articleslug != null)
																	<a href="{{url('article/'.$article->articleslug )}}" class="action-btns1" target="_blank" >
																	<i class="feather feather-eye text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i>
																</a>
																@else
																	<a href="{{url('article/'.$article->id )}}" class="action-btns1" target="_blank" >
																	<i class="feather feather-eye text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i>
																</a>
																@endif
															@endif
															@if(Auth::user()->can('Article Delete'))
																<a href="javascript:void(0)" class="action-btns1" data-id="{{$article->id}}" id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"><i class="feather feather-trash-2 text-danger"></i></a>
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

			(function($)  {
				"use strict";

				// Variables
				var SITEURL = '{{url('')}}';

				// Csrf Field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				// Datatable
				// $('#articlelist').DataTable({

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
                $('#articlelist').dataTable({
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

				// Delete button article
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
								type: "get",
								url: SITEURL + "/admin/article/"+_id,
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

				// Status change article
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? 'Published' : 'UnPublished';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/article/status"+_id,
						data: {'status': status},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

				// privatemode change article
				$('body').on('click', '.tswitch1', function () {
					var _id = $(this).data("id");
					var privatemode = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/article/privatestatus/"+_id,
						data: {'privatemode': privatemode},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

				// Mass Delete
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
									url:"{{ url('admin/massarticle/delete')}}",
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

				// Checkbox check all
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
