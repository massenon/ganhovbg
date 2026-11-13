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
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Edit Article')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!--Article Edit-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header d-flex border-0">
										<h4 class="card-title">{{lang('Edit Article')}}</h4>
                                        @if(setting('enable_gpt') == 'on')
                                            <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
                                        @endif
									</div>
									<form method="POST" action="{{url('/admin/article/'.$article->id.'/edit')}}" enctype="multipart/form-data">
										@csrf

										@honeypot
										<div class="card-body">
											<div class="form-group">
												<label class="form-label">{{lang('Title')}}</label>
												<input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{$article->title}}">
												@error('title')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Catégorie')}}</label>
												<select class="form-control select2-show-search  select2 @error('category') is-invalid @enderror" data-placeholder="{{lang('Choisir une catégorie')}}" name="category" id="category">
													<option label="{{lang('Choisir une catégorie')}}"></option>
													@foreach ($category as $category)

														<option value="{{ $category->id }}" @if ($category->id === 	$article->category_id) selected @endif >{{ $category->name }}</option>
													@endforeach

												</select>
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
												<label class="form-label">{{lang('Description')}}:</label>
												<textarea class="summernote form-control  @error('message') is-invalid @enderror" rows="6" name="message">{{$article->message}}</textarea>
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
													{{-- <input class="form-control @error('featureimage') is-invalid @enderror" name="featureimage" type="file" accept="image/png, image/jpeg,image/jpg" > --}}
													@error('featureimage')

														<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
														</span>
													@enderror

												</div>
												<small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
											</div>
											@if ($article->featureimage != null)

											<div class="file-image-1 removesprukoi{{$article->id}}">
												<div class="product-image custom-ul">
													<a href="#">
														<img src="{{asset('uploads/featureimage/' .$article->featureimage)}}" class="br-5" alt="{{$article->featureimage}}">
													</a>
													<ul class="icons">
														<li><a href="javascript:(0);" class="bg-danger imagefdel" data-id="{{$article->id}}"><i class="fe fe-trash"></i></a></li>
													</ul>
												</div>
											</div>
											@endif

											<div class="form-group">
												<label class="form-label">{{lang('Tags')}}</label>
												<input type="text" id="tags" class="form-control" name="tags" value="{{$article->tags}}" data-role="tagsinput" />
											</div>

											<div class="form-group">
												<div class="custom-controls-stacked d-md-flex">
													<label class="form-label mt-1 me-5">{{lang('Status')}} :</label>
													<label class="custom-control form-radio success me-4">
														<input type="radio" class="custom-control-input" name="status" value="Published" {{ $article->status == 'Published' ? 'checked' : '' }}>
														<span class="custom-control-label">{{lang('Publish')}}</span>
													</label>
													<label class="custom-control form-radio success me-4">
														<input type="radio" class="custom-control-input" name="status" value="UnPublished" {{ $article->status == 'UnPublished' ? 'checked' : '' }}>
														<span class="custom-control-label">{{lang('UnPublish')}}</span>
													</label>
												</div>
											</div>

											<div class="form-group">
												<label class="custom-control form-checkbox">
													<input type="checkbox" class="custom-control-input" name="privatemode" id="privatemode" {{$article->privatemode == 1 ? 'checked' : ''}}>
													<span class="custom-control-label">{{lang('Privacy Mode')}}</span>
												</label>
											</div>
											<div class="form-group">
												<label class="form-label">{{lang('Upload File')}}:</label>
												<div class="needsclick dropzone" id="document-dropzone"></div>
												<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
											</div>


											<div class="d-flex align-items-center">
												@foreach ($article->getMedia('article') as $articles)
												<div class="file-image-1 m-1 removespruko{{$articles->id}}">
													<div class="product-image">
														<a href="javascript:void(0);">
															<img src="{{$articles->getFullUrl()}}" class="br-5" alt="{{$articles->file_name}}">
														</a>
														<ul class="icons">
															<li><a href="javascript:(0);" class="bg-danger imagedel" data-id="{{$articles->id}}"><i class="fe fe-trash"></i></a></li>
														</ul>
													</div>
												</div>
												@endforeach
											</div>

										</div>
										<div class="card-footer clearfix">
											<div class="form-group mb-0 float-end">
												<a href="{{ url('/admin/article') }}" class="btn btn-outline-danger mx-2" >{{lang('Fermer')}}</a>
												<input type="submit" class="btn btn-secondary"  value="{{lang('Update')}}" onclick="this.disabled=true;this.form.submit();">
											</div>
										</div>
									</form>
								</div>
							</div>
							<!--End Article Edit-->

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

			// Variables
			var SITEURL = '{{url('')}}';

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
				}
				else {
				  name = uploadedDocumentMap[file.name]
				}
				$('form').find('input[name="article[]"][value="' + name + '"]').remove()
			  },
			  init: function () {
				@if(isset($article) && $article->article)
				  var files =
					{!! json_encode($article->article) !!}
				  for (var i in files) {
					var file = files[i]
					this.options.addedfile.call(this, file)
					file.previewElement.classList.add('dz-complete')
					$('form').append('<input type="hidden" name="article[]" value="' + file.file_name + '">')
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

			// bootstrap tag js
			$('#tags').tagsinput({
				maxTags: 10
			});

			// Summernote js
			$('.summernote').summernote({
				placeholder: '',
				tabsize: 1,
				height: 120,
			});

			// Attachment delete
			$('.imagedel').on('click', function () {
				let id = $(this).data("id");
				let _url = `{{url('/admin/image/delete/${id}')}}`;
				let _token   = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					type: "DELETE",
					url: _url,
					data: {_token: _token},
					success: function (data) {
					$(".removespruko"+id).remove();
					toastr.success(data.success);
					},
					error: function (data) {
					console.log('Error:', data);
					}
				});
			});

			// Feature Image delete
			$('.imagefdel').on('click', function () {
				let id = $(this).data("id");
				let _url = `{{url('/admin/article/featureimage/${id}')}}`;
				let _token   = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					type: "post",
					url: _url,
					data: {_token: _token},
					success: function (data) {
					$(".removesprukoi"+id).remove();
					toastr.success(data.success);
					},
					error: function (data) {
					console.log('Error:', data);
					}
				});
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

			$(window).on("load", function(e) {
				$.ajax({
					url:"{{ route('admin.article', $article->id) }}",
					type:"get",
					success:function (data) {

						@if($article->subcategory != null)
							$('#selectssSubCategory').show()
							$('#subscategory').html(data);
						@endif

					}
				})
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
