
@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

        <!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Email Templates', 'menu')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<div class="row">
								<!-- Email Template Edit -->
								<div class="col-xl-8 col-lg-12 col-md-12">
									<div class="card ">
										<div class="card-header border-0 d-flex">
											<h4 class="card-title">{{lang('Email Template')}}</h4>
                                            @if(setting('enable_gpt') == 'on')
                                                <button class="btn btn-primary ms-auto" id="gptmodal" data-target="#exampleModal234">{{ lang('Ask Chat GPT') }}</button>
                                            @endif
										</div>
										<div class="card-body" >
											<form method="POST" action="{{ route('settings.email.update', [$template->id]) }}" enctype="multipart/form-data">
												@csrf

												@honeypot
												<div class="row">
													<div class="col-sm-12 col-md-12">
														<div class="form-group">
															<label class="form-label">{{lang('Email Subject')}} <span class="text-red">*</span></label>
															<input type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" Value="{{ old('subject', $template->subject) }}">
															@error('subject')

																<span class="invalid-feedback" role="alert">
																	<strong>{{ lang($message) }}</strong>
																</span>
															@enderror

														</div>
													</div>
													<div class="col-sm-12 col-md-12">
														<div class="form-group">
															<label class="form-label">{{lang('Email Body')}} <span class="text-red">*</span></label>
															<textarea class="form-control summernote @error('body') is-invalid @enderror" placeholder="{{lang('FAQ Answer')}}" name="body" id="answer" aria-multiline="true">
																{{ old('body', $template->body) }}
															</textarea>
															@error('body')

																<span class="invalid-feedback" role="alert">
																	<strong>{{ lang($message) }}</strong>
																</span>
															@enderror

														</div>
													</div>
													<div class="col-md-12 card-footer ">
														<div class="form-group float-end mb-0">
															<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="col-xl-4 col-lg-12 col-md-12">
									<div class="card">
										<div class="card-header border-0">
											<h4 class="card-title">{{lang('Email Template Fields')}}</h4>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{Contact_name}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{Contact_email}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{Contact_subject}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{Contact_phone}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{Contact_message}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_username}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_id}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_title}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{comment}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_description}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_customer_url}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_admin_url}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ratinglink}}'; @endphp</div>

													</div>
												</div>
                                                <div class="col-md-6 col-sm-12 p-1">
                                                    <div class="border br-3 p-2">
                                                        <div class="fs-14 font-weight-semibold"><?php echo '{{url}}'; ?></div>

                                                    </div>
                                                </div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_overduetime}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_closingtime}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{ticket_status}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{replystatus}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{username}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{email}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{email_verify_url}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{guestotp}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{guestname}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{guestemail}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{reset_password_url}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{notification_subject}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{notification_message}}'; @endphp</div>

													</div>
												</div>
												<div class="col-md-6 col-sm-12 p-1">
													<div class="border br-3 p-2">
														<div class="fs-14 font-weight-semibold">@php echo '{{notification_tag}}'; @endphp</div>

													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
								<!-- Email Template Edit -->
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

		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

        <!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
        <script src="{{gn_asset('assets/js/support/support-createticket.js')}}"></script>

        <script type="text/javascript">

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
