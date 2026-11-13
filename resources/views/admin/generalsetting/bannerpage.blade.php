
@extends('layouts.adminmaster')

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Banner', 'menu')}} </span></h1>
								</div>
							</div>
							<!--End Page header-->
							
							<!--Banner Section-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Banner Section')}}</h4>
									</div>
									<form method="POST" action="{{url('/admin/bannerstore')}}" enctype="multipart/form-data">
										<div class="card-body" >
											@csrf

											@honeypot
											<input type="hidden" class="form-control" name="id" Value="{{$basic->id}}">
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('searchtitle') is-invalid @enderror" name="searchtitle" Value="{{$basic->searchtitle}}">
														@error('searchtitle')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-12 col-md-12">
													<div class="form-group">
														<label class="form-label">{{lang('Subtitle')}}</label>
														<input type="text" class="form-control @error('searchsub') is-invalid @enderror" name="searchsub" Value="{{$basic->searchsub}}" >
														@error('searchsub')

															<span class="invalid-feedback" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

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
							<!--EndBanner Section-->
							@endsection
