
@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAl Tag css -->
		<link href="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.css')}}" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Customer')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

							<!-- Create Customers -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Créer une victime')}}</h4>
									</div>
									<form method="POST" action="{{url('/admin/customer/create')}}" enctype="multipart/form-data">
										<div class="card-body" >
											@csrf

											@honeypot
											<div class="row">
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname"  value="{{old('firstname')}}" >
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
														<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"  value="{{old('lastname')}}" >
														@error('lastname')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Email')}} <span class="text-red">*</span></label>
														<input type="email" class="form-control  @error('email') is-invalid @enderror" name="email"  value="{{old('email')}}" >
														@error('email')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Mot de passe')}} <small class="text-muted"><i>({{lang('Please copy the Password')}})</i></small></label>
														<input class="form-control @error('password') is-invalid @enderror" type="text"  name="password" value="{{str_random(10)}}"  readonly>
														@error('password')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror
													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Numéro de téléphone')}}</label>
														<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"  value="{{old('phone')}}" >
														@error('phone')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12 card-footer">
											<div class="form-group float-end">
												<input type="submit" class="btn btn-secondary" value="{{lang('Créer une victime')}}" onclick="this.disabled=true;this.form.submit();">
											</div>
										</div>
									</form>

								</div>
							</div>
							<!-- Create Customers -->
							@endsection

		@section('scripts')

		<!--File BROWSER -->
		<script src="{{gn_asset('assets/js/form-browser.js')}}"></script>

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>
		<script src="{{gn_asset('assets/js/select2.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>

		<!-- INTERNAL TAG js-->
		<script src="{{gn_asset('assets/plugins/taginput/bootstrap-tagsinput.js')}}"></script>

		@endsection
