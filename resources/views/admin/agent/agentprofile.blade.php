
@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Tag css -->
<link href="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.css')}}" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection

@section('content')


<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Employees')}}</span></h1>
    </div>
</div>
<!--End Page header-->

<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Edit Employee')}}</h4>
        </div>
        <form method="POST" action="{{url('/admin/agentprofile/' . $user->id)}}" enctype="multipart/form-data">
            <div class="card-body" >
                @csrf

                @honeypot
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname"  value="{{ $user->firstname }}" >
                            @error('firstname')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Prénom')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"  value="{{$user->lastname }}" >
                            @error('lastname')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Pseudo')}}</label>
                            <input type="text" class="form-control" name="name"  value="{{$user->name }}" disabled >
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Employee ID')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('empid') is-invalid @enderror" placeholder="{{lang('EMPID-001')}}" name="empid"  value="{{old('empid', $user->empid)}}">
                            @error('empid')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Sélectionner un rôle')}} <span class="text-red">*</span></label>


                            @if(Auth::check() && Auth::user()->id == '1')
                                @if(Auth::user()->id != $user->id )

                                <select class="form-control select2-show-search  select2 @error('role') is-invalid @enderror" data-placeholder="{{lang('Sélectionner un rôle')}}" name="role" id="roleID" >
                                    @if(!empty($user->getRoleNames()[0]))

                                    <option label="{{lang('Sélectionner un rôle')}}"></option>
                                    @foreach($roles as $role)

                                    <option  value="{{$role->name}}" {{ old('role', $user->getRoleNames()[0])==$role->name ? 'selected' :'' }}> {{$role->name}}</option>

                                    @endforeach

                                    @else

                                    <option label="{{lang('Sélectionner un rôle')}}"></option>
                                    @foreach($roles as $role)


                                    <option  value="{{$role->name}}" {{ old('role')==$role->name ? 'selected' :'' }}> {{$role->name}}</option>

                                    @endforeach
                                    @endif

                                </select>
                                @else
                                    @if(!empty($user->getRoleNames()[0]))

                                    <input type="text" class="form-control" name="role" value="{{$user->getRoleNames()[0]}}" readonly>

                                    @else

                                    <input type="text" class="form-control" name="role" value="superadmin" readonly>

                                    @endif
                                @endif
                            @else
                                @if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

                                <select class="form-control select2-show-search  select2 @error('role') is-invalid @enderror" data-placeholder="{{lang('Sélectionner un rôle')}}" name="role" id="roleID" >
                                    @if(!empty($user->getRoleNames()[0]))

                                    <option label="{{lang('Sélectionner un rôle')}}"></option>
                                    @foreach($roles as $role)

                                    <option  value="{{$role->name}}" {{ old('role', $user->getRoleNames()[0])==$role->name ? 'selected' :'' }}> {{$role->name}}</option>

                                    @endforeach

                                    @else

                                    <option label="{{lang('Sélectionner un rôle')}}"></option>
                                    @foreach($roles as $role)


                                    <option  value="{{$role->name}}" {{ old('role')==$role->name ? 'selected' :'' }}> {{$role->name}}</option>

                                    @endforeach
                                    @endif

                                </select>
                                @else
                                    @if(!empty($user->getRoleNames()[0]))

                                    <input type="text" class="form-control" name="role" value="{{$user->getRoleNames()[0]}}" readonly>

                                    @else

                                    <input type="text" class="form-control" name="role" value="superadmin" readonly>

                                    @endif
                                @endif

                            @endif

                            @error('role')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Département')}} </label>
                            <select class="form-control select2-show-search  select2 @error('department') is-invalid @enderror" data-placeholder="{{lang('Select Department')}}" name="department"  >
                                <option label="{{lang('Select Department')}}"></option>
                                @foreach($departments as $department)

                                    <option  value="{{$department->departmentname}}" {{ $user->departments == $department->departmentname ? "selected" : "" }}> {{$department->departmentname}}</option>

                                @endforeach

                            </select>
                            @error('department')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Email')}} <span class="text-red">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" Value="{{$user->email}}">
                            @error('email')

                            <span class="invalid-feedback" role="alert">
                                <strong>{{ lang($message) }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Numéro de téléphone')}}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"  value="{{old('phone', $user->phone)}}" >
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
                            <input type="text" class="form-control @error('languages') is-invalid @enderror" value="{{$user->languagues}}" name="languages" data-role="tagsinput" />
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
                            <input type="text" class="form-control @error('skills') is-invalid @enderror" value="{{$user->skills}}" name="skills" data-role="tagsinput" />
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
							<select name="country" class="form-control select2 select2-show-search" id="">
								@foreach($countries as $country)
								<option value="{{$country->name}}" {{$country->name == $user->country ? 'selected' : ''}}>{{$country->name}}</option>
								@endforeach
							</select>


						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Timezone')}}</label>
							<select name="timezone" class="form-control select2 select2-show-search" id="">
								@foreach($timezones  as $group => $timezoness)
									<option value="{{$timezoness->timezone}}" {{$timezoness->timezone == $user->timezone ? 'selected' : ''}}>{{$timezoness->timezone}} {{$timezoness->utc}}</option>

								@endforeach
							</select>
						</div>
					</div>
                    <div class="col-md-6 col-sm-6">
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
                        @if ($user->image != null)
                            <div class="file-image-1 removesprukoi{{$user->id}}">
                                <div class="product-image custom-ul">
                                    <a href="#">
                                        <img src="{{asset('public/uploads/profile/' .$user->image)}}" class="br-5" alt="{{$user->image}}">
                                    </a>
                                    <ul class="icons">
                                        <li><a href="javascript:(0);" class="bg-danger delete-image" data-id="{{$user->id}}"><i class="fe fe-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
					</div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Status')}}</label>
                            @if(Auth::check() && Auth::user()->id == '1')
                                @if(Auth::user()->id != $user->id )

                                <select class="form-control  select2" data-placeholder="{{lang('Select Status')}}" name="status">
                                    <option label="{{lang('Select Status')}}"></option>
                                    <option value="1" @if ($user->status === '1') selected @endif>{{lang('Active')}}</option>
                                    <option value="0" @if ($user->status === '0') selected @endif>{{lang('Inactive')}}</option>
                                </select>
                                @else
                                    @if(!empty($user->getRoleNames()[0]))
                                    @if ($user->status === '1')

                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Active')}}" readonly>
                                    @endif
                                    @if ($user->status === '0')
                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Inactive')}}<" readonly>
                                    @endif


                                    @else

                                    @if ($user->status === '1')

                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Active')}}" readonly>
                                    @endif
                                    @if ($user->status === '0')
                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Inactive')}}<" readonly>
                                    @endif

                                    @endif
                                @endif
                            @else
                                @if(Auth::user()->id != $user->id  && !empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] != 'superadmin')

                                <select class="form-control  select2" data-placeholder="{{lang('Select Status')}}" name="status">
                                    <option label="{{lang('Select Status')}}"></option>
                                    <option value="1" @if ($user->status === '1') selected @endif>{{lang('Active')}}</option>
                                    <option value="0" @if ($user->status === '0') selected @endif>{{lang('Inactive')}}</option>
                                </select>
                                @else
                                    @if(!empty($user->getRoleNames()[0]))

                                    @if ($user->status === '1')

                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Active')}}" readonly>
                                    @endif
                                    @if ($user->status === '0')
                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Inactive')}}<" readonly>
                                    @endif

                                    @else

                                    @if ($user->status === '1')

                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Active')}}" readonly>
                                    @endif
                                    @if ($user->status === '0')
                                    <input type="hidden" class="form-control" name="status" value="{{$user->status}}" >
                                    <input type="text" class="form-control"  value="{{lang('Inactive')}}<" readonly>
                                    @endif

                                    @endif
                                @endif

                            @endif


                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{lang('Select Dashboard')}}</label>
                            <div class="custom-controls-stacked d-md-flex" id="text">
                                <label class="custom-control form-radio success me-4">
                                    <input id="empDashboard" type="radio" class="custom-control-input" name="dashboard" value="Employee" autocomplete="off" @if($user->dashboard != null) @if($user->dashboard == 'Employee') checked  @endif  @endif>
                                    <span class="custom-control-label">{{lang('Tableau de Bord du Prestataire')}}</span>
                                </label>

                                <label class="custom-control form-radio success me-4">
                                    <input id="AdmDashboard" type="radio" class="custom-control-input" name="dashboard" value="Admin"  autocomplete="off" @if($user->dashboard != null) @if($user->dashboard == 'Admin') checked @endif  @endif>
                                    <span class="custom-control-label">{{lang('Admin Dashboard')}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group float-end">
                    <input type="submit" class="btn btn-secondary" value="{{lang('Update Profile')}}" onclick="this.disabled=true;this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')

<!--File BROWSER -->
<script src="{{gn_asset('assets/js/form-browser.js')}}"></script>

<!-- INTERNAL Vertical-scroll js-->
<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

<!-- INTERNAL Index js-->
<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>
<script src="{{gn_asset('assets/js/select2.js')}}"></script>

<!-- INTERNAL TAG js-->
<script src="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script type="text/javascript">
    var SITEURL = '{{url('')}}';
    (function($) {
    "use strict";

    // Csrf Field
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $('#roleID').on('change',function(e) {
    var cat_id = e.target.value;
    let empdasType = document.querySelector('#empDashboard');
    let admindasType = document.querySelector('#AdmDashboard');
    if(cat_id == 'superadmin'){
    admindasType.checked = true
    }
    else{
    empdasType.checked = true
    }
    });

    })(jQuery);

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

</script>

@endsection
