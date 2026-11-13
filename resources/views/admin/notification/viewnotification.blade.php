@extends('layouts.adminmaster')


								@section('content')

								<!--Page header-->
								<div class="page-header d-xl-flex d-block">
									<div class="page-leftheader">
										<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Notification View')}}</span></h1>
									</div>
								</div>
								<!--End Page header-->

								<div class="col-xl-12 col-lg-12 col-md-12">
									<div class="card">
										<div class="card-header d-block border-0">
											<h4 class="card-title">
													
												{{$notifications->data['mailsubject']}}
												<span class="badge badge-success badge-notify br-13 ms-2 mt-0" style="background-color: {{$notifications->data['mailsendtagcolor']}}">{{$notifications->data['mailsendtag']}}</span>
											</h4>
											<div class="mt-2">
												<span class="badge badge-light">{{$notifications->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('date_format'))}}</span>
												<span class="badge badge-light">{{$notifications->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('time_format'))}}</span>
											</div>
										</div>
										<div class="card-body pt-1">

											{{$notifications->data['mailtext']}}
										</div>
									</div>
									
								</div>

								@endsection

		@section('scripts')


	
		@endsection