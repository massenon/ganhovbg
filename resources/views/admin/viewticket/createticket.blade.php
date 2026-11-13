
@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<!-- INTERNAl Dropzone css -->
<link href="{{gn_asset('assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />


@endsection

					@section('content')

					<!--Page header-->
					<div class="page-header d-xl-flex d-block">
						<div class="page-leftheader">
							<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Créer une Alerte/Cas')}}</span></h1>
						</div>
					</div>
					<!--End Page header-->

					<!-- Create Ticket List-->
					<div class="col-xl-12 col-lg-12 col-md-12">
						<div class="card ">
							<div class="card-header border-0">
								<h4 class="card-title">{{lang('Nouvel Alerte')}}</h4>
							</div>
							<form method="post" id="admin_form" enctype="multipart/form-data">
								@honeypot

								<div class="card-body">
									<div class="form-group">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Email')}} <span class="text-red">*</span></label>
											</div>
											<div class="col-md-10">
												<input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{lang('Email')}}" value="{{ old('email') }}" name="email" id="email">
												<span id="EmailError" class="text-danger alert-message" ></span>
												@error('email')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
									</div>
									@if(setting('cc_email') == 'on')
									<div class="form-group">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('CC')}} </label>
											</div>
											<div class="col-md-10">
												<input type="email" class="form-control @error('ccmail') is-invalid @enderror" placeholder="{{lang('CC Email')}}" value="{{ old('ccmail') }}" name="ccmail" id="ccmail">
												<div><small class="text-muted"> {{lang('You are allowed to send only a single CC.')}}</small></div>
												<span id="ccEmailError" class="text-danger alert-message" ></span>
												@error('ccmail')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror
											</div>
										</div>
									</div>
									@endif

									<div class="form-group">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Subject')}} <span class="text-red">*</span></label>
											</div>
											<div class="col-md-10">
												<input type="text" id="subject" class="form-control @error('subject') is-invalid @enderror" maxlength="{{setting('TICKET_CHARACTER')}}" placeholder="{{lang('Subject')}}" name="subject" value="{{ old('subject') }}">
												<small class="text-muted float-end mt-2 subjectmaxtext" id="subjectmaxtext">{{lang('Maximum')}} <b>{{setting('TICKET_CHARACTER')}}</b> {{lang('Characters')}}</small>
												<div>
													<span id="SubjectError" class="text-danger alert-message"></span>
												</div>
												@error('subject')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Catégorie')}}<span class="text-red">*</span></label>
											</div>
											<div class="col-md-10">
												<select  class="form-control select2-show-search  select2 @error('category') is-invalid @enderror"  data-placeholder="{{lang('Choisir une catégorie')}}" name="category" id="category">
													<option label="{{lang('Choisir une catégorie')}}"></option>
													@foreach ($categories as $category)

														<option value="{{ $category->id }}" @if(old('category') == $category->id) selected @endif>{{ $category->name }}</option>
													@endforeach

												</select>
												<span id="CategoryError" class="text-danger alert-message"></span>
												@error('category')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
									</div>
									<div class="form-group" id="selectssSubCategory" style="display: none;">

										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Sous-catégorie')}}</label>
											</div>
											<div class="col-md-10">
												<select  class="form-control select2-show-search  select2"  data-placeholder="{{lang('Sélectionnez la sous-catégorie')}}" name="subscategory" id="subscategory" >

												</select>
												<span id="subsCategoryError" class="text-danger alert-message"></span>
											</div>
										</div>

									</div>
									<div class="form-group" id="selectSubCategory">
									</div>
									<div class="form-group" id="envatopurchase">
									</div>
									@if($customfields->isNotEmpty())
										@foreach($customfields as $customfield)
										<div class="form-group ">
											<div class="row">
												<div class="col-md-2">
													<label class="form-label mb-0 mt-2">{{$customfield->fieldnames}}
														@if($customfield->fieldrequired == '1')

														<span class="text-red">*</span>
														@endif
													</label>
												</div>
												<div class="col-md-10">

													@if($customfield->fieldtypes == 'text')

														<input type="{{$customfield->fieldtypes}}" maxlength="255" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
													@endif
													@if($customfield->fieldtypes == 'email')

														<input type="{{$customfield->fieldtypes}}" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
													@endif
													@if($customfield->fieldtypes == 'textarea')

														<textarea name="custom_{{$customfield->id}}" maxlength="255" class="form-control" id="" cols="30" rows="4" {{$customfield->fieldrequired == '1' ? 'required' : ''}}></textarea>
													@endif
													@if($customfield->fieldtypes == 'checkbox')

														@php
															$coptions = explode(',', $customfield->fieldoptions)
														@endphp
														@foreach($coptions as $key => $coption)
														<label class="custom-control custom-checkbox d-inline-block me-3">
															<input type="{{$customfield->fieldtypes}}" class="custom-control-input {{$customfield->fieldrequired == '1' ? 'required' : ''}}"  name="custom_{{$customfield->id}}[]" value="{{$coption}}" id="" >

															<span class="custom-control-label">{{$coption}}</span>
														</label>

														@endforeach


													@endif
													@if($customfield->fieldtypes == 'select')
														<select name="custom_{{$customfield->id}}" id="" class="form-control select2-show-search" data-placeholder="{{lang('Select')}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
															@php
																$seoptions = explode(',', $customfield->fieldoptions)
															@endphp
															<option ></option>
															@foreach($seoptions as $seoption)

															<option value="{{$seoption}}">{{$seoption}}</option>
															@endforeach
														</select>
													@endif
													@if($customfield->fieldtypes == 'radio')
													@php
														$roptions = explode(',', $customfield->fieldoptions)
													@endphp
													@foreach($roptions as $roption)
													<label class="custom-control custom-radio d-inline-block me-3">
														<input type="{{$customfield->fieldtypes}}" class="custom-control-input" name="custom_{{$customfield->id}}" value="{{$roption}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
														<span class="custom-control-label">{{$roption}}</span>
													</label>


													@endforeach

													@endif

												</div>
											</div>
										</div>
										@endforeach
									@endif
									<div class="form-group ticket-summernote ">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Description')}}<span class="text-red">*</span></label>
											</div>
											<div class="col-md-10">
												<textarea class="summernote form-control @error('message') is-invalid @enderror" rows="7" name="message">{{old('message')}}</textarea>
												<span id="MessageError" class="text-danger alert-message"></span>
												@error('message')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-md-2">
												<label class="form-label mb-0 mt-2">{{lang('Upload File')}}</label>
											</div>
											<div class="col-md-10">
												<div class="form-group mb-0">
													<div class="needsclick dropzone" id="document-dropzone"></div>
												</div>
												<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer">
									<div class="form-group float-end">
										<button type="submit" id="createticketbtn"  class="btn btn-secondary btn-lg purchasecode">{{lang('Créer une Alerte/Cas')}}</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!--End Create Ticket List-->
					@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{gn_asset('assets/js/support/support-createticket.js')}}"></script>
		<script src="{{gn_asset('assets/js/select2.js')}}"></script>

		<!-- INTERNAL Dropzone js-->
		<script src="{{gn_asset('assets/plugins/dropzone/dropzone.js')}}"></script>

		<!-- INTERNAL Bootstrap-MaxLength js-->
		<script src="{{gn_asset('assets/plugins/bootstrapmaxlength/bootstrap-maxlength.min.js')}}"></script>



		<script type="text/javascript">

			"use strict";

            var licensekey;

			// $('#subject').maxlength({
			// 	alwaysShow: true,
			// });


			// Image Upload
			var uploadedDocumentMap = {}
			Dropzone.options.documentDropzone = {
				url: '{{route('imageuploadadmin')}}',
				maxFilesize: '{{setting('FILE_UPLOAD_MAX')}}', // MB
				addRemoveLinks: true,
				acceptedFiles: '{{setting('FILE_UPLOAD_TYPES')}}',
					headers: {
						'X-CSRF-TOKEN': "{{ csrf_token() }}"
					},
				success: function (file, response) {
					$('form').append('<input type="hidden" name="ticket[]" value="' + response.name + '">')
					uploadedDocumentMap[file.name] = response.name
				},
				removedfile: function (file) {
					file.previewElement.remove()
					var name = ''
					if (typeof file.file_name !== 'undefined') {
					name = file.file_name
					}
					else {
					name = uploadedDocumentMap[file.name]
					}
					$('form').find('input[name="ticket[]"][value="' + name + '"]').remove()
				},
				init: function () {
					@if(isset($project) && $project->document)
					var files =
						{!! json_encode($project->document) !!}
					for (var i in files) {
						var file = files[i]
						this.options.addedfile.call(this, file)
						file.previewElement.classList.add('dz-complete')
						$('form').append('<input type="hidden" name="ticket[]" value="' + file.file_name + '">')
					}
					@endif

					this.on('error', function(file, errorMessage) {
						if (errorMessage.message) {

							var errorDisplay = document.querySelectorAll('[data-dz-errormessage]');
							errorDisplay[errorDisplay.length - 1].innerHTML = errorMessage.message;
						}
					});
				}
			}

			// Csrf field
			$.ajaxSetup({
				headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			// when category change its get the subcat list
			$('#category').on('change',function(e) {
				var cat_id = e.target.value;
				$('#selectssSubCategory').hide();
				$.ajax({
					url:"{{ route('guest.subcategorylist') }}",
					type:"POST",
						data: {
						cat_id: cat_id
						},
						cache : false,
						async: true,
					success:function (data) {
						if(data.subcategoriess != ''){
							$('#subscategory').html(data.subcategoriess)
							$('#selectssSubCategory').show()
						}
						else{
							$('#selectssSubCategory').hide();
							$('#subscategory').html('')
						}
						//projectlist
						if(data.subcategories.length >= 1){

							$('#subcategory')?.empty();
							$('#selectSubCategory .row')?.remove();
							let selectDiv = document.querySelector('#selectSubCategory');
							let Divrow = document.createElement('div');
							Divrow.setAttribute('class','row mt-4');
							let Divcol3 = document.createElement('div');
							Divcol3.setAttribute('class','col-md-2');
							let selectlabel =  document.createElement('label');
							selectlabel.setAttribute('class','form-label mb-0 mt-2')
							selectlabel.innerText = "Projects";
							let divcol9 = document.createElement('div');
							divcol9.setAttribute('class', 'col-md-10');
							let selecthSelectTag =  document.createElement('select');
							selecthSelectTag.setAttribute('class','form-control select2-show-search');
							selecthSelectTag.setAttribute('id', 'subcategory');
							selecthSelectTag.setAttribute('name', 'project');
							selecthSelectTag.setAttribute('data-placeholder','Select Projects');
							let selectoption = document.createElement('option');
							selectoption.setAttribute('label','Select Projects')
							selectDiv.append(Divrow);
							Divrow.append(Divcol3);
							Divcol3.append(selectlabel);
							divcol9.append(selecthSelectTag);
							selecthSelectTag.append(selectoption);
							Divrow.append(divcol9);
							$('.select2-show-search').select2();
							$.each(data.subcategories,function(index,subcategory){
							$('#subcategory').append('<option value="'+subcategory.name+'">'+subcategory.name+'</option>');
							})
						}
						else{
							$('#subcategory')?.empty();
							$('#selectSubCategory .row')?.remove();
						}
						@if(setting('ENVATO_ON') == 'on')
						//Envato Access
						if(data.envatosuccess.length >= 1){
							$('#envato_id')?.empty();
							$('#envatopurchase .row')?.remove();
							let selectDiv = document.querySelector('#envatopurchase');
							let Divrow = document.createElement('div');
							Divrow.setAttribute('class','row mt-4');
							let Divcol3 = document.createElement('div');
							Divcol3.setAttribute('class','col-md-2');
							let selectlabel =  document.createElement('label');
							selectlabel.setAttribute('class','form-label mb-0 mt-2')
							selectlabel.innerHTML = "Envato Purchase Code <span class='text-red'>*</span>";
							let divcol9 = document.createElement('div');
							divcol9.setAttribute('class', 'col-md-10');
							let selecthSelectTag =  document.createElement('input');
							selecthSelectTag.setAttribute('class','form-control');
							selecthSelectTag.setAttribute('type','search');
							selecthSelectTag.setAttribute('id', 'envato_id');
							selecthSelectTag.setAttribute('name', 'envato_id');
							selecthSelectTag.setAttribute('placeholder', 'Enter Your Purchase Code');
							let selecthSelectInput =  document.createElement('input');
							selecthSelectInput.setAttribute('type','hidden');
							selecthSelectInput.setAttribute('id', 'envato_support');
							selecthSelectInput.setAttribute('name', 'envato_support');
							selectDiv.append(Divrow);
							Divrow.append(Divcol3);
							Divcol3.append(selectlabel);
							divcol9.append(selecthSelectTag);
							divcol9.append(selecthSelectInput);
							Divrow.append(divcol9);
							$('.purchasecode').attr('disabled', true);

						}else{
							$('#envato_id')?.empty();
							$('#envatopurchase .row')?.remove();
							$('.purchasecode').removeAttr('disabled');
						}
						@endif
					},
					error:(data)=>{

					}
				});
			});


			@php $module = Module::all(); @endphp

			@if(in_array('Uhelpupdate', $module))



			// Purchase Code Validation
			$("body").on('keyup', '#envato_id', function() {
				let value = $(this).val();
				if (value != '') {
					if(value.length == '36'){
						var _token = $('input[name="_token"]').val();
					$.ajax({
						url: "{{ route('guest.envatoverify') }}",
						method: "POST",
						data: {data: value, _token: _token},

						dataType:"json",

						success: function (data) {
							if(data.valid == 'true'){
								$('.sprukoapiblock').removeAttr('disabled');
								$('#envato_id').addClass('is-valid');
								$('#envato_id').attr('readonly', true);
								$('.purchasecode').removeAttr('disabled');
								$('#envato_id').css('border', '1px solid #02f577');
								$('#envato_support').val('Supported');
                                licensekey = data.key
								toastr.success(data.message);
							}
							if(data.valid == 'expried'){
								@if(setting('ENVATO_EXPIRED_BLOCK') == 'on')

								$('.purchasecode').attr('disabled', true);
								$('#envato_id').css('border', '1px solid #e13a3a');
								$('#envato_support').val('Expired');
								toastr.error(data.message);
								@endif
								@if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
								$('.sprukoapiblock').removeAttr('disabled');
								$('#envato_id').addClass('is-valid');
								$('#envato_id').attr('readonly', true);
								$('.purchasecode').removeAttr('disabled');
								$('#envato_id').css('border', '1px solid #02f577');
								$('#envato_support').val('Expired');
                                licensekey = data.key
								toastr.warning(data.message);
								@endif

							}
							if(data.valid == 'false'){
								$('.purchasecode').attr('disabled', true);
								$('#envato_id').css('border', '1px solid #e13a3a');
								toastr.error(data.message);
							}


						},
						error: function (data) {

						}
					});
					}
				}else{
					toastr.error('{{lang('Purchase Code field is Required', 'alerts')}}');
					$('.purchasecode').attr('disabled', true);
					$('#envato_id').css('border', '1px solid #e13a3a');
				}
			});

			@endif

			// $('#subject').maxlength({
			// 	alwaysShow: true,
			// 	warningClass: "badge badge-xs bg-warning",
			// 	limitReachedClass: "badge badge-xs bg-danger"

			// });

			// summernote
			$('.note-editable').on('keyup', function(e){
				localStorage.setItem('adminmessage', e.target.innerHTML)
			})

			$('#subject').on('keyup', function(e){
				if(e.target.value.length == {{setting('TICKET_CHARACTER')}}){
					$('#subjectmaxtext').removeClass('text-muted')
					$('#subjectmaxtext').addClass('text-red');
				}else{
					$('#subjectmaxtext').removeClass('text-red')
					$('#subjectmaxtext').addClass('text-muted');
				}
				localStorage.setItem('adminsubject', e.target.value)
			})
			$('#email').on('keyup', function(e){
				localStorage.setItem('adminemail', e.target.value)
			})

			$(window).on('load', function(){
				if(localStorage.getItem('adminsubject') || localStorage.getItem('adminmessage') || localStorage.getItem('adminemail')){


                    if(localStorage.getItem('adminsubject')){
                        document.querySelector('#subject').value = localStorage.getItem('adminsubject').slice(0,{{setting('TICKET_CHARACTER')}});
                    }
					document.querySelector('#email').value = localStorage.getItem('adminemail');
					document.querySelector('.summernote').innerHTML = localStorage.getItem('adminmessage');
					document.querySelector('.note-editable').innerHTML = localStorage.getItem('adminmessage');
				}
			})



			$('body').on('submit', '#admin_form', function (e) {
				e.preventDefault();
				$('#SubjectError').html('');
				$('#MessageError').html('');
				$('#EmailError').html('');
				$('#CategoryError').html('');
				$('#verifyotpError').html('');
				var actionType = $('#createticketbtn').val();
				var fewSeconds = 2;
				$('#createticketbtn').html(`Loading.. <i class="fa fa-spinner fa-spin"></i>`);
				$('#createticketbtn').prop('disabled', true);
				var formData = new FormData(this);
                formData.set('envato_id', licensekey);

				let checked  = document.querySelectorAll('.required:checked').length;
				var isValid = checked > 0;
				if(document.querySelectorAll('.required').length == '0'){
					ajax(formData);
				}else{

					if(isValid){
						ajax(formData);
					}else{
						toastr.error('{{lang('Check the all field(*) required', 'alerts')}}')
					}
				}


			});

			function ajax(formData){
				$.ajax({
					type:'post',
					url: '{{url('/admin/createticket')}}',
					data: formData,
					cache:false,
					contentType: false,
					processData: false,

					success: (data) => {

						if(data.message == 'envatoerror')
						{
							toastr.error(data.error);
                            window.location.reload();
						}
						if(data.message == 'domainblock')
						{
							$('#EmailError').html(data.error);
						}
						if(data.message == 'createticket')
						{
							$('#SubjectError').html('');
							$('#MessageError').html('');
							$('#EmailError').html('');
							$('#CategoryError').html('');
							$('#verifyotpError').html('');
							toastr.success(data.success);
							if(localStorage.getItem('adminsubject') || localStorage.getItem('adminmessage') || localStorage.getItem('adminemail')){
								localStorage.removeItem("adminsubject");
								localStorage.removeItem("adminmessage");
								localStorage.removeItem("adminemail");
							}
							window.location.replace('{{url('admin/')}}');
						}

					},
					error: function(data){

						$('#SubjectError').html(data.responseJSON.errors.subject);
						$('#MessageError').html(data.responseJSON.errors.message);
						$('#EmailError').html(data.responseJSON.errors.email);
						$('#CategoryError').html(data.responseJSON.errors.category);
						$('#verifyotpError').html(data.responseJSON.errors.verifyotp);

					}
				});
			}

		</script>

		@endsection
