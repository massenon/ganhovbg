                                    @php $notifys = auth()->guard('customer')->user()->unreadNotifications()->paginate(2); $badgecount = auth()->guard('customer')->user()->unreadNotifications->count(); @endphp
                                    <li class="dropdown me-3 header-message">
                                        <a class="nav-link icon p-0 mt-1 badgecount" data-bs-toggle="dropdown" >
                                            @include('includes.user.badgecount')
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-0 notification-dropdown-container">
                                            <div class="dropdown-header border-bottom d-flex justify-content-between">
                                                
                                                <div class="markasreadcount">
                                                    @include('includes.user.markasreadcount')
                                                </div>
                                            </div>
                                            <div class="notifyreading">
                                                
                                                @include('includes.user.allnotify')
                                            </div>
                                            <div class=" text-center p-2">
                                                <a href="{{route('client.notification')}}" class="cmark-all">{{lang('See All Notifications', 'notification')}} </a>
                                            </div>
                                        </div>

                                    </li>