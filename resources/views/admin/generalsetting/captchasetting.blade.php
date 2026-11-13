
@extends('layouts.adminmaster')



@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Captcha Setting', 'menu')}}</span></h1>
    </div>
</div>
<!--End Page header-->

<!--Captcha Enable/Disable-->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0 d-sm-max-flex">
            <h4 class="card-title">{{lang('Captcha Enable/Disable')}}</h4>
            <div class="card-options card-header-styles mt-sm-max-2">
                <small class="me-1 mt-1">{{lang('Captcha Disable', 'setting')}}</small>
                <div class="float-end mt-0">
                    <div class="switch-toggle">
                        <a class="onoffswitch2">
                        <input type="radio"  name="CAPTCHATYPE" id="myonoffswitch181" class=" toggle-class onoffswitch2-checkbox sprukocaptcha" value="off" @if(setting('CAPTCHATYPE') == 'off') checked="" @endif>
                        <label for="myonoffswitch181" class="toggle-class onoffswitch2-label"></label>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" >
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="radio"  name="CAPTCHATYPE" id="myonoffswitch180" class=" toggle-class onoffswitch2-checkbox sprukocaptcha" value="manual"  @if(setting('CAPTCHATYPE') == 'manual') checked="" @endif>
                        <label for="myonoffswitch180" class="toggle-class onoffswitch2-label"></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Manual Captcha Enable', 'setting')}}</label>
                    <small class="text-muted ps-1 ps-md-max-0"><i>({{lang('This setting will enable the "Manual" captcha.', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="radio"  name="CAPTCHATYPE" id="myonoffswitch182" class=" toggle-class onoffswitch2-checkbox sprukocaptcha" value="google"  @if(setting('CAPTCHATYPE') == 'google') checked="" @endif>
                        <label for="myonoffswitch182" class="toggle-class onoffswitch2-label"></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Google Captcha Enable', 'setting')}}</label>
                    <small class="text-muted ps-1 ps-md-max-0"><i>({{lang('This setting will enable the "Google" captcha.', 'setting')}})</i></small>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Captcha Enable/Disable-->

<!--Google Re-Captcha Setting-->
<div class="col-xl-12 col-lg-12 col-md-12" id="hidesettings">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Google Re-Captcha Setting')}}</h4>
        </div>
        <form method="POST" action="{{ route('settings.captcha.store') }}" enctype="multipart/form-data">
            <div class="card-body" >
                @csrf
                @honeypot
                <div class="row">
                    <div class="col-sm-12 col-md-12 ">
                        <div class="form-group {{ $errors->has('googlerecaptchakey') ? ' has-danger' : '' }}">
                            <label class="form-label">{{lang('Site Key')}}</label>
                            <input type="text" class="form-control {{ $errors->has('googlerecaptchakey') ? ' is-invalid' : '' }}"  name="googlerecaptchakey" placeholder="{{lang('Site key')}}" value="{{ old('googlerecaptchakey', setting('GOOGLE_RECAPTCHA_KEY')) }}">
                            @if ($errors->has('googlerecaptchakey'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('googlerecaptchakey') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('googlerecaptchasecret') ? ' has-danger' : '' }}">
                            <label class="form-label">{{lang('Secret Key')}}</label>
                            <input type="text" class="form-control {{ $errors->has('googlerecaptchasecret') ? ' is-invalid' : '' }}" name="googlerecaptchasecret" placeholder="{{lang('Secret Key')}}" value="{{ old('googlerecaptchasecret', setting('GOOGLE_RECAPTCHA_SECRET')) }}">
                            @if ($errors->has('googlerecaptchasecret'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('googlerecaptchasecret') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <div class="form-group float-end">
                    <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<!--End Google Re-Captcha Setting-->

<!--Captcha Setting In Forms-->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Captcha Setting in Forms')}}</h4>
        </div>
        <div class="card-body">
            <div class="switch_section">
                <div class="switch-toggle d-flex mt-4 d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="RECAPTCH_ENABLE_CONTACT" id="myonoffswitch12" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('RECAPTCH_ENABLE_CONTACT') == 'yes') checked="" @endif>
                        <label for="myonoffswitch12" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable On Contact Form', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this captcha setting feature, it will appear on the "Contact Form".', 'setting')}})</i></small>
                </div>

            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex mt-4 d-md-max-block">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="RECAPTCH_ENABLE_REGISTER" id="myonoffswitch1" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('RECAPTCH_ENABLE_REGISTER') == 'yes') checked="" @endif>
                        <label for="myonoffswitch1" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable On Register Form', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this captcha setting feature, it will appear on the "Register Form".', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex mt-4 d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="RECAPTCH_ENABLE_LOGIN" id="myonoffswitch11" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('RECAPTCH_ENABLE_LOGIN') == 'yes') checked="" @endif>
                        <label for="myonoffswitch11" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable On Login Form', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this captcha setting feature, it will appear on the "Login Form".', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex mt-4 d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="RECAPTCH_ENABLE_GUEST" id="myonoffswitch112" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('RECAPTCH_ENABLE_GUEST') == 'yes') checked="" @endif>
                        <label for="myonoffswitch112" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable On Guest Ticket', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this captcha setting feature, it will appear on the "Guest Ticket".', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex mt-4 d-md-max-block">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="RECAPTCH_ENABLE_ADMIN_LOGIN" id="myonoffswitch1102" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN') == 'yes') checked="" @endif>
                        <label for="myonoffswitch1102" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable On Admin Login Form', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this captcha setting feature, it will appear on the "Admin Login Form".', 'setting')}})</i></small>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Captcha Setting In Forms-->

@endsection

@section('scripts')


<script type="text/javascript">

    "use strict";

    (function($)  {


        if(document.querySelector("#myonoffswitch181").checked || document.querySelector("#myonoffswitch180").checked){
            document.getElementById('hidesettings').classList.add('d-none');
        }

        document.querySelectorAll('#myonoffswitch182, #myonoffswitch180, #myonoffswitch181').forEach( e => {
            e.addEventListener('click', function(){
                if(document.getElementById('myonoffswitch182').checked){
                document.getElementById('hidesettings').classList.remove('d-none');
                }
                else{
                document.getElementById('hidesettings').classList.add('d-none');
                }
            })
        })

        // Csrf Field
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // enable on captcha contact
        $('#myonoffswitch12').on('change', function() {
            var status = $(this).prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/captchacontact')}}',
                data: {'RECAPTCH_ENABLE_CONTACT': status},
                success: function(data){
                    if(toastr.success('{{lang('Updated successfully', 'alerts')}}')){
                        location.reload();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        // enable on captcha register
        $('#myonoffswitch1').on('change', function() {
            var status = $(this).prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/captcharegister')}}',
                data: {'RECAPTCH_ENABLE_REGISTER': status},
                success: function(data){
                    if(toastr.success('{{lang('Updated successfully', 'alerts')}}')){
                        location.reload();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        // enable on captcha login
        $('#myonoffswitch11').on('change', function() {
            var status = $(this).prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/captchalogin')}}',
                data: {'RECAPTCH_ENABLE_LOGIN': status},
                success: function(data){
                    if(toastr.success('{{lang('Updated successfully', 'alerts')}}')){
                        location.reload();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        // enable on captcha guest
        $('#myonoffswitch112').on('change', function() {
            var status = $(this).prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/captchaguest')}}',
                data: {'RECAPTCH_ENABLE_GUEST': status},
                success: function(data){
                    if(toastr.success('{{lang('Updated successfully', 'alerts')}}')){
                        location.reload();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

        // enable on captcha type
        $('.sprukocaptcha').on('change', function(){
            var captchatype = $(this).val()
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{url('admin/captchatype')}}',
                data:{'captchatype':captchatype},

                success:function(data){
                    toastr.success(data.success);
                },
                error:function(data){
                    toastr.error('{{lang('Setting Not Updated', 'alerts')}}');
                }
            });

        });

        // enable on captcha AdMIN login
        $('#myonoffswitch1102').on('change', function() {
            var status = $(this).prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/captchaadminlogin')}}',
                data: {
                    'RECAPTCH_ENABLE_ADMIN_LOGIN': status
                },
                success: function(data){
                    if(toastr.success('{{lang('Updated Successfully!', 'alerts')}}')){
                        location.reload();
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });

    })(jQuery);
</script>

@endsection
