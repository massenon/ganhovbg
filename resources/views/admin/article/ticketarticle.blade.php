@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<link href="{{gn_asset('assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />

<link href="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.css')}}" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Ticket To Article')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Ticket To Article')}}</h4>
		</div>
		<form method="post"  enctype="multipart/form-data" id="adminarticle_forms">

			@honeypot
			<div class="card-body">
				<div class="form-group">
					<label class="form-label">{{lang('Title')}}</label>
					<input type="text" class="form-control @error('title') is-invalid @enderror"  name="title" value="{{$articleticket->subject}}">
					<span id="TitleError" class="text-danger alert-message"></span>
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

							<option value="{{ $category->id }}" @if ($category->id === 	$articleticket->category_id) selected @endif >{{ $category->name }}</option>
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
					<label class="form-label">{{lang('Description')}}:</label>
					<textarea class="summernote @error('message') is-invalid @enderror" name="message">
						@foreach($finalcomment as $finalcomments)
							<p>{{$finalcomments}}</p>
						@endforeach
					</textarea>
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
						<span id="FeatureimageError" class="text-danger alert-message"></span>

					</div>
					<small class="text-muted"><i>{{lang('The file size should not be more than 5MB')}}</i></small>
				</div>
				@if ($articleticket->featureimage != null)
				@if($articleticket->featureimage == 'frontend.jpg')

				<div class="file-image-1 removesprukoi{{$articleticket->id}}">
					<div class="product-image custom-ul">
						<img src="{{asset('uploads/featureimage/demo/frontend.jpg')}}" class="br-5" alt="{{$articleticket->featureimage}}">

					</div>
				</div>
				@else

				<div class="file-image-1 removesprukoi{{$articleticket->id}}">
					<div class="product-image custom-ul">
						<a href="#">
							<img src="{{asset('uploads/featureimage/' .$articleticket->featureimage)}}" class="br-5" alt="{{$articleticket->featureimage}}">
						</a>
						<ul class="icons">
							<li><a href="javascript:(0);" class="bg-danger imagefdel" data-id="{{$articleticket->id}}"><i class="fe fe-trash"></i></a></li>
						</ul>
					</div>
				</div>
				@endif

				@endif

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
					<div class="custom-controls-stacked d-md-flex">
						<label class="form-label mt-1 me-5">{{lang('Status')}} : <span class="text-red">*</span></label>
						<label class="custom-control form-radio success me-4">
							<input type="radio" class="custom-control-input" name="status" value="Published" {{ $articleticket->status == 'Published' ? 'checked' : '' }}>
							<span class="custom-control-label">{{lang('Publish')}}</span>
						</label>
						<label class="custom-control form-radio success me-4">
							<input type="radio" class="custom-control-input" name="status" value="UnPublished" {{ $articleticket->status == 'UnPublished' ? 'checked' : '' }}>
							<span class="custom-control-label">{{lang('UnPublish')}}</span>
						</label>
					</div>
					<span id="StatusError" class="text-danger alert-message"></span>
				</div>

				<div class="form-group">
					<label class="custom-control form-checkbox">
						<input type="checkbox" class="custom-control-input" name="privatemode" id="privatemode" {{$articleticket->privatemode == 1 ? 'checked' : ''}}>
						<span class="custom-control-label">{{lang('Privacy Mode')}}</span>
					</label>
				</div>
				<div class="form-group">
					<label class="form-label">{{lang('Upload File')}}</label>
					<div class="needsclick dropzone" id="document-dropzone"></div>
					<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
				</div>

				<div class="d-flex flex-wrap align-items-center">
					@foreach ($articleticket->getMedia('article') as $articles)

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
				<div class="form-group float-end mb-0">
					<a href="{{ url('/admin/article') }}" class="btn btn-outline-danger mx-2" >{{lang('Fermer')}}</a>
					<button type="submit" class="btn btn-secondary" >{{lang('Sauvegarder')}}</button>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection


@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

<!-- INTERNAL Summernote js  -->
<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
<script src="{{gn_asset('assets/js/select2.js')}}"></script>

<script src="{{gn_asset('assets/plugins/dropzone/dropzone.js')}}"></script>

<script src="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.js')}}"></script>


<script type="text/javascript">

	"use strict";

	// Variables
	var SITEURL = '{{url('')}}';

	// Csrf Field
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

	// Bootstrap tag js
	$('#tags').tagsinput({
		maxTags: 10
	});

	// Summernote js
	$('.summernote').summernote({
		placeholder: '',
		tabsize: 1,
		height: 120,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			// ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			// ['height', ['height']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen']],
			['help', ['help']]
		],
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

	// onload get the data from local
	$(window).on('load', function(){
		if(localStorage.getItem('articlesubject') || localStorage.getItem('articlemessage')){

			document.querySelector('#subject').value = localStorage.getItem('articlesubject');
			document.querySelector('.summernote').innerHTML = localStorage.getItem('articlemessage');
			document.querySelector('.note-editable').innerHTML = localStorage.getItem('articlemessage');
		}
	})

	// store subject to local
	$('#subject').on('keyup', function(e){
		localStorage.setItem('articlesubject', e.target.value)
	})

	// summernote
	$('.note-editable').on('keyup', function(e){
		localStorage.setItem('articlemessage', e.target.innerHTML)
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

</script>

@endsection
