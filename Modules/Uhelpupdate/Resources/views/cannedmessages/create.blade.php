@extends('layouts.adminmaster')

        @section('styles')

        <!-- INTERNAl Summernote css -->
	    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote.css')}}">

        <!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

        @endsection
							@section('content')

                            <!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader d-flex">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Canned Response Create')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->
                            <div class="row">
                                <div class="col-xl-9 col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header border-0 d-flex">
                                            <h4 class="card-title">{{lang('Canned Response Create')}}</h4>
                                            @if(setting('enable_gpt') == 'on')
                                                <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
                                            @endif
                                        </div>
                                        <form action="{{route('admin.cannedmessages.store')}}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="" class="form-label">{{lang('Canned Response Title')}}</label>
                                                    <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" >
                                                    @error('title')

                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ lang($message )}}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label">{{lang('Canned Response Message')}}</label>
                                                    <textarea  name="message" class="form-control summernote @error('message') is-invalid @enderror"  rows="8" cols="50"></textarea>
                                                    @error('message')

                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ lang($message) }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                                <div class="form-group">
                                                        <div class="switch_section">
                                                            <div class="switch-toggle d-flex mt-4">
                                                                <label class="form-label pe-2">{{lang('Status')}}:</label>
                                                                <a class="onoffswitch2">
                                                                    <input type="checkbox"  name="statuscanned" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                                                    <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" ></label>
                                                                </a>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="form-group float-end">
                                                    <input type="submit" class="btn btn-secondary" value="{{lang('Save')}}">
                                                </div>
                                            </div>
                                        <form>

                                    </div>

                                </div>
                                <div class="col-xl-3 col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h4 class="card-title">Canned Response Fields</h4>
                                        </div>
                                        <div class="card-body pt-2 ps-0 pe-0 pb-0">
                                            <div class="table-responsive tr-lastchild">
                                                <table class="table mb-0 table-information">
                                                    <tbody>

                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{app_name}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">Le nom de l’application</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{site_url}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">L’adresse du site</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{ticket_id}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">Le numéro de l’alerte</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{ticket_user}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">Le <b>nom</b> de la victime ou du client qui a ouvert l’alerte</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{ticket_title}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">L’objet de l’alerte</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{ticket_priority}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">La priorité de l’alerte</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{user_reply}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">Le <b>nom</b> du membre de l’équipe qui répond à l’alerte</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="w-50 text-bold">@php echo '{{user_role}}' @endphp</span>
                                                            </td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="font-weight-semibold">Le rôle du membre de l’équipe</span>
                                                            </td>
                                                        </tr>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                    @csrf
                                                    @honeypot
                                                    <div class="modal-body pb-0 position-relative">
                                                        <div class="form-group">
                                                            <label class="form-label">{{lang('Type your query here')}}</label>
                                                            <div class="d-flex gap-2">
                                                                <input type="text" class="form-control" placeholder="Enter Here" name="" id="spk_gpt">
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
                                                        <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Close')}}</a>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- GPT Model end -->
                                @endif

                            @endsection


        @section('scripts')

        <!-- INTERNAL Summernote js  -->
		<script src="{{asset('assets/plugins/summernote/summernote.js')}}"></script>

        <!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

        <script type="text/javascript">

            "use strict";
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
					// ['table', ['table']],
					['insert', ['link']],
					['view', ['fullscreen']],
					['help', ['help']]
				],
                disableDragAndDrop:true,
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
