
@extends('layouts.adminmaster')

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Customer')}}</span></h1>
	</div>
</div>
<!--End Page header-->

<!-- Customer Edit -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Edit Customer')}}</h4>
		</div>
		<form method="POST" action="{{url('/admin/customer/' . $user->id)}}" enctype="multipart/form-data">
			<div class="card-body" >
				@csrf

				@honeypot
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Nom')}}</label>
							<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname"  value="{{ $user->firstname, old('firstname') }}" >
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
							<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"  value="{{$user->lastname, old('lastname') }}" >
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
							<input type="text" class="form-control" name="name"  value="{{$user->username }}" readonly>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Email')}}</label>
							<input type="email @error('email') is-invalid @enderror" class="form-control" name="email" Value="{{$user->email, old('email')}}">
							@error('email')

								<span class="invalid-feedback" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Pays')}}</label>
                            <select name="country" class="form-control select2 select2-show-search">
                                @foreach($countries as $country)
                                    <option value="{{$country->name}}" {{$country->name == $user->country ? 'selected' : ''}}>{{$country->name}}</option>
                                @endforeach
                            </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Timezones')}}</label>
							<select name="timezone" class="form-control select2 select2-show-search">
                                @foreach($timezones  as $group => $timezoness)
                                    <option value="{{$timezoness->timezone}}" {{$timezoness->timezone == $user->timezone ? 'selected' : ''}}>{{$timezoness->timezone}} {{$timezoness->utc}}</option>
                                @endforeach
                            </select>
						</div>
					</div>
                    @if($customfield != null)
                        @foreach($customfield as $customfields)
                            @if($customfields->fieldtypes != 'textarea')
                                @if($customfields->privacymode == '1')
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{$customfields->fieldnames}}</label>
                                            <input type="email" class="form-control" Value="{{decrypt($customfields->values)}}" readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{$customfields->fieldnames}}</label>
                                            <input type="email" class="form-control" Value="{{$customfields->values}}" readonly>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label">{{lang('Status')}}</label>
							<select class="form-control  select2" data-placeholder="{{lang('Select Status')}}" name="status">
								<option label="{{lang('Select Status')}}"></option>
								@if ($user->status === '1')

								<option value="{{$user->status}}" @if ($user->status === '1') selected @endif>{{lang('Active')}}</option>
								<option value="0">{{lang('Inactive')}}</option>
								@else

								<option value="{{$user->status}}" @if ($user->status === '0') selected @endif>{{lang('Inactive')}}</option>
								<option value="1">{{lang('Active')}}</option>
								@endif

							</select>
						</div>
					</div>
					<div class="switch_section">
						<div class="switch-toggle d-flex mt-4">
							<a class="onoffswitch2">
								<input type="checkbox" data-id="{{$user->id}}" name="voilated" id="myonoffswitch181" class=" toggle-class onoffswitch2-checkbox sprukoswitch"  @if($user->voilated == 'on') checked="" @endif>
								<label for="myonoffswitch181" class="toggle-class onoffswitch2-label" data-id="{{$user->id}}"></label>
							</a>
							<label class="form-label ps-3"> {{lang('Voilated Customer')}} </label>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="form-group float-end">
					<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
				</div>
			</div>
		</form>
	</div>
</div>
<!-- End Customer Edit -->

@endsection

@section('scripts')

<!-- INTERNAL select2 js-->
<script src="{{gn_asset('assets/js/select2.js')}}"></script>
@endsection
