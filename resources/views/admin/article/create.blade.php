@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

		<!-- INTERNAl dropzone css -->
		<link href="{{gn_asset('assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />

		<!-- INTERNAl bootstraptag css -->
		<link href="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.css')}}" rel="stylesheet" />

        <!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('New Article')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!--Article Create-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-flex">
										<h4 class="card-title">{{lang('Add New Article')}}</h4>
                                        @if(setting('enable_gpt') == 'on')
                                                <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
                                            @endif
									</div>
									<form method="post"  enctype="multipart/form-data" id="adminarticle_forms">

										@honeypot
										<div class="card-body">
											<div class="form-group">
												<label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
												<input type="text" class="form-control @error('title') is-invalid @enderror" placeholder="{{lang('Subject')}}" name="title" value="{{old('title')}}" id="subject">
												<span id="TitleError" class="text-danger alert-message"></span>
												@error('title')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Catégorie')}} <span class="text-red">*</span></label>
												<select class="form-control select2-show-search  select2 @error('category') is-invalid @enderror" data-placeholder="{{lang('Choisir une catégorie')}}" name="category" id="category">
													<option label="{{lang('Choisir une catégorie')}}"></option>
													@foreach ($category as $category)

														<option value="{{ $category->id }}" @if(old('category') == $category->id ) selected @endif>{{ $category->name }}</option>
													@endforeach

												</select>
												<span id="CategoryError" class="text-danger alert-message"></span>
												@error('category')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
											<div class="form-group" id="selectssSubCategory" style="display: none;">
													<label class="form-label mb-0 mt-2">{{lang('Sous-catégorie')}}</label>
													<select  class="form-control select2-show-search  select2"  data-placeholder="{{lang('Sélectionnez la sous-catégorie')}}" name="subscategory" id="subscategory">
													</select>
													<span id="subsCategoryError" class="text-danger alert-message"></span>
											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Description')}}: <span class="text-red">*</span></label>
												<textarea class="summernote d-none @error('message') is-invalid @enderror" name="message" id="summernote">{{old('message')}}</textarea>
												<span id="MessageError" class="text-danger alert-message"></span>
												@error('message')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>

												@enderror
											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Feature Image')}}</label>
												<div class="input-group file-browser">
													<div class="needsclick dropzone" id="feature-image"></div>
													@error('featureimage')

														<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
														</span>
													@enderror
													<span id="FeatureimageError" class="text-danger alert-message"></span>

												</div>
												<small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Tags')}} <span class="text-red">*</span></label>
												<input type="text" id = "tags" class="form-control @error('tags') is-invalid @enderror" name="tags" value="{{old('tags')}}" data-role="tagsinput" />
												@error('tags')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror
												<span id="TagsError" class="text-danger alert-message"></span>

											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Upload File')}}:</label>
												<div class="needsclick dropzone" id="document-dropzone"></div>
												<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
											</div>
											<div class="form-group">
												<div class="custom-controls-stacked d-md-flex @error('status') is-invalid @enderror">
													<label class="form-label mt-1 me-5">{{lang('Status')}} : <span class="text-red">*</span></label>
													<label class="custom-control form-radio success me-4">
														<input type="radio" class="custom-control-input " name="status" value="Published">
														<span class="custom-control-label">{{lang('Publish')}}</span>
													</label>
													<label class="custom-control form-radio success me-4">
														<input type="radio" class="custom-control-input" name="status" value="UnPublished">
														<span class="custom-control-label">{{lang('UnPublish')}}</span>
													</label>
												</div>
												@error('status')

													<div class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</div>
												@enderror
												<span id="StatusError" class="text-danger alert-message"></span>
											</div>

											<div class="form-group">
												<label class="custom-control form-checkbox">
													<input type="checkbox" class="custom-control-input" name="privatemode" id="privatemode" >
													<span class="custom-control-label">{{lang('Privacy Mode')}}</span>
												</label>
											</div>

										</div>
										<div class="card-footer clearfix">
											<div class="form-group float-end mb-0 btn-list">
												<a href="{{ url('/admin/article') }}" class="btn btn-outline-danger" >{{lang('Fermer')}}</a>
												<button type="submit" class="btn btn-secondary" >{{lang('Sauvegarder')}}</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<!--End Article Create-->

                            @endsection

                            @section('modal')

                                @if(setting('enable_gpt') == 'on')
                                    <!-- GPT Model-->
                                    <div class="modal fade"  id="addgptmodal" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ lang('Ask Chat GPT') }}</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                    @csrf
                                                    @honeypot
                                                    <div class="modal-body pb-0 position-relative">
                                                        <div class="form-group">
                                                            <label class="form-label">{{lang('Type your query here')}}</label>
                                                            <div class="d-flex gap-2">
                                                                <input type="text" class="form-control" placeholder="Saisir ici" name="" id="spk_gpt">
                                                                <input type="button" class="btn btn-secondary" id="priority_form1"  value="{{lang('Generate Text')}}">
                                                            </div>
                                                            <span class="invalid-feedback d-block mt-4" role="alert">
                                                                <strong id="error-message-gpt"></strong>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="gap-2 mt-sm-0 mt-3 me-2 position-absolute open-pen d-none" id="loader-gpt">
                                                        <div class="px-1 py-1 d-flex align-items-center">
                                                            <span class="avatar text-muted me-0 bg-transparent" style="background-image: url(&quot;{{asset('assets/images/typing.gif')}}&quot;);">
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body pt-0">
                                                        <div class="form-group" id="main-gpt">
                                                            <div id="textares-gpt">
                                                                <label class="form-label">{{lang('Generated text')}}</label>
                                                                <div class="" >
                                                                    <div class="form-control openanswer" name="" id="answershow" ></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="button" class="ms-0 btn btn-primary d-none" name="" id="copytoresponse-gpt" data-bs-dismiss="modal" value="{{ lang('Copy Response') }}">
                                                        <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GPT Model end -->
                                @endif

                            @endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{gn_asset('assets/js/select2.js')}}"></script>

		<!-- INTERNAL dropzone js-->
		<script src="{{gn_asset('assets/plugins/dropzone/dropzone.js')}}"></script>

		<!-- INTERNAL bootstraptag js-->
		<script src="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.js')}}"></script>

        <!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<script type="text/javascript">

			"use strict";

			// Csrf field
			$.ajaxSetup({
				headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			// Attachment Image Upload
			var uploadedDocumentMap = {}
			Dropzone.options.documentDropzone = {
			  	url: '{{route('admin.imageupload')}}',
			  	maxFilesize: '{{setting('FILE_UPLOAD_MAX')}}', // MB
			  	addRemoveLinks: true,
			  	acceptedFiles: '{{setting('FILE_UPLOAD_TYPES')}}',
				headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
			  	success: function (file, response) {
					$('form').append('<input type="hidden" name="article[]" value="' + response.name + '">')
					uploadedDocumentMap[file.name] = response.name
			  	},
			 	removedfile: function (file) {
					file.previewElement.remove()
					var name = ''
					if (typeof file.file_name !== 'undefined') {
				  		name = file.file_name
					} else {
				 		 name = uploadedDocumentMap[file.name]
					}
					$('form').find('input[name="article[]"][value="' + name + '"]').remove()
			  	},
			  	init: function () {
					@if(isset($project) && $project->document)
				  		var files =
							{!! json_encode($project->document) !!}
				  		for (var i in files) {
							var file = files[i]
							this.options.addedfile.call(this, file)
							file.previewElement.classList.add('dz-complete')
							$('form').append('<input type="hidden" name="article[]" value="' + file.file_name + '">')
				  		}
					@endif

					this.on('error', function(file, errorMessage) {
						if (errorMessage.message) {
							console.log(errorMessage);
							var errorDisplay = document.querySelectorAll('[data-dz-errormessage]');
							errorDisplay[errorDisplay.length - 1].innerHTML = errorMessage.message;
						}
					});
			  	}
			}

			// Feature Image
			Dropzone.options.featureImage = {
			  	url: '{{route('admin.featureimageupload')}}',
			  	maxFilesize: '5', // MB
			  	addRemoveLinks: true,
			  	acceptedFiles: '.jpeg,.jpg,.png',
				  maxFiles: 1,
				headers: {
					'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
			  	success: function (file, response) {
					$('form').append('<input type="hidden" name="featureimage" value="' + response.name + '">')
					uploadedDocumentMap[file.name] = response.name
			  	},
			 	removedfile: function (file) {
					file.previewElement.remove()
					var name = ''
					if (typeof file.file_name !== 'undefined') {
				  		name = file.file_name
					} else {
				 		 name = uploadedDocumentMap[file.name]
					}
					$('form').find('input[name="featureimage"][value="' + name + '"]').remove()
			  	},
			  	init: function () {
					@if(isset($project) && $project->document)
				  		var files =
							{!! json_encode($project->document) !!}
				  		for (var i in files) {
							var file = files[i]
							this.options.addedfile.call(this, file)
							file.previewElement.classList.add('dz-complete')
							$('form').append('<input type="hidden" name="featureimage" value="' + file.file_name + '">')
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

			// Bootstrap tag js
			$('#tags').tagsinput({
				maxTags: 10
			});

			// Summernote js
			$('.summernote').summernote({
				placeholder: '',
				tabsize: 1,
				height: 120,
			});

			// when category change its get the subcat list
			$('#category').on('change',function(e) {
				e.preventDefault();
				var cat_id = e.target.value;
				$('#selectssSubCategory').hide();
				$.ajax({
					url:"{{ route('guest.subcategorylist') }}",
					type:"POST",
					dataType: "json",
						data: {
						cat_id: cat_id
						},
					success:function (data) {
						if(data.subcategoriess){

							$('#selectssSubCategory').hide()
							$('#subscategory').html(data.subcategoriess)
							$('.subcategoryselect').select2();
							$('#selectssSubCategory').show()
						}
						else{
							$('#selectssSubCategory').hide();
							$('#subscategory').html('')
						}
					}
				})
			});

			// store subject to local
			$('#subject').on('keyup', function(e){
				localStorage.setItem('articlesubject', e.target.value)
			})

			// summernote
			$('.note-editable').on('keyup', function(e){
				localStorage.setItem('articlemessage', e.target.innerHTML)
			})


			// onload get the data from local
			$(window).on('load', function(){
				if(localStorage.getItem('articlesubject') || localStorage.getItem('articlemessage')){

					document.querySelector('#subject').value = localStorage.getItem('articlesubject');
					document.querySelector('.summernote').innerHTML = localStorage.getItem('articlemessage');
					document.querySelector('.note-editable').innerHTML = localStorage.getItem('articlemessage');
				}
			})

			// Create Ticket
			$('body').on('submit', '#adminarticle_forms', function (e) {
				e.preventDefault();
				$('#TitleError').html('');
				$('#CategoryError').html('');
				$('#MessageError').html('');
				$('#TagsError').html('');
				$('#StatusError').html('');
				var actionType = $('#btnsave').val();
				var fewSeconds = 2;
				$('#btnsave').html('Sending..');
				$('#btnsave').prop('disabled', true);
					setTimeout(function(){
						$('#btnsave').prop('disabled', false);
					}, fewSeconds*1000);
				var formData = new FormData(this);

				$.ajax({
					type:'post',
					url: '{{url('/admin/article/create')}}',
					data: formData,
					cache:false,
					contentType: false,
					processData: false,

					success: (data) => {


						$('#TitleError').html('');
						$('#CategoryError').html('');
						$('#MessageError').html('');
						$('#TagsError').html('');
						$('#StatusError').html('');
						toastr.success(data.success);
						if(localStorage.getItem('articlesubject') || localStorage.getItem('articlemessage')){
							localStorage.removeItem("articlesubject");
							localStorage.removeItem("articlemessage");
						}
						window.location.replace('{{url('admin/article')}}');




					},
					error: function(data){

						$('#TitleError').html(data.responseJSON.errors.title);
						$('#CategoryError').html(data.responseJSON.errors.category);
						$('#MessageError').html(data.responseJSON.errors.message);
						$('#TagsError').html(data.responseJSON.errors.tags);
						$('#StatusError').html(data.responseJSON.errors.status);

					}
				});

			});

            $('body').on('click', '#gptmodal', function(){
                $('#spk_gpt').val('');
                $('#answershow').html('');
                $('#addgptmodal').modal('show');
                document.getElementById("copytoresponse-gpt").classList.add("d-none")
            });

		</script>

        <script type="module">
            import openai from "{{asset('assets/js/openapi/openapi.min.js')}}"

            if(document.getElementById("priority_form1")){
                //Chat GPT
                document.getElementById("priority_form1").disabled = true

                var i

                if(document.getElementById("spk_gpt")){
                    ['click','keyup'].forEach( evt =>
                        document.getElementById("spk_gpt").addEventListener(evt,(ele)=>{
                            i = ele.target.value
                            if(i.length){
                                document.getElementById("priority_form1").disabled = false
                            }
                            else{
                                document.getElementById("priority_form1").disabled = true
                            }
                        })
                    );

                }



                document.getElementById("priority_form1").onclick = ()=>{
                    if(i){
                        // copytoresponse button
                        document.getElementById("copytoresponse-gpt").classList.add("d-none")
                        // End copytoresponse button

                        // Loader
                        document.getElementById("loader-gpt").classList.remove("d-none")
                        //End Loader


                        // text area

                        //Adding the text area
                        document.getElementById("answershow").innerText  = ""
                        //End Adding the text area

                        var aaaa;
                        aaaa = {!! json_encode(setting('openai_api')) !!}

                        const configuration = new openai.Configuration({
                            apiKey: aaaa,
                        });
                        const Openai = new openai.OpenAIApi(configuration);
                        const main = async ()=>{
                            const question = i
                            const completion = await Openai.createCompletion({
                                model: "text-davinci-003",
                                prompt: question,
                                max_tokens:2048,
                                temperature:1
                            })
                            return completion.data.choices[0].text
                        }
                        let responce = main()

                        responce.then((data)=>{

                            // Loader
                            document.getElementById("loader-gpt").classList.add("d-none")
                            //End Loader

                            //Adding the text area

                            document.getElementById("answershow").innerText  = data

                            // Adding the Copy Response
                            document.getElementById("copytoresponse-gpt").classList.remove("d-none")

                            //copytoresponse Button event
                            document.getElementById("copytoresponse-gpt").addEventListener("click",()=>{
                                document.querySelector(".note-editable").innerText = `${data}`
                                document.querySelector(".summernote").innerHTML = `${data}`
                            })
                        })

                        responce.catch((error)=>{
                            //To add The Error Message
                            document.getElementById("error-message-gpt").innerText = error?.response.data.error.message
                            //End To add The Error Message


                            // Loader
                            document.getElementById("loader-gpt").classList.add("d-none")
                            //End Loader

                            // copytoresponse button
                            document.getElementById("copytoresponse-gpt").classList.add("d-none")
                            // End copytoresponse button

                        })
                    }
                    else{
                        toastr.error('Please enter somthing!');
                        document.getElementById("priority_form1").disabled = true
                    }
                }
            }

        </script>

		@endsection
