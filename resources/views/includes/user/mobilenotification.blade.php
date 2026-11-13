                                    <div class="dropdown me-0 pe-1 header-message">
                                        <a class="nav-link icon p-0 mt-1" data-bs-toggle="dropdown">
                                            <i class="feather feather-bell header-icon"></i>
                                            <!-- Counter - Alerts -->
                                            @php $notifycount = auth()->guard('customer')->user()->unreadNotifications->count() @endphp
                                            @if($notifycount == '0')

                                            <span class="badge badge-gray">0</span>
                                            @else
                                            <span class="badge badge-success badge-counter pulse-success side-badge">{{ $notifycount }}</span>
                                            @endif
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-0 notification-dropdown-container">
                                            <div class="dropdown-header border-bottom d-flex justify-content-between">
                                                <div>
                                                    <span class="font-weight-semibold fs-14">{{lang('New Notifications', 'notification')}}({{ auth()->guard('customer')->user()->unreadNotifications->count() }})</span>
                                                </div>
                                                <div>
                                                    @if($notifycount == '0')
                                                    
                                                    <span class="mark-read-none fs-13">{{lang('Mark all as read', 'notification')}}</span>
                                                    @else

                                                    <span class="mark-read text-primary fs-13">{{lang('Mark all as read', 'notification')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(auth()->guard('customer')->user())
                                                @forelse( auth()->guard('customer')->user()->unreadNotifications()->paginate(2) as $notification)
                                                    @if($notification->data['status'] == 'New')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('Your new ticket has been created', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($notification->data['status'] == 'Closed')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('Your ticket has been closed', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($notification->data['status'] == 'On-Hold')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('Your ticket status is On-Hold', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($notification->data['status'] == 'Re-Open')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('Your ticket has been Reopened', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($notification->data['status'] == 'Inprogress')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('You got a new reply on this ticket', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($notification->data['status'] == 'overdue')

                                                        <a class="dropdown-item border-bottom mark-as-read" href="{{$notification->data['clink']}}" data-id="{{ $notification->id }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                                                                </div>
                                                                <div class="d-flex">
                                                                    <div class="ps-3">
                                                                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                                                        <p class="fs-13 mb-1 text-wrap">  {{lang('Your ticket status is Overdue', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                                                        <div class="small text-muted">
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if ($notification->data['status'] == 'mail')

                                                    <a class="dropdown-item border-bottom mark-as-read" href="{{route('customer.notiication.view', $notification->id)}}" >
                                                        <div class="d-flex ">
                                                            <div class="">
                                                                <span class="bg-success-transparent brround fs-12 notifications"><i class="feather feather-mail sidemenu_icon fs-20 text-success"></i></span>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="ps-3">
                                                                    <h6 class="mb-1"> {{$notification->data['mailsubject']}}</h6>
                                                                    <p class="fs-13 mb-1 text-wrap">
                                                                        {{Str::limit($notification->data['mailtext'], '100', '.......')}}
                                                                    </p>
                                                                    <div class="small text-muted">
                                                                        {{ $notification->created_at->diffForHumans() }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @endif
                                                @empty

                                                    <a class="dropdown-item border-bottom mark-as-read notification-dropdown" href="">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="d-flex">
                                                                <div class="ps-3 text-center">
                                                                    <img src="{{asset('assets/images/nonotification.png')}}" alt="">
                                                                    <p class="fs-13 mb-1 text-muted">{{lang('Il n\'y a pas de nouvelles notifications à afficher')}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforelse
                                            @endif

                                            <div class=" text-center p-2">
                                                <a href="{{route('client.notification')}}" class="">{{lang('Voir toutes les notifications')}}</a>
                                            </div>
                                        </div>

                                    </div>