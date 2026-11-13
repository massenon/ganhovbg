
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
    <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('General Setting', 'menu')}}</span></h1>
</div>
</div>
<!--End Page header-->

<div class="row">
<!-- App Title & Logos -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('App Title & Logos', 'setting')}}</h4>
        </div>
        <form method="POST" action="{{url('/admin/general')}}" enctype="multipart/form-data">
        <div class="card-body" >
                @csrf

                @honeypot
                <input type="hidden" class="form-control" name="id" Value="{{$basic->id}}">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" Value="{{$basic->title}}" >
                            @error('title')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-xl-4 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-7 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('image') is-invalid @enderror ">
                                            <label class="form-label fs-16">{{lang('Upload Light-Logo', 'setting')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="image" type="file" >

                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                        </div>
                                        @error('image')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto sprukologoss ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if($title->image == null)


                                            <img src="{{asset('uploads/logo/logo/logo-white.png')}}" class="br-5" alt="logo">
                                            @else

                                            <button class="btn ticketnotedelete border-white text-gray logosdelete" value="logo1" data-id="{{$title->id}}">
                                                <i class="feather feather-x" ></i>
                                            </button>
                                            <img src="{{asset('uploads/logo/logo/'.$title->image)}}" class="br-5" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-7 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('image1') is-invalid @enderror">
                                            <label class="form-label fs-16">{{lang('Upload Dark-Logo', 'setting')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="image1" type="file" >
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                        </div>
                                        @error('image1')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if($title->image1 == null)

                                            <img src="{{asset('uploads/logo/darklogo/logo.png')}}" class="br-5" alt="logo">
                                            @else

                                            <button class="btn ticketnotedelete border-white text-gray logosdelete" value="logo2" data-id="{{$title->id}}">
                                                <i class="feather feather-x" ></i>
                                            </button>
                                            <img src="{{asset('uploads/logo/darklogo/'.$title->image1)}}" class="br-5" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-7 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('image2') is-invalid @enderror">
                                            <label class="form-label fs-16">{{lang('Upload Dark-Icon', 'setting')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="image2" type="file" >
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                        </div>
                                        @error('image2')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if($title->image2 == null)

                                            <img src="{{asset('uploads/logo/icon/icon.png')}}" class="br-5" alt="logo">
                                            @else

                                            <button class="btn ticketnotedelete border-white text-gray logosdelete" value="logo3" data-id="{{$title->id}}">
                                                <i class="feather feather-x" ></i>
                                            </button>
                                            <img src="{{asset('uploads/logo/icon/'.$title->image2)}}" class="br-5" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-7 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('image3') is-invalid @enderror">
                                            <label class="form-label fs-16">{{lang('Upload Light-Icon', 'setting')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="image3" type="file" >
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                        </div>
                                        @error('image3')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if($title->image3 == null)

                                            <img src="{{asset('uploads/logo/darkicon/icon-white.png')}}" class="br-5" alt="logo">
                                            @else

                                            <button class="btn ticketnotedelete border-white text-gray logosdelete" value="logo4" data-id="{{$title->id}}">
                                                <i class="feather feather-x" ></i>
                                            </button>
                                            <img src="{{asset('uploads/logo/darkicon/'.$title->image3)}}" class="br-5" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-7 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('image4') is-invalid @enderror">
                                            <label class="form-label fs-16">{{lang('Upload Favicon', 'setting')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="image4" type="file" >
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                        </div>
                                        @error('image4')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if($title->image4 == null)

                                            <img src="{{asset('uploads/logo/favicons/favicon.ico')}}" class="br-5" alt="logo">
                                            @else

                                            <button class="btn ticketnotedelete border-white text-gray logosdelete" value="logo5" data-id="{{$title->id}}">
                                                <i class="feather feather-x" ></i>
                                            </button>
                                            <img src="{{asset('uploads/logo/favicons/'.$title->image4)}}" class="br-5" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-12 card-footer ">
                <div class="form-group float-end">
                    <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End App Title & Logos -->


<!-- Custom pages -->

<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Set URL', 'setting')}}</h4>
        </div>
        <form action="{{route('settings.url.urlset')}}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Terms of service Url', 'setting')}} <span class="text-red">*</span></label>
                            <input class="form-control {{ $errors->has('terms_url') ? ' is-invalid' : '' }}" placeholder="{{lang('https://example.com')}}" name="terms_url" type="text" value="{{ old('terms_url', setting('terms_url')) }}" >

                            @if ($errors->has('terms_url'))

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('terms_url') }}</strong>
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
<!-- End Custom pages -->

<!-- Color Setting -->
<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Color Setting', 'setting')}}</h4>
        </div>
        <form action="{{route('settings.color.colorsetting')}}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Primary Color', 'setting')}} <span class="text-red">*</span></label>
                            <input class="form-control {{ $errors->has('theme_color') ? ' is-invalid' : '' }}" name="theme_color" type="text" value="{{ old('theme_color', setting('theme_color')) }}" id="theme_color-input">

                            @if ($errors->has('theme_color'))

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('theme_color') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Secondary Color', 'setting')}} <span class="text-red">*</span></label>
                            <input class="form-control {{ $errors->has('theme_color_dark') ? ' is-invalid' : '' }}" name="theme_color_dark" type="text" value="{{ old('theme_color_dark', setting('theme_color_dark')) }}" id="theme_color_dark-input">

                            @if ($errors->has('theme_color_dark'))

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('theme_color_dark') }}</strong>
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
<!-- Color Setting -->

<!-- Global Language Setting -->
<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Global Language Setting', 'setting')}}</h4>
        </div>
        <form action="{{ route('settings.lang.store') }}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="form-group mb-4">
                <label  class="form-label">{{lang('Select Language', 'setting')}}</label>
                    <select name="default_lang" id="input-default_lang" class="form-control select2 select2-show-search" required>
                        @foreach(getLanguageslist() as $key => $lang)

                            <option value="{{ $lang->languagecode }}" {{ old('default_lang', setting('default_lang'))==$lang->languagecode ? 'selected' :'' }}>{{Str::upper($lang->languagename)}}</option>
                        @endforeach

                    </select>
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
<!-- Global Language Setting -->

<!-- Date and Time Format -->
<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Global Date & Time Format', 'setting')}}</h4>
        </div>
        <form action="{{ route('settings.timedateformat.store') }}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group mb-4">
                            <label  class="form-label">{{lang('Select Date Format', 'setting')}}</label>
                            <select name="date_format" id="input-date_format" class="form-control select2 select2-show-search" required>

                                <option value="d M, Y" {{setting('date_format') == 'd M, Y' ? 'selected' : ''}}>d M, Y</option>
                                <option value="m.d.y" {{setting('date_format') == 'm.d.y' ? 'selected' : ''}}>m.d.y</option>
                                <option value="Y-m-d" {{setting('date_format') == 'Y-m-d' ? 'selected' : ''}}>Y-m-d</option>
                                <option value="d-m-Y" {{setting('date_format') == 'd-m-Y' ? 'selected' : ''}}>d-m-Y</option>
                                <option value="d/m/Y" {{setting('date_format') == 'd/m/Y' ? 'selected' : ''}}>d/m/Y</option>
                                <option value="Y/m/d" {{setting('date_format') == 'Y/m/d' ? 'selected' : ''}}>Y/m/d</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group mb-4">
                            <label  class="form-label">{{lang('Select Time Format', 'setting')}}</label>
                            <select name="time_format" id="input-time_format" class="form-control select2 select2-show-search" required>

                                <option value="h:i A" {{setting('time_format') == 'h:i A' ? 'selected' : ''}}>03:00 PM</option>
                                <option value="h:i:s A" {{setting('time_format') == 'h:i:s A' ? 'selected' : ''}}>03:00:02 PM</option>
                                <option value="H:i" {{setting('time_format') == 'H:i' ? 'selected' : ''}}>15:00</option>
                                <option value="H:i:s" {{setting('time_format') == 'H:i:s' ? 'selected' : ''}}>15:00:02</option>

                            </select>
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
<!-- Date and Time Format -->

<!--- Start Week Days -->
<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('First Day of the Week', 'setting')}}</h4>
        </div>
        <form action="{{ route('settings.startweek.store') }}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="form-group mb-4">
                    <label  class="form-label">{{lang('Select Day', 'setting')}}</label>
                    <select name="start_week" id="input-start_week" class="form-control select2 select2-show-search" required>

                        <option value="0" {{setting('start_week') == '0' ? 'selected' : ''}}>Sunday</option>
                        <option value="1" {{setting('start_week') == '1' ? 'selected' : ''}}>Monday</option>
                        <option value="2" {{setting('start_week') == '2' ? 'selected' : ''}}>Tuesday</option>
                        <option value="3" {{setting('start_week') == '3' ? 'selected' : ''}}>Wednesday</option>
                        <option value="4" {{setting('start_week') == '4' ? 'selected' : ''}}>Thursday</option>
                        <option value="5" {{setting('start_week') == '5' ? 'selected' : ''}}>Friday</option>
                        <option value="6" {{setting('start_week') == '6' ? 'selected' : ''}}>Saturday</option>

                    </select>
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
<!--- Start Week Days -->

<!-- TimeZones -->
<div class="col-xl-6 col-lg-6">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Global Timezones', 'setting')}}</h4>
        </div>
        <form action="{{ route('settings.timezone.store') }}" method="POST">
            @csrf

            <div class="card-body" >
                <div class="form-group mb-4">
                    <label  class="form-label">{{lang('Select Global Timezones', 'setting')}}</label>
                    <select name="timezones" class="form-control select2 select2-show-search" id="">
                        @foreach($timezones  as $group => $timezoness)

                            <option value="{{$timezoness->timezone}}" {{$timezoness->timezone == setting('default_timezone') ? 'selected' : ''}}>{{$timezoness->timezone}} {{$timezoness->utc}}</option>

                        @endforeach
                    </select>
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
<!-- End TimeZone -->

    <!-- Contact us email -->
    <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Contact Us', 'setting')}}</h4>
        </div>
        <div class="switch_section my-0 ps-3">
            <div class="switch-toggle d-flex d-md-max-block mt-4">
                <a class="onoffswitch2">
                <input type="checkbox" name="CONTACT_ENABLE" id="contact" class=" toggle-class onoffswitch2-checkbox enablemenus" value="yes" @if(setting('CONTACT_ENABLE') == 'yes') checked="" @endif>
                <label for="contact" class="toggle-class onoffswitch2-label" ></label>
                </a>
                <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Contact Us', 'setting')}}</label>
                <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, the "Contact Us" option will appear in the application’s landing page.', 'setting')}})</i></small>
            </div>
        </div>
        <form action="{{ route('settings.contactemail.store') }}" method="POST">
            @csrf
            <div class="card-body pt-2">
                <div class="form-group d-flex d-md-max-block">
                    <label  class="form-label">{{lang('Enter Contact us Email', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('Enter an email address to receive contact-us form details.', 'setting')}})</i></small>
                </div>
                <div class="col-xl-6">
                    <input type="email" placeholder="{{lang('admin@example.com')}}" name="contact_form_mail" class="form-control @error('contact_form_mail') is-invalid @enderror" value="{{ old('contact_form_mail', setting('contact_form_mail')) }}">
                    @error('contact_form_mail')

                        <span class="invalid-feedback" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror

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
<!-- End Contact us email -->

<!-- Chat GPT Open AI -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Chat GPT', 'setting')}}</h4>
        </div>
        <div class="switch_section my-0 ps-3">
            <div class="switch-toggle d-flex d-md-max-block mt-4">
                <a class="onoffswitch2">
                    <input type="checkbox" name="enable_gpt" id="enable_gpt" class=" toggle-class onoffswitch2-checkbox enablemenus" value="yes" @if(setting('enable_gpt') == 'on') checked="" @endif>
                    <label for="enable_gpt" class="toggle-class onoffswitch2-label" ></label>
                </a>
                <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Chat GPT', 'setting')}}</label>
                <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('By enabling this setting, you will be able to use chat gpt to generate text for canned response, email templates, custom notifications, articles and announcements.', 'setting')}})</i></small>
            </div>
        </div>
        <form action="{{ route('settings.chatgpt.store') }}" method="POST">
            @csrf
            <div class="card-body pt-2">
                <div class="form-group d-flex d-md-max-block">

                    <label  class="form-label">{{lang('Enter OpenAI Chat GPT API Secret Key', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('Enter the OpenAI Chat GPT API Secret Key to use Chat GPT in your application.', 'setting')}})</i></small>
                </div>

                @if(setting('enable_gpt') == 'on')
                    <div class="col-xl-6">
                        <input type="text" placeholder="Saisissez votre clé d’API" name="openai_api" class="form-control @error('openai_api') is-invalid @enderror" value="{{ old('openai_api', setting('openai_api')) }}">
                        @error('openai_api')

                            <span class="invalid-feedback" role="alert">
                                <strong>{{ lang($message) }}</strong>
                            </span>
                        @enderror

                    </div>
                @else
                    @if(setting('openai_api') != null)

                        <div class="col-xl-6">
                            <input type="text" placeholder="Saisissez votre clé d’API" name="openai_api" class="form-control @error('openai_api') is-invalid @enderror" value="{{ old('openai_api', setting('openai_api')) }}" readonly>
                            @error('openai_api')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                    @endif
                @endif

            </div>
            <div class="col-md-12 card-footer ">
                <div class="form-group float-end ">
                <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Chat GPT Open AI -->

<!-- Customer/Guest Delete -->
<div class="col-xl-12 col-lg-12 col-md-12">
<div class="card">
    <div class="card-header border-0">
        <h4 class="card-title">{{lang('Inactive Customer/Guest Delete ', 'setting')}}</h4>
    </div>
    <form action="{{route('admin.customerprofiledelete')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <!---Customer Profile Delete Notify--->
            <div class="border-bottom">
            <div class="col-sm-12 col-md-12">
                <div class="form-group {{ $errors->has('customer_inactive_notify') ? ' has-danger' : '' }}">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                        <a class="onoffswitch2">
                        <input type="checkbox" id="myonoffswitch18" name="customer_inactive_notify" value="on"  class=" toggle-class onoffswitch2-checkbox"  @if(setting('customer_inactive_notify') == 'on') checked="" @endif>
                        <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" ></label>
                        </a>
                        <div class="ps-3">
                            <label class="form-label">{{lang('Customer Profile Auto Delete Enable', 'setting')}}</label>
                            <small class="text-muted ">
                            <i>({{lang('If you enable this ticket setting, inactive customers will receive an email after the time period specified below (Inactive Months) stating that their account has been unused since then and will be deleted automatically after the specified (Customer Delete Days).', 'setting')}})</i></small>
                        </div>
                        </div>
                    </div>
                    @if ($errors->has('customer_inactive_notify'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('customer_inactive_notify') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-12 ms-7 ps-3 ">
                <div class="form-group d-flex d-md-max-block {{ $errors->has('customer_inactive_notify_date') ? ' is-invalid' : '' }}">
                    <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2 "  name="customer_inactive_notify_month"  value="{{old('customer_inactive_notify_date', setting('customer_inactive_notify_date')) }}">
                    <label class="form-label mt-2 ms-2">{{lang('Inactive Months', 'setting')}}</label>
                </div>
                @if ($errors->has('customer_inactive_notify_date'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('customer_inactive_notify_date') }}</strong>
                </span>
                @endif
            </div>
            <div class="col-sm-12 col-md-12 ms-7">
                <div class="form-group d-flex d-md-max-block {{ $errors->has('customer_inactive_week_date') ? ' is-invalid' : '' }}">
                    <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2"  name="customer_inactive_days"  value="{{old('customer_inactive_week_date', setting('customer_inactive_week_date')) }}">
                    <label class="form-label  mt-2 ms-2">{{lang('Customer Delete Days', 'setting')}}</label>
                </div>
                @if ($errors->has('customer_inactive_week_date'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('customer_inactive_week_date') }}</strong>
                </span>
                @endif
            </div>
            </div>
            <div>
            <div class="col-sm-12 col-md-12">
                <div class="form-group {{ $errors->has('guest_inactive_notify') ? ' has-danger' : '' }}">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                        <a class="onoffswitch2">
                        <input type="checkbox" id="guestdelete" name="guest_inactive_notify" value="on"  class=" toggle-class onoffswitch2-checkbox"  @if(setting('guest_inactive_notify') == 'on') checked="" @endif>
                        <label for="guestdelete" class="toggle-class onoffswitch2-label" ></label>
                        </a>
                        <div class="ps-3">
                            <label class="form-label">{{lang('Guest Profile Auto Delete Enable', 'setting')}}</label>
                            <small class="text-muted ">
                            <i>({{lang('If you enable this ticket setting, inactive guests will receive an email after the time period specified below (Inactive Months) stating that their account has been unused since then and will be deleted automatically after the specified (Guest Delete Days).', 'setting')}})</i></small>
                        </div>
                        </div>
                    </div>
                    @if ($errors->has('guest_inactive_notify'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('guest_inactive_notify') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-12 ms-7 ps-3 ">
                <div class="form-group d-flex d-md-max-block {{ $errors->has('guest_inactive_notify_date') ? ' is-invalid' : '' }}">
                    <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2 "  name="guest_inactive_notify_month"  value="{{old('guest_inactive_notify_date', setting('guest_inactive_notify_date')) }}">
                    <label class="form-label mt-2 ms-2">{{lang('Inactive Months', 'setting')}}</label>
                </div>
                @if ($errors->has('guest_inactive_notify_date'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('guest_inactive_notify_date') }}</strong>
                </span>
                @endif
            </div>
            <div class="col-sm-12 col-md-12 ms-7">
                <div class="form-group d-flex d-md-max-block {{ $errors->has('guest_inactive_week_date') ? ' is-invalid' : '' }}">
                    <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2"  name="guest_inactive_days"  value="{{old('guest_inactive_week_date', setting('guest_inactive_week_date')) }}">
                    <label class="form-label  mt-2 ms-2">{{lang('Guest Delete Days', 'setting')}}</label>
                </div>
                @if ($errors->has('guest_inactive_week_date'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('guest_inactive_week_date') }}</strong>
                </span>
                @endif
            </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="form-group float-end">
            <button type="submit" class="btn btn-secondary">{{lang('Sauvegarder')}}</button>
            </div>
        </div>
    </form>
</div>
</div>
<!-- Customer/Guest Delete -->

<!-- Switches -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('App Global Settings', 'setting')}}</h4>
        </div>
        <div class="card-body">
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="checkbox" id="sprukoadminp" class=" toggle-class onoffswitch2-checkbox sprukoregister" @if(setting('SPRUKOADMIN_P') == 'on') checked="" @endif>
                        <label for="sprukoadminp" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Dark Mode Switch For Admin Panel User’s', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, the "Switch to Dark-Mode" option will disappear from the Admin panel user’s profile page.', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="checkbox" id="sprukoadminc" class=" toggle-class onoffswitch2-checkbox sprukoregister" @if(setting('SPRUKOADMIN_C') == 'on') checked="" @endif>
                        <label for="sprukoadminc" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Dark Mode Switch For All Customer’s', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, the "Switch to Dark-Mode" option will disappear from the Customer’s profile page. And global "Dark-Mode" settings will not apply for customers.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="checkbox" id="darkmode" class=" toggle-class onoffswitch2-checkbox sprukoregister" @if(setting('DARK_MODE') == '1') checked="" @endif>
                        <label for="darkmode" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Dark-Mode Globally', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, the whole application will convert to "Dark-Mode" except for the users that are permitted with "Personal Settings."', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="REGISTER_POPUP" id="myonoffswitch1" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('REGISTER_POPUP') == 'yes') checked="" @endif>
                        <label for="myonoffswitch1" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Popup Register/Login', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, customers can access the registration form or login form in “popup modal” only.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="REGISTER_DISABLE" id="REGISTER_DISABLE" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="off" @if(setting('REGISTER_DISABLE') == 'off') checked="" @endif>
                        <label for="REGISTER_DISABLE" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Disable Register', 'setting')}}</label>
                    <a href="javascript:void(0);" class="ps-1 sprukologindisable"><i class="feather feather-edit-2"></i></a>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, "Register" options will disappear from the application’s header section, and new visitors wont be able to register to the application.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="login_disable" id="login_disable" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('login_disable') == 'on') checked="" @endif>
                        <label for="login_disable" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Login Disable', 'setting')}}</label><a href="javascript:void(0);" class="ps-1 sprukologindisable"><i class="feather feather-edit-2"></i></a>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, "Login" options will disappear from the application’s header section. Customers cannot login to their accounts.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="GOOGLEFONT_DISABLE" id="GOOGLEFONT_DISABLE" class=" toggle-class onoffswitch2-checkbox sprukoregister"  @if(setting('GOOGLEFONT_DISABLE') == 'on') checked="" @endif>
                        <label for="GOOGLEFONT_DISABLE" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Disable Google Font', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, "Google Font" will not apply to the whole application or site.', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox"  name="FORCE_SSL" id="FORCE_SSL" class=" toggle-class onoffswitch2-checkbox sprukoregister"  @if(setting('FORCE_SSL') == 'on') checked="" @endif>
                        <label for="FORCE_SSL" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Force SSL (https)', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, it will make your web application secure using "force SSL" when it is not secure, even if your domain is certified with an SSL certificate.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="KNOWLEDGE_ENABLE" id="myonoffswitch12" class=" toggle-class onoffswitch2-checkbox enablemenus" value="yes" @if(setting('KNOWLEDGE_ENABLE') == 'yes') checked="" @endif>
                        <label for="myonoffswitch12" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Knowledge', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, the "Knowledge" option will disappear from the application’s header section.', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="FAQ_ENABLE" id="faqs" class=" toggle-class onoffswitch2-checkbox enablemenus" value="yes" @if(setting('FAQ_ENABLE') == 'yes') checked="" @endif>
                        <label for="faqs" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Faq', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, the "faq" option will disappear from the application’s header section.', 'setting')}})</i></small>
                </div>
            </div>
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="PROFILE_USER_ENABLE" id="myonoffswitch123" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('PROFILE_USER_ENABLE') == 'yes') checked="" @endif>
                        <label for="myonoffswitch123" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Customer Profile Image Upload', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, the "Upload File" option will disappear from the customers profile page, and they wont be able to upload their profile picture.', 'setting')}})</i></small>
                </div>

            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="envato_on" id="envato_on" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('ENVATO_ON') == 'on') checked="" @endif>
                        <label for="envato_on" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Envato On', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this Envato switch, the entire "Envato" option will disappear from the application', 'setting')}})</i></small>
                </div>

            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="defaultlogin_on" id="defaultlogin_on" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('defaultlogin_on') == 'on') checked="" @endif>
                        <label for="defaultlogin_on" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Default Login', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, login page will appear on the main site URL by default. Users cannot access the homepage', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="article_count" id="article_count" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('article_count') == 'on') checked="" @endif>
                        <label for="article_count" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Article Count Enable', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this setting, article views count will disappear in the "Article" view page.', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="only_social_logins" id="only_social_logins" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('only_social_logins') == 'on') checked="" @endif>
                        <label for="only_social_logins" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Social Login’s Only', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, only social login form will appear to customer’s by when you click on the login button in header section. They cannot access normal login form', 'setting')}})</i></small>
                </div>
            </div>

            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="sidemenu_icon_style" id="sidemenu_icon_style" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('sidemenu_icon_style') == 'on') checked="" @endif>
                        <label for="sidemenu_icon_style" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Sidemenu Icon Style', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you "Enable" this setting, the whole application sidemenu will collapse into Icon Menu', 'setting')}})</i></small>
                </div>
            </div>

            <!--- Customer Profile Delete Enable --->
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">

                    <a class="onoffswitch2">
                        <input type="checkbox" name="cust_profile_delete_enable" id="cust_profile_delete_enable" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('cust_profile_delete_enable') == 'on') checked="" @endif>
                        <label for="cust_profile_delete_enable" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Customer Account Delete Permission', 'setting')}}</label>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you "Enable" this setting, customers can "Delete" their account permenently.', 'setting')}})</i></small>
                </div>
            </div>
            <!--- End Customer Profile Delete Enable --->

            <!-- Under Maintanance Mode -->
            <div class="switch_section">
                <div class="switch-toggle d-flex d-md-max-block mt-4">
                    <a class="onoffswitch2">
                        <input type="checkbox" name="maintanance_mode_enable" id="maintanance_mode_enable" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if (setting('MAINTENANCE_MODE') == 'on') checked @endif>
                        <label for="maintanance_mode_enable" class="toggle-class onoffswitch2-label" ></label>
                    </a>
                    <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Maintenance Mode', 'setting')}}</label>
                    <a href="{{url('/admin/maintenancepage')}}" class="ps-1"><i class="feather feather-edit-2"></i></a>
                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you "Enable" this setting, the application will go into maintenance mode. Only admin panel users can access the application.', 'setting')}})</i></small>
                </div>
            </div>
            <!-- End Under Maintanance Mode -->
        </div>
    </div>
</div>
<!-- End switches-->

<!-- Footer Copyright Text -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Footer Copyright Text', 'setting')}}</h4>
        </div>
        <form method="POST" action="{{url('admin/footer/' )}}" enctype="multipart/form-data">
            @csrf

            @honeypot
            <input type="hidden" name="id" value="1">

            <div class="card-body">
                <textarea class="summernote d-none @error('copyright') is-invalid @enderror" name="copyright" aria-multiline="true">{{$footertext->copyright}}</textarea>
                @error('copyright')

                    <span class="invalid-feedback" role="alert">
                        <strong>{{ lang($message) }}</strong>
                    </span>
                @enderror

            </div>

            <div class="card-footer">
                <div class="form-group float-end ">
                    <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Footer Copyright Text -->
</div>

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
        let themeColor = setColorPicker('#theme_color-input', document.querySelector('#theme_color-input').value);
        let themeColorDark = setColorPicker('#theme_color_dark-input', document.querySelector('#theme_color_dark-input').value);

        // Csrf Field
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // summernote js
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 1,
            height: 200,
        });

        // Multiple switch changes
        $('.sprukoregister').on('change', function() {
            var status = $('#myonoffswitch1').prop('checked') == true ? 'yes' : 'no';
            var registerdisable = $('#REGISTER_DISABLE').prop('checked') == true ? 'off' : 'on';
            var googledisable = $('#GOOGLEFONT_DISABLE').prop('checked') == true ? 'on' : 'off';
            var forcessl = $('#FORCE_SSL').prop('checked') == true ? 'on' : 'off';
            var darkmode = $('#darkmode').prop('checked') == true ? '1' : '0';
            var sprukoadminp = $('#sprukoadminp').prop('checked') == true ? 'on' : 'off';
            var sprukocustp = $('#sprukoadminc').prop('checked') == true ? 'on' : 'off';
            var envatoon = $('#envato_on').prop('checked') == true ? 'on' : 'off';
            var purchasecodeon = $('#purchasecode_on').prop('checked') == true ? 'on' : 'off';
            var defaultloginon = $('#defaultlogin_on').prop('checked') == true ? 'on' : 'off';
            var articlecount = $('#article_count').prop('checked') == true ? 'on' : 'off';
            var defaultsocialloginon = $('#only_social_logins').prop('checked') == true ? 'on' : 'off';
            var sidemenustyle = $('#sidemenu_icon_style').prop('checked') == true ? 'on' : 'off';
            var logindisable = $('#login_disable').prop('checked') == true ? 'on' : 'off';
            var custdeleteprofile = $('#cust_profile_delete_enable').prop('checked') == true ? 'on' : 'off';
            var maintanancemode = $('#maintanance_mode_enable').prop('checked') == true ? 'on' : 'off';

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{url('/admin/general/register')}}',
                data: {
                    'status': status,
                    'registerdisable' : registerdisable,
                    'googledisable' : googledisable,
                    'forcessl' : forcessl,
                    'darkmode' : darkmode,
                    'sprukoadminp' : sprukoadminp,
                    'sprukocustp' : sprukocustp,
                    'envatoon' : envatoon,
                    'purchasecodeon' : purchasecodeon,
                    'defaultloginon' : defaultloginon,
                    'articlecount' : articlecount,
                    'defaultsocialloginon' : defaultsocialloginon,
                    'sidemenustyle' : sidemenustyle,
                    'logindisable' : logindisable,
                    'custdeleteprofile' : custdeleteprofile,
                    'maintanancemode' : maintanancemode,
                },
                success: function(data){
                    toastr.success('{{lang('Updated successfully', 'alerts')}}')
                    window.location.reload();
                },
                error: function(data){
                    toastr.error('{{lang('Social logins are not enabled please enable it first', 'alerts')}}')
                    window.location.reload();
                }
            });
        });

        // Enable Menus
        $('.enablemenus').on('change', function() {
            var status = $('#myonoffswitch12').prop('checked') == true ? 'yes' : 'no';
            var status1 = $('#faqs').prop('checked') == true ? 'yes' : 'no';
            var status2 = $('#contact').prop('checked') == true ? 'yes' : 'no';
            var status3 = $('#enable_gpt').prop('checked') == true ? 'on' : 'off';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/knowledge')}}',
                data: {
                    'KNOWLEDGE_ENABLE': status,
                    'FAQ_ENABLE': status1,
                    'CONTACT_ENABLE': status2,
                    'enable_gpt': status3,
                },
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

        // user profile enable
        $('#myonoffswitch123').on('change', function() {
            var status1 = $('#myonoffswitch123').prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/profileuser')}}',
                data: {'PROFILE_USER_ENABLE': status1},
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

        // employye profile enable
        $('#myonoffswitch124').on('change', function() {
            var status2 = $('#myonoffswitch124').prop('checked') == true ? 'yes' : 'no';
            $.ajax({
                type: "post",
                dataType: "json",
                url: '{{url('/admin/profileagent')}}',
                data: {'PROFILE_AGENT_ENABLE': status2},
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

        // Logos Delete
        $('body').on('click', '.logosdelete', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let logo = $(this).val();
            swal({
                title: `{{lang('Are you sure want to remove this logo?')}}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: "{{route('admin.logodelete')}}",
                        data: {
                            'id': id,
                            'logo': logo

                        },
                        success: function (data) {
                        toastr.success(data.success);
                        location.reload();
                        },
                        error: function (data) {
                        console.log('Error:', data);
                        }
                    });
                }
            });
        });

        //Login_Disable Content modelPopup
        $('body').on('click', '.sprukologindisable', function(e){

            e.preventDefault();
            $('#name').html("");
            $('#sprukologin').val("Save");
            $('#logindisable_form').trigger("reset");
            $('.modal-title').html("{{lang('Login/Register Disable Statement')}}");
            $('#addlogindisable').modal('show');
            $('#name').val("{{setting('login_disable_statement')}}");
        })

        $('body').on('submit', '#logindisable_form', function(e){
            e.preventDefault();
            var actionType = $('#btnsave').val();
            var fewSeconds = 2;
            $('#btnsave').html('Loading...');
            $('#btnsave').prop('disabled', true);
                setTimeout(function(){
                    $('#btnsave').prop('disabled', false);
                    $('#btnsave').html('Save');
                }, fewSeconds*1000);
            var formData = new FormData(this);
            $.ajax({
            type:'POST',
            url: '{{url('admin/general/logindisable')}}',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,

            success: (data) => {
                $('#nameError').html('');
                $('#logindisable_form').trigger("reset");
                $('#addlogindisable').modal('hide');
                $('#btnsave').html('Save');
                toastr.success(data.success);
            },
            error: function(data){
                $('#nameError').html('');
                $('#nameError').html(data.responseJSON.errors.name);

                $('#btnsave').html('Save');
            }
        });
        })

    })();


</script>

@endsection

@section('modal')

    @include('admin.generalsetting.modalpopup.logindisablemodal')

@endsection
