@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL multiselecte css-->
<link href="{{gn_asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
<link href="{{gn_asset('assets/plugins/multi/multi.min.css')}}" rel="stylesheet" />

<!-- INTERNAl color css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/colorpickr/themes/nano.min.css')}}">

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h1 class="page-title">
            <span class="font-weight-normal text-muted ms-2">{{lang('Employees')}}</span>
        </h1>
    </div>
    <div class="page-rightheader ms-md-auto">
        <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
            <div class="d-flex">
                <div class="btn-list">
                    @can('Custom Notifications Customer')

                    <a href="{{route('mail.customer')}}" class="btn btn-info ">{{lang('Compose for Customers')}}</a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">{{lang('Compose Notification For Employees')}}</h3>
                @if(setting('enable_gpt') == 'on')
                    <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
                @endif
            </div>
            <form action="{{route('mail.employeesend')}}" method="post" onsubmit="submitEmployee()">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="align-items-center">
                                    <label class="form-label">{{lang('To')}} <span class="text-red">*</span></label>
                                        <div class="custom-controls-stacked d-md-flex @error('users') is-invalid @enderror" id="projectdisable">
                                            <select multiple="multiple" class="filter-multi "  id="users"  name="users[]" >
                                                @foreach ($users as $item)
                                                    @if ($item->id != Auth::id())
                                                    @if(!empty($item->getRoleNames()[0]))

                                                    <option value="{{$item->id}}" @if(old('users') == $item->id) selected @endif>{{$item->name}}({{$item->getRoleNames()[0]}})</option>
                                                    @endif
                                                    @endif
                                                @endforeach

                                            </select>

                                        </div>
                                        @error('users')

                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="align-items-center">
                                    <label class="form-label">{{lang('Subject')}} <span class="text-red">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject')}}" name="subject">
                                        @error('subject')

                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class=" align-items-center">
                                        <label class="form-label">{{lang('Tag')}} <span class="text-red">*</span></label>
                                        <input type="text" class="form-control @error('tag') is-invalid @enderror" value="{{old('tag')}}" name="tag">
                                        @error('tag')

                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                    <label class="form-label">{{lang('Select Tag Color')}} <span class="text-red">*</span></label>
                                    <div>
                                        <input type="text" class="form-control @error('selecttagcolor') is-invalid @enderror" value="rgba(116, 53, 53, 1)" name="selecttagcolor" id="selecttagcolor">
                                        @error('selecttagcolor')

                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="form-label">{{lang('Message')}} <span class="text-red">*</span></label>
                                <textarea rows="10" class="summernote form-control @error('message') is-invalid @enderror" name="message">{{old('message')}}</textarea>
                                    @error('message')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-sm-flex">
                    <div class="btn-list ms-auto">
                        <button id="employee-submit" type="submit" class="btn btn-primary btn-space">{{lang('Send Message')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



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

<!-- INTERNAL multiselecte js-->
<script src="{{gn_asset('assets/plugins/multipleselect/multiple-select.js')}}"></script>
<script src="{{gn_asset('assets/plugins/multipleselect/multi-select.js')}}"></script>

<!-- INTERNAL color pickr -->
<script src="{{gn_asset('assets/plugins/colorpickr/pickr.min.js')}}"></script>

<!-- INTERNAL Summernote js  -->
<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script type="text/javascript">

    "use strict";

    // Summernote
    $('.summernote').summernote({
        placeholder: '',
        tabsize: 1,
        height: 200,
        toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], // ['height', ['height']],
        ['table', ['table']], ['insert', ['link']], ['view', ['fullscreen']], ['help', ['help']]],
        callbacks: {
            onImageUpload: function(e){}
        },
    });

    (() => {

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
        let selecttagcolor = setColorPicker('#selecttagcolor', document.querySelector('#selecttagcolor').value);

    })();

    function submitEmployee() {
        $('#employee-submit').html(`Sending.. <i class="fa fa-spinner fa-spin"></i>`);
        $('#employee-submit').prop('disabled', true);
    }

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

