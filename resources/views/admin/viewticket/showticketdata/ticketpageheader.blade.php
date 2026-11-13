                                <div class="page-rightheader d-flex ms-md-auto">

									<!-- @if($ticket->status == 'Closed')
										<button type="buttom" class="btn btn-sm btn-light me-2 d-none" id="ticket_to_article" value="">
											<i class="feather feather-book me-3 fs-18 my-auto text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Create Article')}}"></i>
											<span>{{lang('Ticket To Article')}} </span>
										</button>
										<a href="{{route('admin.article.ticket', $ticket->ticket_id)}}" class="btn btn-sm btn-light me-2"  id="ticket_to_article">
											<i class="feather feather-book me-3 fs-18 my-auto text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Create Article')}}"></i>
											<span>{{lang('Ticket To Article')}} </span>
										</a>
									@endif -->

									<div class="btn-list">

										<div class="dropdown">
											<button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
												<i class="fe fe-more-vertical"></i>
											</button>
											<div class="dropdown-menu">

												<a href="javascript:void(0)" data-id="{{$ticket->id}}" class="dropdown-item" id="show-delete">
													<i class="fa fa-trash me-3 fs-18 my-auto text-muted" data-id="{{$ticket->id}}"></i>
													{{lang('Supprimer Arlette')}}
												</a>

												@if($ticket->cust->voilated != 'on')
													<a href="{{route('voilating.customer',$ticket->cust->id)}}" class="dropdown-item">
														<i class="fa fa-exclamation-triangle me-3 fs-18 my-auto text-muted" data-id="{{$ticket->id}}"></i>
														{{lang('Voilation')}}
													</a>
												@else
													<a href="{{route('unvoilating.customer',$ticket->cust->id)}}" class="dropdown-item">
														<i class="fa fa-exclamation-triangle me-3 fs-18 my-auto text-muted" data-id="{{$ticket->id}}"></i>
														{{lang('Un-Voilation')}}
													</a>
												@endif


												@if($ticket->status == 'Closed')

												@else

												@if($ticket->status == 'Suspend')
													<a href="javascript:void(0)" data-id="{{$ticket->id}}" class="dropdown-item" id="unsuspend">
														<i class="fa fa-pause-circle me-3 fs-18 my-auto text-muted" data-id="{{$ticket->id}}"></i>
														{{lang('Unsuspend Ticket')}}
													</a>
												@else
													<a href="javascript:void(0)" data-id="{{$ticket->id}}" class="dropdown-item" id="suspend">
														<i class="fa fa-pause-circle me-3 fs-18 my-auto text-muted" data-id="{{$ticket->id}}"></i>
														{{lang('Suspend Ticket')}}
													</a>
												@endif

												@endif

											</div>
										</div>
									</div>
								</div>
