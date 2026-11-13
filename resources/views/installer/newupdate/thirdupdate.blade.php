@extends('layouts.updatemaster')

@section('title')
    {{ trans('Update version') }} {{$version}}
@endsection


@section('container')
    <form method="post" action="{{route('update.datachecking')}}" class="tabs-wrap">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">

            <div class="form-group col-6 {{ $errors->has('app_firstname') ? ' has-error ' : '' }}">
                <label for="app_firstname">
                    {{ trans('Enter Your Firstname') }}
                    <span class="text-red">*</span>
                </label>
                <input type="text" name="app_firstname" id="app_firstname" value="" placeholder="{{ trans('Enter Your Firstname') }}" />
                @if ($errors->has('app_firstname'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('app_firstname') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-6 {{ $errors->has('app_lastname') ? ' has-error ' : '' }}">
                <label for="app_lastname">
                    {{ trans('Enter Your Lastname') }}
                    <span class="text-red">*</span>
                </label>
                <input type="text" name="app_lastname" id="app_lastname" value="" placeholder="{{ trans('Enter Your Lastname') }}" />
                @if ($errors->has('app_lastname'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('app_lastname') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-6 {{ $errors->has('app_email') ? ' has-error ' : '' }}">
                <label for="app_email">
                    {{ trans('Enter Your Email') }}
                    <span class="text-red">*</span>
                </label>
                <input type="email" name="app_email" id="app_email" value="" placeholder="{{ trans('Enter Your Email') }}" />
                @if ($errors->has('app_email'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('app_email') }}
                    </span>
                @endif
            </div>

            <div class="form-group col-6 {{ $errors->has('envato_purchasecode') ? ' has-error ' : '' }}">
                <label for="envato_purchasecode">
                    {{ trans('Enter the Envato Purchase Code') }}
                    <span class="text-red">*</span>
                </label>
                <div class="pos-relative">
                    <input type="text" name="envato_purchasecode" id="envato_purchasecode" value="" placeholder="{{ trans('Enter the Envato Purchase Code ') }}" />
                </div>

                @if ($errors->has('envato_purchasecode'))
                    <span class="error-block">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ $errors->first('envato_purchasecode') }}
                    </span>
                @elseif($message = Session::get('error'))
                    <span class="error-block text-red">
                        <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        {{ trans($message) }}
                    </span>
                @endif

            </div>
        </div>

        <div class="buttons">
            <button class="button" type="submit" id="buttonfinal" onclick="button(this)">
                {{ trans('Update') }}
                <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
            </button>
        </div>
    </form>

@endsection
@section('scripts')

        <script type="text/javascript">

            "use strict";


            function button(bt){
                document.getElementById("buttonfinal").innerHTML = `Please Wait... <i class="fa fa-spinner fa-spin"></i>`;
                bt.disabled = true;
                bt.form.submit();
                document.getElementById("buttonfinal").style.cursor = "not-allowed";
                document.getElementById("buttonfinal").style.opacity = "0.5";
            }

        </script>

@endsection
