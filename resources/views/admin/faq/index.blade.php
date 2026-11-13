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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('FAQ’s', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<form method="POST" action="{{url('/admin/faq')}}" enctype="multipart/form-data">
										@csrf

										@honeypot

										<div class="card-header border-0 d-sm-max-flex">
											<h4 class="card-title">{{lang('FAQ’s Section')}}</h4>
											<div class="card-options card-header-styles mt-sm-max-2">
												<small class="me-1 mt-1">{{lang('Show Section')}}</small>
												<div class="float-end mt-0">
													<div class="switch-toggle">
														<a class="onoffswitch2">
															<input type="checkbox"  name="faqcheck" id="faqchecks" class=" toggle-class onoffswitch2-checkbox" value="on" @if($basic->faqcheck == 'on')  checked=""  @endif>
															<label for="faqchecks" class="toggle-class onoffswitch2-label" ></label>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="card-body" >
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<input type="hidden" class="form-control " id="testimonial_id" name="id" value="{{$basic->id}}">
													<div class="form-group">
														<label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('faqtitle') is-invalid @enderror" name="faqtitle" value="{{$basic->faqtitle}}">
														@error('faqtitle')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">{{lang('Subtitle')}}</label>
														<input type="text" class="form-control @error('faqsub') is-invalid @enderror" name="faqsub" value="{{$basic->faqsub}}">
														@error('faqsub')

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
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">{{lang('FAQ’s', 'menu')}}</h4>
										<div class="card-options mt-sm-max-2">
											@can('FAQs Create')
                                                <a href="{{route('faq.create')}}" class="btn btn-secondary me-3" id="create-new-post">{{lang('Add FAQ')}}</a>
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

														<th >{{lang('Question')}}</th>
														<th >{{lang('Answer')}}</th>
														<th >{{lang('Faq Category', 'menu')}}</th>
														<th >{{lang('Privacy Mode')}}</th>
														<th class="w-5">{{lang('Status')}}</th>
														<th class="w-5">{{lang('Actions')}}</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($faqs as $faq)
														<tr>
															<td>{{$i++}}</td>
															<td>
																@if(Auth::user()->can('FAQs Delete'))
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$faq->id}}" />
																@else
																	<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$faq->id}}" disabled />
																@endif
															</td>
															<td>{{Str::limit($faq->question, '50')}}</td>
															<td>{!! strip_tags( \Illuminate\Support\Str::words($faq->answer, 5,'...')) !!}</td>
															<td>
																@if($faq->faqcat_id != null)
																@if($faq->faqcategory != null)

																{{$faq->faqcategory->faqcategoryname}}
																@else
																~
																@endif
																@else
																~
																@endif
															</td>
															<td>
																@if(Auth::user()->can('FAQs Edit'))
																	@if($faq->privatemode == '1')

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="privatemode" data-id="{{$faq->id}}" id="privatemode{{$faq->id}}" value="1" class="custom-switch-input tswitch1" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@else

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="privatemode" data-id="{{$faq->id}}" id="privatemode{{$faq->id}}" value="1" class="custom-switch-input tswitch1">
																			<span class="custom-switch-indicator"></span>
																		</label>

																	@endif
																@else
																	~
																@endif
															</td>
															<td>
																@if(Auth::user()->can('FAQs Edit'))
																	@if($faq->status == '1')

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$faq->id}}" id="myonoffswitch{{$faq->id}}" value="1" class="custom-switch-input tswitch" checked>
																			<span class="custom-switch-indicator"></span>
																		</label>
																	@else

																		<label class="custom-switch form-switch mb-0">
																			<input type="checkbox" name="status" data-id="{{$faq->id}}" id="myonoffswitch{{$faq->id}}" value="1" class="custom-switch-input tswitch">
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

                                                                        <a href="{{route('faq.edit',$faq->id)}}" class="action-btns1">
                                                                            <i class="feather feather-edit text-primary" data-id="{{$faq->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
                                                                        </a>
																	@else
																		~
																	@endif
																	@if(Auth::user()->can('FAQs Delete'))
																		<a href="javascript:void(0)" data-id="{{$faq->id}}" class="action-btns1"  onclick="deletePost(event.target)">
																			<i class="feather feather-trash-2 text-danger" data-id="{{$faq->id}} " data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
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

   	@include('admin.faq.model')

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
				// 	responsive: true,
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		// { "orderable": false, "targets":[ 0,1,6] }
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
									url:"{{ route('faq.deleteall')}}",
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

				// Status change faq
				$('body').on('click', '.tswitch', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/faq/status"+_id,
						data: {'status': status},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

				// privatemode change faq
				$('body').on('click', '.tswitch1', function () {
					var _id = $(this).data("id");
					var privatemode = $(this).prop('checked') == true ? '1' : '0';
					$.ajax({
						type: "post",
						url: SITEURL + "/admin/faq/privatestatus/"+_id,
						data: {'privatemode': privatemode},
						success: function (data) {
							toastr.success(data.success);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				});

			})(jQuery);

			// Add faq
			function addPost() {
                $("#faq_id").val('');
                $(".modal-title").text('{{lang('Add New FAQ')}}');
				$('#faq_form').trigger("reset");
				$('#answer').summernote({
					callbacks: {
						onPaste: function (e) {
							var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
							e.preventDefault();
							document.execCommand('insertText', false, bufferText);
						}
					},
                disableDragAndDrop:true,
				});
				$('#answer').summernote('reset');
				$('#faqcat_name').select2({
					dropdownParent: ".sprukofaqcat",
					minimumResultsForSearch: '',
					placeholder: "Search",
					width: '100%'
				});
				$.get('faqcategory/list', function(data){

					$('#faqcat_name').html(data);
                });
                $('#addfaq').modal('show');
            }

			// edit faq
            function editPost(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/faq/${id}')}}`;
                $('#questionError').text('');
                $('#answerError').text('');
				$('#answer').summernote({
					callbacks: {
						onPaste: function (e) {
							var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
							e.preventDefault();
							document.execCommand('insertText', false, bufferText);
						}
					},
				});
				$('#faqcat_name').select2({
					dropdownParent: ".sprukofaqcat",
					minimumResultsForSearch: '',
					placeholder: "Search",
					width: '100%'
				});
                $.ajax({
                	url: _url,
               		type: "GET",
                	success: function(response) {
                    	if(response) {
							$('#questionError').text('');
                			$('#answerError').text('');
                     	   	$(".modal-title").text('{{lang('Edit FAQ')}}');
                        	$("#faq_id").val(response.post.id);
                        	$("#question").val(response.post.question);
                        	$("#answer").summernote('code',response.post.answer);
							$('#faqcat_name').html(response.faqcatlist);
							if (response.post.status == "1")
							{
								$('#status').prop('checked', true);
							}
							if (response.post.privatemode == "1")
							{
								$('#privatemode').prop('checked', true);
							}
                        	$('#addfaq').modal('show');
                   		}
                	}
                });
            }

			// Delete faq
            function deletePost(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/faq/delete/${id}')}}`;
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

			// create the faq
            function createPost() {
				$('#questionError').text('');
                $('#answerError').text('');
                var question = $('#question').val();
                var answer = $('#answer').val();
                var faqcatsname = $('#faqcat_name').val();
				var status = $('#status').prop('checked') == true ? '1' : '0';
				var privatemode = $('#privatemode').prop('checked') == true ? '1' : '0';
                var id = $('#faq_id').val();
				var actionType = $('#btnsave').val();
				var fewSeconds = 2;
				$('#btnsave').prop('disabled', true);
					setTimeout(function(){
						$('#btnsave').prop('disabled', false);
					}, fewSeconds*1000);
                let _url = `{{url('/admin/faq/create')}}`;
                let _token   = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url:_url,
                    type:"POST",
                    data:{
                        id: id,
                        question: question,
                        answer: answer,
                        status: status,
                        privatemode: privatemode,
                        faqcatsname: faqcatsname,
                        _token: _token
                    },
                    success: function(response) {
                        if(response.code == 200) {
							$('#questionError').text('');
                			$('#answerError').text('');
							$('#faq_form').trigger("reset");
							$('#answer').summernote('reset');
							$('#addfaq').modal('hide');
                            toastr.success(response.success);
							location.reload();
                        }
                    },
                    error: function(response) {
						$('#questionError').text('');
                		$('#answerError').text('');
                        $('#questionError').text(response.responseJSON.errors.question);
                        $('#answerError').text(response.responseJSON.errors.answer);
                        $('#faqcategoryError').text(response.responseJSON.errors.faqcatsname);
                    }
                });

            }

			// cancel faq
			function cancelPost() {
				$('#faq_form').trigger("reset");
				$('#answer').summernote('reset');
			}

		</script>

		@endsection
