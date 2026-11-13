@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<!-- INTERNAL Data table css -->
<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

<!-- INTERNAL Datepicker css-->
<link href="{{gn_asset('assets/plugins/modal-datepicker/datepicker.css')}}" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

<!-- INTERNAl color css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/colorpickr/themes/nano.min.css')}}">

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Announcements', 'menu')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<!-- Add Announcement -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Create Announcement')}}</h4>
            @if(setting('enable_gpt') == 'on')
                <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
            @endif
		</div>
        <form method="POST" enctype="multipart/form-data" id="testimonial_form" name="testimonial_form">
            <input type="hidden" name="testimonial_id" value="{{$announcementData->id}}" id="$announcementData->id">
            @csrf
            @honeypot
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="title" id="name" value="{{$announcementData->title}}">
                    <span id="nameError" class="text-danger alert-message"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Notice Text')}}</label>
                    <textarea class="form-control summernote"  name = "notice" id="description" >{{$announcementData->notice}}</textarea>
                    <span id="descriptionError" class="text-danger alert-message"></span>
                </div>
                <div class="row">
                    <h4>{{lang('Announcement Days')}} : </h4>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Start Date')}}: <span class="text-red">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="feather feather-calendar"></i>
                                </div>
                                <input class="form-control fc-datepicker" placeholder="AAAA-MM-JJ" type="text" value="{{$announcementData->startdate?->format('Y-m-d')}}" name="startdate" id="startdate" autocomplete="off" readonly>
                            </div>
                            <span id="startdateError" class="text-danger alert-message"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('End Date')}}: <span class="text-red">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="feather feather-calendar"></i>
                                </div>
                                <input class="form-control fc-datepicker" placeholder="AAAA-MM-JJ" type="text" value="{{$announcementData->enddate?->format('Y-m-d')}}" name="enddate" id="enddate"  autocomplete="off" readonly>
                            </div>
                            <span id="enddateError" class="text-danger alert-message"></span>
                        </div>
                    </div>
                    <span class="text-center">Or</span>
                    <div class="form-group">
                        <label class="form-label">{{lang('Repeated Announcement Day')}} : <span class="text-red">*</span></label>
                        <select class="form-control custom-select form-select @error('announcementday') is-invalid @enderror" data-placeholder="{{lang('Select Day')}}" multiple name="announcementday[]" id="announcementday">
                            <option value=""></option>
                            @foreach($normalDay as $normalDays)
                                @if(in_array($normalDays , $announceDay))
                                    <option value="{{$normalDays}}" selected>{{$normalDays}}</option>
                                @else
                                    <option value="{{$normalDays}}">{{$normalDays}}</option>
                                @endif
                            @endforeach

                        </select>
                        @error('announcementday')

                            <span class="invalid-feedback" role="alert">
                                <strong>{{ lang($message) }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Primary Background Color', 'general')}} <span class="text-red">*</span></label>
                            <input class="form-control" name="primary_color" type="text" value="{{ $announcementData->primary_color != null ? $announcementData->primary_color : '' }}" id="primary_color-input">

                            @if ($errors->has('primary_color'))

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('primary_color') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Secondary Background Color', 'general')}} <span class="text-red">*</span></label>
                            <input class="form-control" name="secondary_color" type="text" value="{{ $announcementData->secondary_color != null ? $announcementData->secondary_color : '' }}" id="secondary_color-input">

                            @if ($errors->has('secondary_color'))

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('secondary_color') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                            <label class="form-label pe-2">{{lang('Status')}}</label>
                            <a class="onoffswitch2">
                                <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1"  {{$announcementData->status == 1 ? 'checked' : ''}}>
                                <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" ></label>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                {{-- <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a> --}}
                <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Sauvegarder les modifications')}}</button>
            </div>
        </form>
	</div>
</div>
<!-- End Add Announcement -->




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

@include('admin.announcement.model')

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

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script src="{{gn_asset('assets/plugins/jquery/jquery-ui.js')}}"></script>

 <!-- INTERNAL color pickr -->
 <script src="{{gn_asset('assets/plugins/colorpickr/pickr.min.js')}}"></script>


<!-- INTERNALdatepicker js-->

<script type="text/javascript">

	(function($)  {
		"use strict";

		// Variables
		var SITEURL = '{{url('')}}';
		var now = Date.now();

        //  color pickr code
        // Simple example, see optional options for more configuration.
        window.setColorPicker = (elem, defaultValue) => {
            elem = document.querySelector(elem);
            let pickr = Pickr.create({
                el: elem,
                default: defaultValue,
                theme: 'nano', // or 'monolith', or 'nano'
                useAsButton: true,
                swatches: [
                    '#217ff3',
                    '#11cdef',
                    '#fb6340',
                    '#f5365c',
                    '#f7fafc',
                    '#212529',
                    '#2dce89'
                ],
                components: {
                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,
                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        // hsla: true,
                        // hsva: true,
                        // cmyk: true,
                        input: true,
                        clear: true,
                        silent: true,
                        preview: true,
                    }
                }
            });
            pickr.on('init', pickr => {
                elem.value = pickr.getSelectedColor().toRGBA().toString(0);
            }).on('change', color => {
                elem.value = color.toRGBA().toString(0);
            });

            return pickr;

        }

        // Color Pickr variables
        let themeColor = setColorPicker('#primary_color-input', document.querySelector('#primary_color-input').value);
        let themeColorDark = setColorPicker('#secondary_color-input', document.querySelector('#secondary_color-input').value);

		// Csrf Field
		$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

        setTimeout(()=>{
            document.querySelector(".select2-selection--multiple").onclick = ()=>{
                $('#startdate').val('');
                $('#enddate').val('');
                if(document.getElementById("extraoption")){
                    document.getElementById("extraoption").remove()
                }
            }
        }, 1)

        $('body').on('click', '#startdate', function(e){
            document.querySelectorAll(".select2-selection__choice",".select2-results__option--highlighted").forEach((ele)=>{
                ele.remove()
            })
        });

        $('body').on('click', '#enddate', function(e){
            document.querySelectorAll(".select2-selection__choice").forEach((ele)=>{
                ele.remove()
            })
        });


        // Datepicker
        $('#startdate').datepicker({
            dateFormat: 'yy-mm-dd',
            prevText: '<i class="fa fa-angle-left"></i>',
            nextText: '<i class="fa fa-angle-right"></i>',
            minDate: 0,
            firstDay: {{setting('start_week')}},

            onSelect: function (selectedDate) {

                var diff = ($("#enddate").datepicker("getDate") -
                    $("#startdate").datepicker("getDate")) /
                    1000 / 60 / 60 / 24 + 1; // days
                if ($("#enddate").datepicker("getDate") != null) {
                    $('#count').html(diff);
                    $('#days').val(diff);
                }
                $('#enddate').datepicker('option', 'minDate', selectedDate);
            }
        });
        $('#enddate').datepicker({
            dateFormat: 'yy-mm-dd',
            prevText: '<i class="fa fa-angle-left"></i>',
            nextText: '<i class="fa fa-angle-right"></i>',
            firstDay: {{setting('start_week')}},
            onSelect: function (selectedDate) {

                $('#startdate').datepicker('option', 'maxDate', selectedDate);

                var diff = ($("#enddate").datepicker("getDate") -
                    $("#startdate").datepicker("getDate")) /
                    1000 / 60 / 60 / 24 + 1; // days
                if ($("#startdate").datepicker("getDate") != null) {
                    $('#count').html(diff);
                    $('#days').val(diff);
                }
            }
        });

        // Select2
        $('.form-select').select2({
            multiple: true,
            minimumResultsForSearch: Infinity,
            width:'100%',
            closeOnSelect: false
        });


		// Announcement submit form
		$('body').on('submit', '#testimonial_form', function (e) {
			e.preventDefault();

			var formData = new FormData(this);

			$.ajax({
				type:'POST',
				url: SITEURL + "/admin/announcement/update",
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success: (data) => {
                    toastr.success(data.success);
                    window.location.replace("{{url('/admin/announcement')}}");
				},
				error: function(data){
                    if(data.responseJSON.error){
                        toastr.error(data.responseJSON.error);
                    }
					$('#nameError').html(data.responseJSON.errors.title);
					$('#descriptionError').html(data.responseJSON.errors.notice);
					$('#startdateError').html(data.responseJSON.errors.startdate);
					$('#enddateError').html(data.responseJSON.errors.enddate);
				}
			});
		});

	})(jQuery);

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
                    console.log(error?.response.data.error.message);

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
