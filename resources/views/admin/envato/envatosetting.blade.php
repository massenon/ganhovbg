
@extends('layouts.adminmaster')

@section('styles')


<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

<!-- INTERNAl color css -->
<link rel="stylesheet" href="{{gn_asset('assets/plugins/colorpickr/themes/nano.min.css')}}">

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />


@endsection

@section('content')


<!--Page header-->
<div class="page-header d-xl-flex d-block">
<div class="page-leftheader">
    <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Envato Setting', 'menu')}}</span></h1>
</div>
</div>
<!--End Page header-->

<div class="row">

<!-- Envato Expires follow this url -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-bottom">
            <h4 class="card-title">{{lang('Envato Setting', 'setting')}}</h4>
        </div>
        <form action="{{route('settings.expiredsupport')}}" method="POST">
        @csrf
            <div class="card-body pt-2 pb-2" >
                <div class="row">

                    <!--- Enable purchase code to employees --->
                    <div class="border-bottom">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('purchasecode_on') ? ' has-danger' : '' }}">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <a class="onoffswitch2">
                                            <input type="checkbox" id="purchasecode_on" name="purchasecode_on" value="on"  class="toggle-class onoffswitch2-checkbox"  @if(setting('purchasecode_on') == 'on') checked="" @endif>
                                            <label for="purchasecode_on" class="toggle-class onoffswitch2-label" ></label>
                                        </a>
                                        <div class="ps-3">
                                            <label class="form-label d-inline">{{lang('Enable purchase code to employees', 'setting')}}</label>
                                            <small class="text-muted"><i>({{lang('If you enable this, employees can see the purchase code submitted by customer’s.', 'setting')}})</i></small>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('purchasecode_on'))

                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('purchasecode_on') }}</strong>
                                </span>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!--- END Enable purchase code to employees --->

                    <!--- Envato Expired On --->
                    <div class="border-bottom">
                        <div class="col-sm-12 col-md-12 pt-2">
                            <div class="form-group {{ $errors->has('ENVATO_EXPIRED_BLOCK') ? ' has-danger' : '' }}">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex mt-4">
                                        <a class="onoffswitch2">
                                            <input type="checkbox" id="envato_expired_on" name="ENVATO_EXPIRED_BLOCK" value="on"  class="toggle-class onoffswitch2-checkbox"  @if(setting('ENVATO_EXPIRED_BLOCK') == 'on') checked="" @endif>
                                            <label for="envato_expired_on" class="toggle-class onoffswitch2-label" ></label>
                                        </a>
                                        <div class="ps-3">
                                            <label class="form-label d-inline">{{lang('Envato Expired On', 'setting')}}</label>
                                            <small class="text-muted"><i>({{lang('If you enable this Envato Expired switch, customer’s and guest’s cannot create ticket with an expired license.', 'setting')}})</i></small>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('ENVATO_EXPIRED_BLOCK'))

                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ENVATO_EXPIRED_BLOCK') }}</strong>
                                </span>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!--- END Envato Expired On --->

                    <div class="col-xl-12 col-lg-12 col-md-12 mt-4 ms-5">
                        <div class="form-group ">
                            <label for="" class="form-label d-inline">{{lang('Support policy URL', 'setting')}} <span class="text-red">*</span></label>
                            <small class="text-muted"><i>({{lang('If you enable this Envato Expired switch, customer’s and guest’s cannot create ticket with an expired license.', 'setting')}})</i></small>
                            <input class="form-control col-md-6 mt-3 pb-1 {{ $errors->has('SUPPORT_POLICY_URL') ? ' is-invalid' : '' }}" placeholder="{{lang('https://example.com')}}" name="SUPPORT_POLICY_URL" type="text" value="{{ old('SUPPORT_POLICY_URL', setting('SUPPORT_POLICY_URL')) }}" >

                            @if ($errors->has('SUPPORT_POLICY_URL'))

                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('SUPPORT_POLICY_URL') }}</strong>
                            </span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 card-footer ">
                <div class="form-group float-end ">
                    <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Envato Expires follow this url  -->


@endsection

@section('scripts')


<!-- INTERNAL Summernote js  -->
<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
<script src="{{gn_asset('assets/js/select2.js')}}"></script>

<!-- INTERNAL color pickr -->
<script src="{{gn_asset('assets/plugins/colorpickr/pickr.min.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>



<script type="text/javascript">

    "use strict";


</script>

@endsection
