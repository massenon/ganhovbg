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
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Create Page', 'menu')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<!-- Privacy Policy & Terms of Use List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
        <div class="card-header border-0">
			<h4 class="card-title">{{lang('Create Pages', 'menu')}}</h4>
            @if(setting('enable_gpt') == 'on')
                <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
            @endif
		</div>
        <form method="POST" enctype="multipart/form-data" action="{{route('pages.storepage')}}">
            <input type="hidden" name="pages_id" id="pages_id">
            @csrf
            @honeypot
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('pagename') is-invalid @enderror" name="pagename" id="pagename" >
                    @error('pagename')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Description')}} <span class="text-red">*</span></label>
                    <textarea class="form-control summernote @error('pagedescription') is-invalid @enderror"  name="pagedescription" id="pagedescription"></textarea>
                    @error('pagedescription')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-controls-stacked d-md-flex  d-md-max-block">
                        <label class="form-label mt-1 me-4">{{lang('View On:')}} <span class="text-red">*</span></label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="both">
                            <span class="custom-control-label">{{lang('View On Both')}}</span>
                        </label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="header">
                            <span class="custom-control-label">{{lang('View on header')}}</span>
                        </label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="footer">
                            <span class="custom-control-label">{{lang('View on footer')}}</span>
                        </label>
                    </div>
                    @error('display')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex  d-md-max-block mt-4 ms-0 ps-0">
                            <label class="form-label pe-1 me-6">{{lang('Status')}}:</label>
                            <a class="onoffswitch2">
                                <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" "></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a> -->
                <button type="submit" class="btn btn-secondary">{{lang('Sauvegarder')}}</button>
            </div>
        </form>
	</div>
</div>
<!-- End Privacy Policy & Terms of Use List -->


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

        $('body').on('click', '#gptmodal', function(){
            $('#spk_gpt').val('');
            $('#answershow').html('');
            $('#addgptmodal').modal('show');
            document.getElementById("copytoresponse-gpt").classList.add("d-none")
        });

	})(jQuery);

</script>

<script type="module">
    import openai from "{{asset('assets/js/openapi/openapi.min.js')}}"

    if(document.getElementById("priority_form1")){
        //Chat GPT
        if(document.getElementById("priority_form1")){
            document.getElementById("priority_form1").disabled = true
        }

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

@section('modal')

	@include('admin.generalpage.model')

@endsection
