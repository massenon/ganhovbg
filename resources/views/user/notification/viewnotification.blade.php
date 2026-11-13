@extends('layouts.usermaster')


								@section('content')

								<!-- Section -->
								<section>
									<div class="bannerimg cover-image" data-bs-image-src="{{asset('assets/images/photos/banner1.jpg')}}">
										<div class="header-text mb-0">
											<div class="container ">
												<div class="row text-white">
													<div class="col">
														<h3 class="mb-0">{{lang('Editer Profil')}}</h3>
													</div>
													<div class="col col-auto">
														<ol class="breadcrumb text-center">
															<li class="breadcrumb-item">
																<a href="#" class="text-white-50">{{lang('Accueil')}}</a>
															</li>
															<li class="breadcrumb-item active">
																<a href="#" class="text-white">{{lang('Editer Profil')}}</a>
															</li>
														</ol>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
								<!-- Section -->

								<!--Section-->
								<section>
									<div class="cover-image sptb">
										<div class="container ">
											<div class="row">
												@include('includes.user.verticalmenu')

												<div class="col-xl-9">
													<div class="card">
														<div class="card-header d-block border-0">
															<h4 class="card-title">

                                                                {{$notifications->data['mailsubject']}}
																<span class="badge badge-success badge-notify br-13 ms-2 mt-0" style="background-color: {{$notifications->data['mailsendtagcolor']}}">{{$notifications->data['mailsendtag']}}</span>
                                                            </h4>
															<div class="mt-2">
																<span class="badge badge-light">{{$notifications->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('date_format'))}}</span>
																<span class="badge badge-light">{{$notifications->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}</span>
															</div>
														</div>
														<div class="card-body pt-1">

                                                            {{$notifications->data['mailtext']}}
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
								</section>
								<!--Section-->

								@endsection

		@section('scripts')



		@endsection