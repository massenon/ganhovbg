
@extends('layouts.usermaster')

		@section('styles')

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

		<link href="{{gn_asset('assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />

		<!-- GALLERY CSS -->
		<link href="{{gn_asset('assets/plugins/simplelightbox/simplelightbox.css')}}" rel="stylesheet">

		@endsection

		@section('content')

		<!-- Section -->
		<section>
			<div class="bannerimg cover-image" data-bs-image-src="{{asset('assets/images/photos/banner1.jpg')}}">
				<div class="header-text mb-0">
					<div class="container">
						<div class="row text-white">
                            <div class="col">
                                <h1 class="mb-0">GANXÔ Alerte VBG</h1>
                            </div>
                            <div class="col col-auto">
                                <ol class="breadcrumb text-center">
                                    <li class="breadcrumb-item">
                                        <a href="{{url('/')}}" class="text-white-50">{{lang('Accueil')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="#" class="text-white">Formulaire d'Alerte</a>
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
				<div class="container">
					<div class="row">
						<div class="col-xxl-3">
							<div id="scroll-stickybar" class="w-100 pos-sticky-scroll">
								<div class="card">
									<div class="card-body text-center item-user">
										<div class="profile-pic">
											<div class="profile-pic-img mb-2">
												<span class="bg-success dots" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="{{lang('Online')}}"></span>
												<img src="{{asset('uploads/profile/user-profile.png')}}"  class="brround avatar-xxl" alt="default">
											</div>
											<div class="text-dark">
												<h5 class="mb-1 font-weight-semibold2">{{$ticket->cust->username}}</h5>
											</div>
											<small class="text-muted">{{$ticket->cust->email}}</small>
										</div>
									</div>
								</div>

								@if($ticket->purchasecode != null)

								<!-- Purchase Code Details -->
								<div class="purchasecodes alert alert-light-warning br-13 mb-5">
									<div class="d-flex flex-wrap align-items-center justify-content-between">
										<div class="d-sm-flex d-block flex-wrap">
											<p class="mb-0 font-weight-semibold">{{lang('Puchase Code')}} :</p>
											<span class="">{{ Str::padLeft(Str::substr(decrypt($ticket->purchasecode), -4), Str::length(decrypt($ticket->purchasecode)), Str::padLeft('*', 1)) }}</span>
										</div>
										<div>
											@if($ticket->purchasecodesupport == 'Supported')

											<span class="badge badge-success ms-auto float-end p-1 m-0">{{lang('Assistance active')}}</span>
											@elseif($ticket->purchasecodesupport == 'Expired')

											<span class="badge badge-danger ms-auto float-end p-1 m-0">{{lang('Assistance expirée')}}</span>
											<p class="mb-0 mt-3">
											<small>{{lang('Your support for this item has expired. You may still leave a comment but please renew support if you are asking the author for help. View the item support policy')}}</small>
											</p>
											@else
											@endif
										</div>
									</div>
                                 </div>
								<!-- End Purchase Code Details -->

								@endif
								<!--  Ticket Information -->
								<div class="card">
									<div class="card-header  border-0">
										<div class="card-title">{{lang('Arlete VBG Information')}}</div>
										<input type="hidden" name="" data-id="{{$ticket->id}}" id="ticket">
									</div>
									<div class="card-body pt-2 px-0 pb-0">
										<div class="table-responsive tr-lastchild">
											<table class="table mb-0 table-information">
												<tbody>
													<tr>
														<td>
															<span class="w-50">{{lang('Arlete ID')}}</span>
														</td>
														<td>:</td>
														<td>
															<span class="font-weight-semibold">{{ $ticket->ticket_id }}</span>
														</td>
													</tr>
													<tr>
														<td>
															<span class="w-50">{{lang('Catégorie')}}</span>
														</td>
														<td>:</td>
														<td>
															@if($ticket->category_id != null)
																@if($ticket->category != null)

																<span class="font-weight-semibold">{{ $ticket->category->name }}</span>
																@else
																~
																@endif
															@else
																~
															@endif
														</td>
													</tr>
													@if ($ticket->subcategory != null)

													<tr>
														<td>
															<span class="w-50">{{lang('Sub-Category')}}</span>
														</td>
														<td>:</td>
														<td>
															<span class="font-weight-semibold">{{ $ticket->subcategories->subcatlists->subcategoryname }}</span>

														</td>
													</tr>
													@endif
													@if ($ticket->project != null)

													<tr>
														<td>
															<span class="w-50">{{lang('Project')}}</span>
														</td>
														<td>:</td>
														<td>
															<span class="font-weight-semibold">{{ $ticket->project }}</span>
														</td>
													</tr>
													@endif
													<tr>
														<td>
															<span class="w-50">{{lang('Open Date')}}</span>
														</td>
														<td>:</td>
														<td>
															<span class="font-weight-semibold">{{ $ticket->created_at->format(setting('date_format'))}} </span>
														</td>
													</tr>
													<tr>
														<td>
															<span class="w-50">{{lang('Status')}}</span>
														</td>
														<td>:</td>
														<td>
															@if($ticket->status == "New")

															<span class="badge badge-burnt-orange">{{ lang($ticket->status) }}</span>
															@elseif($ticket->status == "Re-Open")

															<span class="badge badge-teal">{{ lang($ticket->status) }}</span>
															@elseif($ticket->status == "Inprogress")

															<span class="badge badge-info">{{ lang($ticket->status) }}</span>
															@elseif($ticket->status == "On-Hold")

															<span class="badge badge-warning">{{ lang($ticket->status) }}</span>
															@else

															<span class="badge badge-danger">{{ lang($ticket->status) }}</span>
															@endif
														</td>
													</tr>
													@if($ticket->replystatus != null && $ticket->replystatus == "Solved" || $ticket->replystatus == "Unanswered" || $ticket->replystatus == "Waiting Response")
													<tr>
														<td>
															<span class="w-50">{{lang('Reply Status')}}</span>
														</td>
														<td>:</td>
														<td>
															@if($ticket->replystatus == "Solved")
															<span class="badge badge-success">{{ lang($ticket->replystatus) }}</span>
															@elseif($ticket->replystatus == "Unanswered")
															<span class="badge badge-danger-light">{{ lang($ticket->replystatus) }}</span>
															@elseif($ticket->replystatus == "Waiting Response")
															<span class="badge badge-warning">{{ lang($ticket->replystatus) }}</span>
															@else
															@endif
														</td>
													</tr>
													@endif

													@php $customfields = $ticket->ticket_customfield()->get(); @endphp
													@if($customfields->isNotEmpty())
													@foreach ($customfields as $customfield)
													@if($customfield->fieldtypes != 'textarea')
														@if($customfield->privacymode == '1')
															@php
																$extrafiels = decrypt($customfield->values);
															@endphp

															<tr>
																<td>{{$customfield->fieldnames}}</td>
																<td>: </td>
																<td>{{$extrafiels}} </td>
															</tr>
														@else

															<tr>
																<td>{{$customfield->fieldnames}}</td>
																<td>:</td>
																<td>{{$customfield->values}} </td>
															</tr>

														@endif
													@endif
													@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<!-- End Ticket Information -->
								<!-- Bussiness Hour -->
								{{-- @if(bussinesshour()->isNotEmpty()) --}}
								@if(setting('businesshoursswitch') == 'on')
								<div class="card p-3 pricing-card border d-flex notify-days-toggle">
									<div class="d-md-flex d-block">
										<div  class="support-img1">
											@if(setting('supporticonimage') != null)

											<img src="{{asset('uploads/support/'. setting('supporticonimage'))}}" class="rounded-circle" alt="img" width="50" height="50">
											@else

											<img src="{{asset('assets/images/support/support.png')}}" alt="img" width="50" height="50">
											@endif

										</div>
										<div class="card-header text-justified flex-1 pt-0 ps-md-3 ps-0 pb-0 ">
											<p class="fs-18 font-weight-semibold mb-1">{{setting('businesshourstitle')}}
												@foreach(bussinesshour() as $bussiness)
												@if(now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks)


                                                @if(strtotime($bussiness->starttime) <= strtotime(now()->timezone(setting('default_timezone'))->format('h:i A')) && strtotime($bussiness->endtime) >= strtotime(now()->timezone(setting('default_timezone'))->format('h:i A')) || $bussiness->starttime == "24H")

                                                <span class="ms-3 badge bg-success text-white  mt-1 fs-12 font-weight-normal">{{lang('online')}}</span>
                                                @else
                                                <span class="ms-3 badge bg-danger text-white  mt-1 fs-12 font-weight-normal">{{lang('offline')}}</span>
                                                @endif

												@endif
												@endforeach

											</p>

											<p class="fs-13 mb-0 text-muted">{{setting('businesshourssubtitle')}}</p>
										</div>
										<div class="my-4 ms-auto">
											<span class="fe fe-chevron-down float-end notify-arrow"></span>
										</div>

									</div>
									<div class="card-body  pt-0 pb-0 px-4 notify-days-container">
										<ul class="custom-ul text-justify pricing-body text-muted ps-0 mb-4">
											@foreach(bussinesshour() as $bussiness)
											@if($bussiness->weeks != null)
											<li class="mb-2">
												<div class="row br-5 notify-days-cal align-items-center p-2 br-5 border text-center {{now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks ? 'bg-success-transparent' : '' }}">
													<div class="col-xxl-3 col-xl-3 col-sm-12 ps-0">
														@if($bussiness->weeks == 'Sun')

														<span class="badge bg-info-transparent fs-13 font-weight-normal  w-100 ">{{$bussiness->weeks}}</span>
														@else

														<span class="badge {{now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks ? 'bg-success' : 'bg-info' }}   fs-13 font-weight-normal  w-100 ">{{$bussiness->weeks}}</span>

														@endif
													</div>
													<div class="col-xxl-3 col-xl-4 col-sm-12">
														@if(now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks)

														<span class="{{$bussiness->status != "Closed" ? 'text-success' : 'text-success' }} fs-12 ms-2">Today</span>
														@endif
													</div>
													<div class="col-xxl-6 col-xl-5 col-sm-12 px-0">
														@if($bussiness->status == "Closed")
														<span class="text-danger fs-12 ms-2">{{$bussiness->status}}</span>
														@else
														<span class="ms-0 fs-13">{{$bussiness->starttime}}
														@if($bussiness->starttime !== null && $bussiness->endtime != null )
														<span class="fs-10 mx-1">- </span>
														@endif
														</span>
														@if($bussiness->starttime !== null && $bussiness->endtime )
														<span class="ms-0">{{$bussiness->endtime}}</span>
														@endif
														@endif
													</div>
												</div>
											</li>
											@endif
											@endforeach
										</ul>
									</div>
								</div>

								@endif
								{{-- @endif --}}
								<!-- End Bussiness Hour -->
							</div>
						</div>
						<div class="col-xxl-9">
							<div class="card">
								<div class="card-header border-0 mb-1 d-block">
									<div class="d-sm-flex d-block">
										<div>
											<h4 class="card-title mb-1 fs-22">{{ $ticket->subject }} </h4>
										</div>
										<div class="card-options float-sm-end ticket-status">
											<a href="{{route('user.pdfmake', $ticket->id)}}" class="btn btn-sm btn-white printticketdata" >

												<i class="feather feather-printer" data-id="{{$ticket->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Print')}}"></i>
											</a>
										</div>
									</div>
									<small class="fs-13 "><i class="feather feather-clock text-muted me-1"></i>{{lang('Created At')}} <span class="text-muted">{{$ticket->created_at->diffForHumans()}}</span></small>
								</div>
								<div class="card-body readmores pt-2">
									<div>
										<span>{!! $ticket->message !!}</span>
										<div class="row galleryopen mt-4">
                                            <div class="uhelp-attach-container flex-wrap">

                                                @if($ticket->emailticketfile != null)
													@if($ticket->emailticketfile == 'mismatch')
														<div class="border d-table rounded attach-container-width mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('File upload failed, Please make sure that the file size is within the allowed limits and that the file format is supported.')}}">
															<div class="d-flex align-items-center file-attach-uhelp mt-1">
																<div class="me-2">
																	<a href="#" class="uhelp-attach-acion d-flex align-items-center justify-content-center"><i class="fe feather-alert-circle text-danger fs-20"></i></a>
																</div>
																<div class="d-flex align-items-center text-muted fs-12 me-3">
																	<p class="file-attach-name text-danger mb-0">Upload Failed</p>
																</div>
															</div>
														</div>
													@else
														@php
															$arraytype = explode(',', $ticket->emailticketfile);
														@endphp
														@foreach($arraytype as $arraytypes)
															@php
																$arrayextension = explode('.', $arraytypes);
																$finalextension = $arrayextension[1];
															@endphp
															<div class="border d-table rounded attach-container-width mb-2">
																<div class="d-flex align-items-center file-attach-uhelp">
																	<div class="me-2">
																		@if($finalextension == 'jpg' || $finalextension == 'jpeg' || $finalextension == 'JPG')
																			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
																				<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
																			</svg>
																		@elseif($finalextension == 'pdf')
																			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
																			<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
																			</svg>
																		@elseif($finalextension == 'csv')
																			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
																			<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
																			</svg>
																		@elseif($finalextension == 'png')
																			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
																				<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
																			</svg>
																		@else
																			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
																				<path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
																			</svg>
																		@endif
																	</div>
																	<div class="d-flex align-items-center text-muted fs-12 me-3">
																		<p class="file-attach-name text-truncate mb-0">{{ $arrayextension[0] }}</p>.{{ $arrayextension[1] }}
																	</div>
																	<a href="{{route('emailtoticketimageurl', array($ticket->id,$arraytypes))}}" target="_blank" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
																						class="fe fe-eye text-muted fs-12"></i></a>
																	<a href="{{route('emailtoticketdownload', array($ticket->id,$arraytypes))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
																			class="fe fe-download text-muted fs-12"></i></a>
																</div>
															</div>
														@endforeach

													@endif
												@endif


                                                @foreach ($ticket->getMedia('ticket') as $ticketss)
                                                @php
                                                    $a = explode('.', $ticketss->file_name);
                                                    $aa = $a[1];
                                                @endphp

                                                <div class="border d-table rounded attach-container-width mb-2">
                                                    <div class="d-flex align-items-center file-attach-uhelp">
                                                        <div class="me-2">
                                                            @if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                </svg>
                                                            @elseif($aa == 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                </svg>
                                                            @elseif($aa == 'csv')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                </svg>
                                                            @elseif($aa == 'png')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                            <p class="file-attach-name text-truncate mb-0">{{ $a[0] }}</p>.{{ $a[1] }}
                                                        </div>
                                                        <a href="{{route('imageurl', array($ticketss->id,$ticketss->file_name))}}" target="_blank" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                            class="fe fe-eye text-muted fs-12"></i></a>
                                                        <a href="{{route('imagedownload', array($ticketss->id,$ticketss->file_name))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                class="fe fe-download text-muted fs-12"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
										</div>
									</div>
								</div>
								{{-- @if($comments->isEmpty())
								<div class="card-footer">
									<button class="btn btn-success mb-1 ms-auto"  data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
										{{lang('langconvert.newwordslang.replyticket')}}
									</button>
								</div>
								@endif --}}
							</div>

							{{-- Reply Ticket Display --}}
							@if ($ticket->status == 'Closed')

								@if (setting('USER_REOPEN_ISSUE') == 'yes')
									@if (setting('USER_REOPEN_TIME') == '0')
									<div class="card">
										<form method="POST" action="{{url('guest/closed/' .$ticket->ticket_id)}}">
											@csrf
											@honeypot
											<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
											<div class="card-body">
												<p>{{lang('This ticket is closed. Do you want to reopen it?')}}
												<input type="submit" class="btn btn-secondary" value="{{lang('Re-ouvrir')}}" onclick="this.disabled=true;this.form.submit();"> </p>
											</div>
										</form>
									</div>
									@else
										@if($ticket->closing_ticket != null)
										@if (now()->format('Y-m-d') <= $ticket->closing_ticket->adddays(setting('USER_REOPEN_TIME'))->format('Y-m-d'))
											<div class="card">
												<form method="POST" action="{{url('guest/closed/' .$ticket->ticket_id)}}">
													@csrf
													@honeypot
													<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
													<div class="card-body">
														<p>{{lang('This ticket is closed. Do you want to reopen it?')}}
														<input type="submit" class="btn btn-secondary" value="{{lang('Re-ouvrir')}}" onclick="this.disabled=true;this.form.submit();"> </p>
													</div>
												</form>
											</div>
										@endif
										@endif
									@endif
								@endif

							@elseif($ticket->status == 'On-Hold')

								<div class="alert alert-light-warning note br-13" role="alert">
									<p class="m-0"><b>{{lang('Note:-')}}</b> {{$ticket->note}}</p>
								</div>
							@else
								@if($comments->isNotEmpty())

									<div class="card">
										<div class="panel-group1" id="accordion1">
											<div class="panel panel-default overflow-hidden br-7">
												<div class="panel-heading1 panel-arrows">
													<h4 class="panel-title1">
														<a class="accordion-toggle collapsed bg-gradient-primary" data-bs-toggle="collapse"
															data-parent="#accordion" href="#collapseFour" aria-expanded="false">{{lang('Reply Ticket')}}</a>
													</h4>
												</div>
												<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-expanded="false">
													<div class="panel-body p-0">
														<div class="card border-0 shadow-none mb-0">
															<form method="POST" action="{{url('guest/ticket/'. $ticket->ticket_id)}}" enctype="multipart/form-data">
																@csrf
																@honeypot
																<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
																<div class="card-body">
																	<div class="form-group">
																		<textarea class="summernote form-control @error('comment') is-invalid @enderror" rows="6" cols="100" name="comment" aria-multiline="true"></textarea>
																		@error('comment')
																			<span class="invalid-feedback" role="alert">
																				<strong>{{ lang($message) }}</strong>
																			</span>
																		@enderror

																	</div>
																	@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
																	<div class="form-group">
																		<label class="form-label">{{lang('Upload File')}}</label>
																		<div class="file-browser">
																			<div class="needsclick dropzone" id="document-dropzone"></div>
																			<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
																		</div>
																	</div>
																	@endif
																	<div class="form-group">
																		<div class="custom-controls-stacked d-md-flex">
																			<label class="form-label mt-1 me-5">{{lang('Status')}}</label>
																			<label class="custom-control form-radio success me-4">
																				@if($ticket->status == 'Re-Open')
																				<input type="radio" class="custom-control-input" name="status" value="Inprogress"
																				{{ $ticket->status == 'Re-Open' ? 'checked' : '' }}>
																				<span class="custom-control-label">{{lang('Inprogress')}}</span>
																				@elseif($ticket->status == 'Inprogress')
																				<input type="radio" class="custom-control-input" name="status" value="{{$ticket->status}}"
																				{{ $ticket->status == 'Inprogress' ? 'checked' : '' }}>
																				<span class="custom-control-label">{{lang('Leave as current')}}</span>
																				@else
																				<input type="radio" class="custom-control-input" name="status" value="{{$ticket->status}}"
																				{{ $ticket->status == 'New' ? 'checked' : '' }}>
																				<span class="custom-control-label">{{lang('Nouvelle')}}</span>
																				@endif
																			</label>
																			<label class="custom-control form-radio success">
																				<input type="radio" class="custom-control-input" name="status" value="Closed">
																				<span class="custom-control-label">{{lang('Solved')}}</span>
																			</label>
																		</div>
																	</div>
																</div>
																<div class="card-footer">
																	<div class="form-group float-end">
																		<input type="submit" class="btn btn-secondary" value="{{lang('Reply Ticket')}}" onclick="this.disabled=true;this.form.submit();">
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@else

									<div class="card">
										<div class="card-header border-0">
											<h4 class="card-title">{{lang('Reply Ticket')}}</h4>
										</div>
										<form method="POST" action="{{url('guest/ticket/'. $ticket->ticket_id)}}" enctype="multipart/form-data">
											@csrf
											@honeypot
											<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
											<div class="card-body">
												<div class="form-group">
													<textarea class="summernote form-control @error('comment') is-invalid @enderror" rows="6" cols="100" name="comment" aria-multiline="true"></textarea>
													@error('comment')
														<span class="invalid-feedback" role="alert">
															<strong>{{ lang($message) }}</strong>
														</span>
													@enderror

												</div>
												@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
												<div class="form-group">
													<label class="form-label">{{lang('Upload File')}}</label>
													<div class="file-browser">
														<div class="needsclick dropzone" id="document-dropzone"></div>
														<small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
													</div>
												</div>
												@endif
												<div class="form-group">
													<div class="custom-controls-stacked d-md-flex">
														<label class="form-label mt-1 me-5">{{lang('Status')}}</label>
														<label class="custom-control form-radio success me-4">
															@if($ticket->status == 'Re-Open')
															<input type="radio" class="custom-control-input" name="status" value="Inprogress"
															{{ $ticket->status == 'Re-Open' ? 'checked' : '' }}>
															<span class="custom-control-label">{{lang('Inprogress')}}</span>
															@elseif($ticket->status == 'Inprogress')
															<input type="radio" class="custom-control-input" name="status" value="{{$ticket->status}}"
															{{ $ticket->status == 'Inprogress' ? 'checked' : '' }}>
															<span class="custom-control-label">{{lang('Leave as current')}}</span>
															@else
															<input type="radio" class="custom-control-input" name="status" value="{{$ticket->status}}"
															{{ $ticket->status == 'New' ? 'checked' : '' }}>
															<span class="custom-control-label">{{lang('Nouvelle')}}</span>
															@endif
														</label>
														<label class="custom-control form-radio success">
															<input type="radio" class="custom-control-input" name="status" value="Closed">
															<span class="custom-control-label">{{lang('Solved')}}</span>
														</label>
													</div>
												</div>
											</div>
											<div class="card-footer">
												<div class="form-group float-end">
													<input type="submit" class="btn btn-secondary" value="{{lang('Reply Ticket')}}" onclick="this.disabled=true;this.form.submit();">
												</div>
											</div>
										</form>
									</div>
								@endif

							@endif
							<!---- End Reply Ticket Display ---->

							<!---- Comments Display ---->
								@if($comments->isNotEmpty())
								<div class="card support-converbody" >
									<div class="card-header">
										<h4 class="card-title">{{lang('conversations')}}</h4>
										{{-- <button class="btn btn-success mb-1 ms-auto"  data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
											{{lang('langconvert.newwordslang.replyticket')}}
										</button> --}}
									</div>
									<div id="spruko_loaddata">

										@include('guestticket.showdataticket')

									</div>
								</div>
								@endif
							<!--- End Comments Display -->
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Section-->

		@endsection

		@section('scripts')


		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-ticketview.js')}}"></script>

		<!-- INTERNAL DropZone js-->
		<script src="{{gn_asset('assets/plugins/dropzone/min/dropzone.min.js')}}"></script>

		<!-- GALLERY JS -->
		<script src="{{gn_asset('assets/plugins/simplelightbox/simplelightbox.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/simplelightbox/light-box.js')}}"></script>

		<!--Showmore Js-->
		<script src="{{gn_asset('assets/js/jquery.showmore.js')}}"></script>

		<script src="{{gn_asset('assets/plugins/jquerysticky/jquery-sticky/jquery-sticky.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/jquerysticky/jquery-sticky.js')}}"></script>

		<script src="{{gn_asset('assets/plugins/printpage/printpage.js')}}"></script>

		<script type="text/javascript">

			"use strict";

			(function($){

				// Delete Media
				$('body').on('click', '.imgdel', function () {

					var product_id = $(this).data("id");
					swal({
						title: `Are you sure you want to delete this record?`,
						text: "If you delete this, it will be gone forever.",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "DELETE",
								url: "{{ url('/image/delete/') }}"+'/'+product_id,
								data: {
								"_token": "{{ csrf_token() }}",

								},
								success: function (data) {
									//  table.draw();
									$('#imageremove'+ product_id).remove();
								},

								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					});

				});

			})(jQuery);

			// Showmore custom Js
            let readMore = document.querySelectorAll('.readmores')
            readMore.forEach(( element, index)=>{
                if(element.clientHeight <= 120)    {
                    element.children[0].classList.add('end')
                }
                else{
                    element.children[0].classList.add('readMore')
                }
            })
            $(`.readMore`).showmore({
                closedHeight: 60,
                buttonTextMore: 'Read More',
                buttonTextLess: 'Read Less',
                buttonCssClass: 'showmore-button',
                animationSpeed: 0.5
            });

			// Edit Form
			function showEditForm(id) {
				var x = document.querySelector(`#supportnote-icon-${id}`);

				if (x.style.display == "block") {
					x.style.display = "none";
				}
				else {

					x.style.display = "block";
				}
			}

			@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')

			// Image Upload
			var uploadedDocumentMap = {}
			Dropzone.options.documentDropzone = {
				url: '{{route('guest.imageupload')}}',
				maxFilesize: '{{setting('FILE_UPLOAD_MAX')}}', // MB
				addRemoveLinks: true,
				acceptedFiles: '{{setting('FILE_UPLOAD_TYPES')}}',
				maxFiles: '{{setting('MAX_FILE_UPLOAD')}}',
				headers: {
				'X-CSRF-TOKEN': "{{ csrf_token() }}"
				},
				success: function (file, response) {
				$('form').append('<input type="hidden" name="comments[]" value="' + response.name + '">')
				uploadedDocumentMap[file.name] = response.name
				},
				removedfile: function (file) {
				file.previewElement.remove()
				var name = ''
				if (typeof file.file_name !== 'undefined') {
					name = file.file_name
				} else {
					name = uploadedDocumentMap[file.name]
				}
				$('form').find('input[name="comments[]"][value="' + name + '"]').remove()
				},
				init: function () {
				@if(isset($project) && $project->document)
					var files =
					{!! json_encode($project->document) !!}
					for (var i in files) {
					var file = files[i]
					this.options.addedfile.call(this, file)
					file.previewElement.classList.add('dz-complete')
					$('form').append('<input type="hidden" name="comments[]" value="' + file.file_name + '">')
					}
				@endif
				}
			}

			@endif

			// Scrolling js
			var page = 1;
			$(window).scroll(function() {
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					page++;
					loadMoreData(page);
				}
			});

			function loadMoreData(page){
				$.ajax(
				{
					url: '?page=' + page,
					type: "get",
				})
				.done(function(data)
				{
					$("#spruko_loaddata").append(data.html);
				})
				.fail(function(jqXHR, ajaxOptions, thrownError)
				{
					alert('server not responding...');
				});
			}

			$('.printticketdata').printPage();

            let notifyToggle = document.querySelector('.notify-days-toggle');
			let notifyContainer = document.querySelector('.notify-days-container');
            if(notifyToggle){
				notifyToggle.addEventListener('click', ()=>{
					notifyContainer.classList.toggle('show-days');
					notifyToggle.querySelector('.notify-arrow').classList.toggle('hide-container')
				})
			}

		</script>

		@endsection
