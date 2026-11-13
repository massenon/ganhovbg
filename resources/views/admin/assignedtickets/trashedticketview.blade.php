@extends('layouts.adminmaster')

        @section('styles')

        <!-- galleryopen CSS -->
		<link href="{{gn_asset('assets/plugins/simplelightbox/simplelightbox.css')}}" rel="stylesheet">

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

        @endsection


                            @section('content')
							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Arlete VBG Information')}}</span></h1>
								</div>
                                <div class="page-rightheader ms-md-auto">
									<a href="javascript:void(0)" data-id="{{$tickettrashedview->id}}" class="btn btn-sm btn-danger" id="show-delete">
										<span>Supprimer Cas VBG </span>
										<i class="feather feather-trash-2 text-white" data-id="{{$tickettrashedview->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
									</a>
                                    <a href="javascript:void(0)" data-id="{{$tickettrashedview->id}}" class="btn btn-sm btn-info" id="show-restore" >
                                        <span> Restaurer Cas VBG </span>
                                        <i class="feather feather-rotate-ccw text-white" data-id="{{$tickettrashedview->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Restore')}}"></i>
                                    </a>
								</div>
							</div>
							<!--End Page header-->


                            <!--Row-->
							<div class="row">
								<div class="col-xl-12 col-md-12 col-lg-12">
									<div class="row">
										<div class="col-xl-9 col-lg-12 col-md-12">
											@if($tickettrashedview->purchasecode != null)

											<!-- Purchase Code Details -->
											<div class="purchasecodes alert alert-light-warning br-13 ">
												<div class="ps-0 pe-0 pb-0">
													<div class="">
														<strong>{{lang('Puchase Code')}} :</strong>
														@if(!empty(Auth::user()->getRoleNames()[0]) && Auth::user()->getRoleNames()[0] == 'superadmin')

														<span class="">{{decrypt($tickettrashedview->purchasecode)}}</span>
														@else
														@if(setting('purchasecode_on') == 'on')

														<span class="">{{decrypt($tickettrashedview->purchasecode)}}</span>
														@else

														<span class="">{{ Str::padLeft(Str::substr(decrypt($tickettrashedview->purchasecode), -4), Str::length(decrypt($tickettrashedview->purchasecode)), Str::padLeft('*', 1)) }}</span>
														@endif
														@endif
														<button class="btn btn-sm btn-dark leading-tight ms-2" id="purchasecodebtn" data-id="{{ $tickettrashedview->purchasecode }}">Voir les détails</button>
														@if($tickettrashedview->purchasecodesupport == 'Supported')

														<span class="badge badge-success ms-2">{{lang('Assistance active')}}</span>
														@elseif($tickettrashedview->purchasecodesupport == 'Expired')

														<span class="badge badge-danger ms-2">{{lang('Assistance expirée')}}</span>
														@else
														@endif

													</div>
												</div>
											</div>
											<!-- End Purchase Code Details -->
											@endif

											<div class="card">
												<div class="card-header border-0 mb-1 d-block">
													<div class="d-sm-flex d-block">
														<div>
															<h4 class="card-title mb-1 fs-22">{{ $tickettrashedview->subject }} </h4>
														</div>
														<div class="card-options float-sm-end ticket-status">
															@if($tickettrashedview->status == "New")

															<span class="badge badge-burnt-orange">{{ gn_statut($tickettrashedview->status) }}</span>
															@elseif($tickettrashedview->status == "Re-Open")

															<span class="badge badge-teal">{{ gn_statut($tickettrashedview->status) }}</span>
															@elseif($tickettrashedview->status == "Inprogress")

															<span class="badge badge-info">{{ gn_statut($tickettrashedview->status) }}</span>
															@elseif($tickettrashedview->status == "On-Hold")

															<span class="badge badge-warning">{{ gn_statut($tickettrashedview->status) }}</span>
															@else

															<span class="badge badge-danger">{{ gn_statut($tickettrashedview->status) }}</span>
															@endif

														</div>
													</div>
													<small class="fs-13"><i class="feather feather-clock text-muted me-1"></i>{{lang('Created Date')}} <span class="text-muted">{{$tickettrashedview->created_at->diffForHumans()}}</span></small>
												</div>
												<div class="card-body pt-2 readmores px-6 mx-1">
													<div>
														<span>{!! $tickettrashedview->message !!}</span>

														<div class="row galleryopen">
															@foreach ($tickettrashedview->getMedia('ticket') as $tickettrashedviewss)

															<div class="file-image-1 removespruko{{$tickettrashedviewss->id}}" id="imageremove{{$tickettrashedviewss->id}}">
																<div class="product-image">
																	<a href="{{$tickettrashedviewss->getFullUrl()}}" class="imageopen">
																		<img src="{{$tickettrashedviewss->getFullUrl()}}" class="br-5" alt="{{$tickettrashedviewss->file_name}}">
																	</a>

																</div>
															</div>
															@endforeach

														</div>
													</div>
												</div>

											</div>
                                            @php

                                            $comments = $tickettrashedview->comments()->onlyTrashed()->get();

                                            @endphp
                                            @if($comments->isNotEmpty())
                                            <div class="card">
												<div class="card-header">
													<h4 class="card-title">{{lang('conversations')}}</h4>
                                                </div>

                                                @foreach ($comments as $comment)
													@if($comment->user_id != null)
														{{--Admin Reply status--}}
														@if ($loop->first)

															<div class="card-body">
																<div class="d-sm-flex">
																	<div class="d-flex me-3">
																		<a href="#">
																			@if ($comment->user != null)
																			@if ($comment->user->image == null)

																			<img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
																			@else

																			<img class="media-object brround avatar-lg" alt="{{$comment->user->image}}" src="{{asset('uploads/profile/'. $comment->user->image)}}">
																			@endif
																			@else

																			<img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
																			@endif

																		</a>
																	</div>
																	<div class="media-body">
																		@if($comment->user != null)

																		<h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->user->name }}@if(!empty($comment->user->getRoleNames()[0]))<span class="badge badge-primary-light badge-md ms-2">{{ $comment->user->getRoleNames()[0] }}</span>@endif</h5>
																		@else

																		<h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>
																		@endif

																		<small class="text-muted"><i class="feather feather-clock"></i> {{ $comment->created_at->diffForHumans() }}</small>
																		<div class="fs-13 mb-0 mt-1">
																			{!! $comment->comment !!}
																		</div>
																		<div class="editsupportnote-icon animated" id="supportnote-icon-{{$comment->id}}">
																			<form action="{{url('admin/ticket/editcomment/'.$comment->id)}}" method="POST">
																				@csrf

																				<textarea class="editsummernote" name="editcomment"> {{$comment->comment}}</textarea>
																			<div class="btn-list mt-1">
																				<input type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.form.submit();" value="Update">
																			</div>
																			</form>
																		</div>

																		@if (Auth::id() == $comment->user_id)

																			<div class="row galleryopen">
																				@foreach ($comment->getMedia('comments') as $commentss)

																				<div class="file-image-1  removespruko{{$commentss->id}}" id="imageremove{{$commentss->id}}">
																					<div class="product-image  ">
																						<a href="{{$commentss->getFullUrl()}}" class="imageopen">
																							<img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">
																						</a>
																					</div>
																					<span class="file-name-1">
																						{{Str::limit($commentss->file_name, 10, $end='.......')}}
																					</span>
																				</div>
																				@endforeach

																			</div>
																		@else

																			<div class="row galleryopen">
																				@foreach ($comment->getMedia('comments') as $commentss)

																				<div class="file-image-1  removespruko{{$commentss->id}}" id="imageremove{{$commentss->id}}">
																					<div class="product-image  ">
																						<a href="{{$commentss->getFullUrl()}}" class="imageopen">
																							<img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">
																						</a>
																					</div>
																					<span class="file-name-1">
																						{{Str::limit($commentss->file_name, 10, $end='.......')}}
																					</span>
																				</div>
																				@endforeach

																			</div>
																		@endif

																	</div>


																</div>
															</div>
														@else

															<div class="card-body">
																<div class="d-sm-flex">
																	<div class="d-flex me-3">
																		<a href="#">
																			@if($comment->user != null)
																			@if ($comment->user->image == null)

																			<img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
																			@else

																			<img class="media-object brround avatar-lg" alt="{{$comment->user->image}}" src="{{asset('uploads/profile/'. $comment->user->image)}}">
																			@endif
																			@else

																			<img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
																			@endif

																		</a>
																	</div>
																	<div class="media-body">
																		@if($comment->user != null)

																		<h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->user->name }}@if(!empty($comment->user->getRoleNames()[0]))<span class="badge badge-primary-light badge-md ms-2">{{ $comment->user->getRoleNames()[0] }}</span>@endif</h5>
																		@else

																		<h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>
																		@endif

																		<small class="text-muted"><i class="feather feather-clock"></i> {{ $comment->created_at->diffForHumans() }}</small>
																		<div class="fs-13 mb-0 mt-1">
																			{!! $comment->comment !!}
																		</div>
																		<div class="row galleryopen">
																			@foreach ($comment->getMedia('comments') as $commentss)

																				<div class="file-image-1  removespruko{{$commentss->id}}" id="imageremove{{$commentss->id}}">
																					<div class="product-image  ">
																						<a href="{{$commentss->getFullUrl()}}" class="imageopen">
																							<img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">
																						</a>
																					</div>
																					<span class="file-name-1">
																						{{Str::limit($commentss->file_name, 10, $end='.......')}}
																					</span>
																				</div>
																			@endforeach

																		</div>
																	</div>
																</div>
															</div>
														@endif
														{{--Admin Reply status end--}}

													@else
														{{--Customer Reply status--}}

														<div class="card-body">
															<div class="d-sm-flex">
																<div class="d-flex me-3">
																	<a href="#">
																		@if ($comment->cust->image == null)

																		<img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
																		@else

																		<img class="media-object brround avatar-lg" alt="{{$comment->cust->image}}" src="{{asset('uploads/profile/'. $comment->cust->image)}}">
																		@endif

																	</a>
																</div>
																<div class="media-body">
																	<h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->cust->username }}<span class="badge badge-danger-light badge-md ms-2">{{ $comment->cust->userType }}</span></h5>
																	<small class="text-muted"><i class="feather feather-clock"></i> {{ $comment->created_at->diffForHumans() }}</small>
																	<div class="fs-13 mb-0 mt-1">
																		{!! $comment->comment !!}

																	</div>
																	<div class="row galleryopen">
																		@foreach ($comment->getMedia('comments') as $commentss)

																		<div class="file-image-1  removespruko{{$commentss->id}}" id="imageremove{{$commentss->id}}">
																			<div class="product-image  ">
																				<a href="{{$commentss->getFullUrl()}}" class="imageopen">
																					<img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">
																				</a>
																			</div>
																			<span class="file-name-1">
																				{{Str::limit($commentss->file_name, 10, $end='.......')}}

																			</span>
																		</div>
																		@endforeach

																	</div>
																</div>
															</div>
														</div>

														{{--Customer Reply status end--}}
													@endif
												@endforeach
                                            </div>
                                            @endif


										</div>

										<div class="col-xl-3 col-lg-12 col-md-12">
											<div class="card">
												<div class="card-header  border-0">
													<div class="card-title">{{lang('Arlete VBG Information')}}</div>
												</div>
												<div class="card-body pt-2 ps-0 pe-0 pb-0">
													<div class="table-responsive tr-lastchild">
														<table class="table mb-0 table-information">
															<tbody>

																<tr>
																	<td>
																		<span class="w-50">{{lang('Arlete ID')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">#{{ $tickettrashedview->ticket_id }}</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span class="w-50">{{lang('Catégorie')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		@if($tickettrashedview->category_id != null)
																			@if($tickettrashedview->category != null)

																			<span class="font-weight-semibold">{{ $tickettrashedview->category->name}}</span>

																			@else
																			~
																			@endif
																		@else
																			~
																		@endif

																	</td>
																</tr>
																@if ($tickettrashedview->subcategory != null)
																<tr>
																	<td>
																		<span class="w-50">{{lang('Sous-catégorie')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{$tickettrashedview->subcategoriess->subcategoryname}}</span>

																	</td>
																</tr>
																@endif

																@if ($tickettrashedview->project != null)

																<tr>
																	<td>
																		<span class="w-50">{{lang('Project')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{ $tickettrashedview->project }}</span>
																	</td>
																</tr>
																@endif
																@if($tickettrashedview->priority != null)
																<tr>
																	<td>
																		<span class="w-50">{{lang('Priorité')}}</span>
																	</td>
																	<td>:</td>
																	<td id="priorityid">
																		@if($tickettrashedview->priority == "Low")

																			<span class="badge badge-success-light" >{{ gn_statut($tickettrashedview->priority) }}</span>

																		@elseif($tickettrashedview->priority == "High")

																			<span class="badge badge-danger-light">{{ gn_statut($tickettrashedview->priority) }}</span>

																		@elseif($tickettrashedview->priority == "Critical")

																			<span class="badge badge-danger-dark">{{ gn_statut($tickettrashedview->priority) }}</span>

																		@else

																			<span class="badge badge-warning-light">{{ gn_statut($tickettrashedview->priority) }}</span>

																		@endif
																	</td>
																</tr>
																@else

																<tr>
																	<td>
																		<span class="w-50">{{lang('Priorité')}}</span>
																	</td>
																	<td>:</td>
																	<td id="priorityid">
																		~
																	</td>
																</tr>
																@endif

																<tr>
																	<td>
																		<span class="w-50">{{lang('Opened Date')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{ $tickettrashedview->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('date_format'))}}</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span class="w-50">{{lang('Status')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		@if($tickettrashedview->status == "New")

																		<span class="badge badge-burnt-orange">{{ gn_statut($tickettrashedview->status) }}</span>
																		@elseif($tickettrashedview->status == "Re-Open")

																		<span class="badge badge-teal">{{ gn_statut($tickettrashedview->status) }}</span>
																		@elseif($tickettrashedview->status == "Inprogress")

																		<span class="badge badge-info">{{ gn_statut($tickettrashedview->status) }}</span>
																		@elseif($tickettrashedview->status == "On-Hold")

																		<span class="badge badge-warning">{{ gn_statut($tickettrashedview->status) }}</span>
																		@else

																		<span class="badge badge-danger">{{ gn_statut($tickettrashedview->status) }}</span>
																		@endif

																	</td>
																</tr>
																@if($tickettrashedview->replystatus != null)

																<tr>
																	<td>
																		<span class="w-50">{{lang('Reply Status')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		@if($tickettrashedview->replystatus == "Solved")

																		<span class="badge badge-success">{{ $tickettrashedview->replystatus }}</span>
																		@elseif($tickettrashedview->replystatus == "Unanswered")

																		<span class="badge badge-danger-light">{{ $tickettrashedview->replystatus }}</span>
																		@elseif($tickettrashedview->replystatus == "Waiting for response")

																		<span class="badge badge-warning">{{ $tickettrashedview->replystatus }}</span>
																		@else
																		@endif

																	</td>
																</tr>
																@endif

															</tbody>
														</table>
													</div>
												</div>
											</div>

											<!-- Customer Details -->
											<div class="card">
												<div class="card-header  border-0">
													<div class="card-title">{{lang('Détails de la victime')}}</div>
												</div>
												<div class="card-body text-center pt-2 px-0 pb-0 py-0">
													<div class="profile-pic">
														<div class="profile-pic-img mb-2">
															<span class="bg-success dots" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Online')}}"></span>
															@if ($tickettrashedview->cust->image == null)

																<img src="{{asset('uploads/profile/user-profile.png')}}"  class="brround avatar-xxl" alt="default">
															@else

																<img class="brround avatar-xxl" alt="{{$tickettrashedview->cust->image}}" src="{{asset('uploads/profile/'. $tickettrashedview->cust->image)}}">
															@endif

														</div>
														<div class="text-dark">
															<h5 class="mb-1 font-weight-semibold2">{{$tickettrashedview->cust->username}}</h5>
															<small class="text-muted ">{{ $tickettrashedview->cust->email }}
															</small>
														</div>
													</div>
													<div class="table-responsive text-start tr-lastchild">
														<table class="table mb-0 table-information">
															<tbody>
																<tr>
																	<td>
																		<span class="w-50">{{lang('IP')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{ $tickettrashedview->cust->last_login_ip }}</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span class="w-50">{{lang('Numéro de téléphone')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{ $tickettrashedview->cust->phone}}</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span class="w-50">{{lang('Pays')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{ $tickettrashedview->cust->country }}</span>
																	</td>
																</tr>
																<tr>
																	<td>
																		<span class="w-50">{{lang('Timezone')}}</span>
																	</td>
																	<td>:</td>
																	<td>
																		<span class="font-weight-semibold">{{$tickettrashedview->cust->timezone}}</span>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<!-- End Customer Details -->
											<!--ticke note  -->
											<div class="card">
												<div class="card-header  border-0">
													<div class="card-title">{{lang('Ticket Note')}}</div>

												</div>
												@php $emptynote = $tickettrashedview->ticketnote()->get() @endphp
												@if($emptynote->isNOtEmpty())
												<div class="card-body  item-user">
													<div id="refresh">

														@foreach ($tickettrashedview->ticketnote()->latest()->get() as $note)

														<div class="alert alert-light-warning ticketnote" id="ticketnote_{{$note->id}}" role="alert">
															@if($note->user_id == Auth::id())


															@endif

															<p class="m-0">{{$note->ticketnotes}}</p>
															<p class="text-end mb-0"><small><i><b>{{$note->users->name}}</b> @if(!empty($note->users->getRoleNames()[0])) ({{$note->users->getRoleNames()[0]}}) @endif</i></small></p>
														</div>
														@endforeach

													</div>
												</div>
												@else
												<div class="card-body">
													<div class="text-center ">
														<div class="avatar avatar-xxl empty-block mb-4">
															<svg xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 48 48"><path fill="#CDD6E0" d="M12.8 4.6H38c1.1 0 2 .9 2 2V46c0 1.1-.9 2-2 2H6.7c-1.1 0-2-.9-2-2V12.7l8.1-8.1z"/><path fill="#ffffff" d="M.1 41.4V10.9L11 0h22.4c1.1 0 2 .9 2 2v39.4c0 1.1-.9 2-2 2H2.1c-1.1 0-2-.9-2-2z"/><path fill="#CDD6E0" d="M11 8.9c0 1.1-.9 2-2 2H.1L11 0v8.9z"/><path fill="#FFD05C" d="M15.5 8.6h13.8v2.5H15.5z"/><path fill="#dbe0ef" d="M6.3 31.4h9.8v2.5H6.3zM6.3 23.8h22.9v2.5H6.3zM6.3 16.2h22.9v2.5H6.3z"/><path fill="#FFD15C" d="M22.8 35.7l-2.6 6.4 6.4-2.6z"/><path fill="#334A5E" d="M21.4 39l-1.2 3.1 3.1-1.2z"/><path fill="#FF7058" d="M30.1 18h5.5v23h-5.5z" transform="rotate(-134.999 32.833 29.482)"/><path fill="#40596B" d="M46.2 15l1 1c.8.8.8 2 0 2.8l-2.7 2.7-3.9-3.9 2.7-2.7c.9-.6 2.2-.6 2.9.1z"/><path fill="#F2F2F2" d="M39.1 19.3h5.4v2.4h-5.4z" transform="rotate(-134.999 41.778 20.536)"/></svg>
														</div>
														<h4 class="mb-2">{{lang('Don’t have any notes yet')}}</h4>
														<span class="text-muted">{{lang('Add your notes here')}}</span>
													</div>
												</div>
												@endif
											</div>
											<!-- End ticket note  -->
										</div>
									</div>
								</div>
							</div>

                            @endsection

        @section('scripts')
        <!-- galleryopen JS -->
		<script src="{{gn_asset('assets/plugins/simplelightbox/simplelightbox.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/simplelightbox/light-box.js')}}"></script>


		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


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

                $('body').on('click', '#purchasecodebtn', function()
                {
                    var envatopurchase_id = $(this).data('id');

                    @if(!empty(Auth::user()->getRoleNames()[0]) && Auth::user()->getRoleNames()[0] == 'superadmin')
                    var envatopurchase_i = envatopurchase_id;

                    @else
                        @if(setting('purchasecode_on') == 'on')
                        var envatopurchase_i = envatopurchase_id;
                        @else
                        var trailingCharsIntactCount = 4;

                        var envatopurchase_i = new Array(envatopurchase_id.length - trailingCharsIntactCount + 1).join('*') + envatopurchase_id.slice( -trailingCharsIntactCount);
                        @endif
                    @endif



                    $('.modal-title').html('Purchase Details');
                    $('.purchasecode').html(envatopurchase_i);
                    $('#addpurchasecode').modal('show');
                    $('#purchasedata').html('');

                    $.ajax({
                        url:"{{ route('admin.ticketlicenseverify') }}",
                        type:"POST",
                        data: {
                            envatopurchase_id: envatopurchase_id
                        },
                        success:function (data) {
                            $('#purchasedata').html(data);
                        },
                        error:function(data){
                            $('#purchasedata').html('');
                        }

                    });
                });

                // TICKET RESTORE SCRIPT
				$('body').on('click', '#show-restore', function () {
					var _id = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to reset this record?', 'alerts')}}`,
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "post",
								url: SITEURL + "/admin/tickettrashedrestore/"+_id,
								success: function (data) {
									toastr.success(data.success);
									location.replace('{{route('admin.tickettrashed')}}');
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					});

				});
				// TICKET RESTORE SCRIPT END
				// TICKET DELETE SCRIPT
				$('body').on('click', '#show-delete', function () {
					var _id = $(this).data("id");
					swal({
						title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
						text: "{{lang('This might erase your records permanently', 'alerts')}}",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "post",
								url: SITEURL + "/admin/tickettrasheddestroy/"+_id,
								success: function (data) {
									toastr.success(data.success);
									location.replace('{{route('admin.tickettrashed')}}');
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					});

				});
				// TICKET DELETE SCRIPT END

            })(jQuery);
        </script>

        @endsection

        @section('modal')

        <!-- PurchaseCode Modals -->
			<div class="modal fade sprukopurchasecode"  id="addpurchasecode" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" ></h5>
							<button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<input type="hidden" name="purchasecode_id" id="purchasecode_id" value="">
						<div class="modal-body">
							<div class="mb-4">
								<!-- <strong>{{lang('Puchase Code')}} :</strong>
								<span class="purchasecode"></span> -->
							</div>
							<div id="purchasedata">

							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End PurchaseCode Modals   -->
        @endsection
