@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

<!-- INTERNAl Tag css -->
<link href="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.css')}}" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Profile', 'menu')}}</span></h1>
    </div>
</div>
<!--End Page header-->

<!-- Profile Page-->
<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-12">
        <div class="card user-pro-list overflow-hidden">
            <div class="card-body">
                <div class="user-pic text-center">
                    @if (Auth::user()->image == null)

                    <span class="avatar avatar-xxl brround" style="background-image: url(../uploads/profile/user-profile.png)">
                        <span class="avatar-status bg-green"></span>
                    </span>
                        @else

                    <span class="avatar avatar-xxl brround" style="background-image: url(../uploads/profile/{{Auth::user()->image}})">
                        <span class="avatar-status bg-green"></span>
                    </span>
                        @endif
                    <div class="pro-user mt-3">
                        <h5 class="pro-user-username text-dark mb-1 fs-16">{{Auth::user()->name}}</h5>
                        <h6 class="pro-user-desc text-muted fs-12">{{Auth::user()->email}}</h6>
                        @if(!empty(Auth::user()->getRoleNames()[0]))
                        <h6 class="pro-user-desc text-muted fs-12">{{ Auth::user()->getRoleNames()[0]}}</h6>
                        @endif
                        <div class="profilerating" data-rating="{{$avg}}"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title"> {{lang('Personal Details')}}</h4>
            </div>
            <div class="card-body px-0 pb-0">

                <div class="table-responsive tr-lastchild">
                    <table class="table mb-0 table-information">
                        <tbody>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Employee ID')}}</span>
                                </td>
                                <td class="py-2 ps-4">{{Auth::user()->empid}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Nom')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{Auth::user()->name}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Role')}} </span>
                                </td>
                                <td class="py-2 ps-4">
                                    @if(!empty(Auth::user()->getRoleNames()[0]))

                                        {{Auth::user()->getRoleNames()[0]}}
                                        @endif

                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Email')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{Auth::user()->email}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Phone')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{Auth::user()->phone}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Languages')}} </span>
                                </td>
                                <td class="py-2 ps-4">
                                    @php
                                    $values = explode(",", Auth::user()->languagues);

                                    @endphp

                                    <ul class="custom-ul">
                                        @foreach ($values as $value)

                                        <li class="tag mb-1">{{ucfirst($value)}}</li>

                                        @endforeach

                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50">{{lang('Skills')}} </span>
                                </td>
                                <td class="py-2 ps-4">
                                    @php
                                    $values = explode(",", Auth::user()->skills);
                                    @endphp

                                    <ul class="custom-ul">
                                        @foreach ($values as $value)

                                        <li class="tag mb-1">{{ucfirst($value)}}</li>

                                        @endforeach

                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Location')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{Auth::user()->country}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(setting('SPRUKOADMIN_P') == 'on')

        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title"> {{lang('Personal Setting')}}</h4>
            </div>
            <div class="card-body">
                <div class="switch_section">
                    <div class="switch-toggle d-flex mt-4">
                        <a class="onoffswitch2">
                            <input type="checkbox" data-id="{{Auth::id()}}" name="checkbox" id="myonoffswitch181" class=" toggle-class onoffswitch2-checkbox sprukoswitch"  @if(Auth::check() && Auth::user()->darkmode == 1) checked="" @endif>
                            <label for="myonoffswitch181" class="toggle-class onoffswitch2-label" data-id="{{Auth::id()}}"></label>
                        </a>
                        <label class="form-label ps-3"> {{lang('Switch to Dark-Mode')}} </label>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Setting -->
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title"> {{lang('Setting')}}</h4>
            </div>
            <div class="card-body">
                <div class="switch_section">
                    <div class="switch-toggle d-flex mt-4">
                        <a class="onoffswitch2">
                            <input type="checkbox" data-id="{{Auth::id()}}" name="emailnotificationon" id="emailnotificationon" class=" toggle-class onoffswitch2-checkbox"  @if(Auth::check() && Auth::user()->usetting != null) @if(Auth::check() && Auth::user()->usetting->emailnotifyon == 1) checked="" @endif @endif>
                            <label for="emailnotificationon" class="toggle-class onoffswitch2-label" data-id="{{Auth::id()}}"></label>
                        </a>
                        <label class="form-label ps-3"> {{lang('Email Notification On/Off')}} </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Setting --->

    </div>
    <div class="col-xl-9 col-lg-8 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title"> {{lang('Details Profil')}}</h4>
            </div>
            <div class="card-body">
                @if(Auth::user()->can('Profile Edit'))
                    <form method="POST" action="{{url('/admin/profile')}}" enctype="multipart/form-data">
                        @csrf
                        @honeypot

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Nom')}}</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{Auth::user()->firstname}}">
                                    @error('firstname')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Prénom')}}</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{Auth::user()->lastname }}">
                                    @error('lastname')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Email')}}</label>
                                    <input type="email" class="form-control" Value="{{Auth::user()->email}}" disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{lang('Employee ID')}}</label>
                                    <input type="email" class="form-control" Value="{{Auth::user()->empid}}" disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Numéro de téléphone')}}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"  value="{{old('phone',Auth::user()->phone)}}" >
                                    @error('phone')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Languages')}}</label>
                                    <input type="text" class="form-control @error('languages') is-invalid @enderror sprukotags" value="{{old('languages', Auth::user()->languagues)}}" name="languages[]" data-role="tagsinput" />
                                    @error('languages')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Skills')}}</label>
                                    <input type="text" class="form-control @error('skills') is-invalid @enderror sprukotags" value="{{old('skills', Auth::user()->skills)}}" name="skills[]" data-role="tagsinput" />
                                    @error('skills')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Pays')}}</label>
                                    <select name="country" class="form-control select2 select2-show-search">
                                        @foreach($countries as $country)
                                        <option value="{{$country->name}}" {{$country->name == Auth::user()->country ? 'selected' : ''}}>{{lang($country->name)}}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Timezone')}}</label>
                                    <select name="timezone" class="form-control select2 select2-show-search">
                                        @foreach($timezones  as $group => $timezoness)
                                            <option value="{{$timezoness->timezone}}" {{$timezoness->timezone == Auth::user()->timezone ? 'selected' : ''}}>{{lang($timezoness->timezone)}} {{lang($timezoness->utc)}}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Upload Image')}}</label>
                                    <div class="input-group file-browser">
                                        <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" accept="image/png, image/jpeg,image/jpg" >

                                        @error('image')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                    <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                </div>
                                @if (Auth::user()->image != null)
                                    <div class="file-image-1 removesprukoi{{Auth::user()->id}}">
                                        <div class="product-image custom-ul">
                                            <a href="#">
                                                <img src="{{asset('public/uploads/profile/' .Auth::user()->image)}}" class="br-5" alt="{{Auth::user()->image}}">
                                            </a>
                                            <ul class="icons">
                                                <li><a href="javascript:(0);" class="bg-danger delete-image" data-id="{{Auth::user()->id}}"><i class="fe fe-trash"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12 card-footer">
                                <div class="form-group float-end mb-0">
                                    <input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    @csrf
                    @honeypot

                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Nom')}}</label>
                                <input type="text" class="form-control"
                                    name="firstname" value="{{Auth::user()->firstname}}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Prénom')}}</label>
                                <input type="text" class="form-control"
                                    name="lastname" value="{{Auth::user()->lastname }}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Email')}}</label>
                                <input type="email" class="form-control" Value="{{Auth::user()->email}}" disabled>

                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Employee ID')}}</label>
                                <input type="email" class="form-control" Value="{{Auth::user()->empid}}" disabled>

                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Numéro de téléphone')}}</label>
                                <input type="text" class="form-control " name="phone"
                                    value="{{old('phone',Auth::user()->phone)}}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Languages')}}</label>
                                <input type="text" class="form-control"
                                    value="{{Auth::user()->languagues}}" name="languages" data-role="tagsinput" disabled />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Skills')}}</label>
                                <input type="text" class="form-control"
                                    value="{{Auth::user()->skills}}" name="skills" data-role="tagsinput" disabled />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Pays')}}</label>
                                <input type="text" class="form-control" value="{{Auth::user()->country}}" disabled>

                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label"> {{lang('Timezone')}}</label>
                                <input type="text" class="form-control" value="{{Auth::user()->timezone}}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{lang('Upload Image')}}</label>
                                <div class="input-group file-browser">
                                    <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" accept="image/png, image/jpeg,image/jpg" disabled>

                                    @error('image')

                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                    @enderror

                                </div>
                                <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                            </div>
                            @if (Auth::user()->image != null)
                                <div class="file-image-1 removesprukoi{{Auth::user()->id}}">
                                    <div class="product-image custom-ul">
                                        <a href="#">
                                            <img src="{{asset('public/uploads/profile/' .Auth::user()->image)}}" class="br-5" alt="{{Auth::user()->image}}">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @include('admin.auth.passwords.changepassword')

    </div>
</div>
<!--End Profile Page-->
@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<!-- INTERNAL TAG js-->
<script src="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.js')}}"></script>

<script src="{{gn_asset('assets/js/select2.js')}}"></script>

<script type="text/javascript">

    "use strict";

    (function($)  {

        // Variables
        var SITEURL = '{{url('')}}';

        // Csrf Field
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Profile Rating
        $(".profilerating").starRating({
            readOnly: true,
            // totalStars: 5,
            starSize: 20,
            emptyColor  :  '#ffffff',
            activeColor :  '#F2B827',
            strokeColor :  '#F2B827',
            strokeWidth :  15,
            useGradient : false

        });

        // DarkMode switch js
        $('.sprukoswitch').on('change', function() {
            var dark = $('#myonoffswitch181').prop('checked') == true ? '1' : '';
            var user_id = $(this).data('id');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{url('/admin/usersettings')}}',
                data: {
                    'dark': dark,
                    'user_id': user_id
                },
                success: function(data){
                    location.reload();
                    toastr.success('{{lang('Updated Successfully!', 'alerts')}}');
                }
            });
        });

        @if (Auth::user()->image != null)

        //Delete Image
        $('body').on('click', '.delete-image', function () {
            var _id = $(this).data("id");

            swal({
                title: `{{lang('Are you sure you want to remove the profile image?', 'alerts')}}`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: SITEURL + "/admin/image/remove/"+_id,
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
        @endif

        $('body').on('change', '#emailnotificationon', function(e){
            e.preventDefault();

            let emailvalue = $(this).prop('checked') == true ? '1' : '0' ,
                userid = $(this).data('id');

                $.ajax({
                type: "POST",
                dataType: "json",
                url: '{{url('/admin/emailonoff')}}',
                data: {
                    'emailvalue': emailvalue,
                    'userid' : userid,
                },
                success: function(data){
                    toastr.success('{{lang('Updated Successfully!', 'alerts')}}')
                    // window.location.reload();
                }
            });

        })

    })(jQuery);

</script>

@endsection
