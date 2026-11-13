

												@php $notifys = auth()->user()->unreadNotifications()->paginate(2); $badgecount = auth()->user()->unreadNotifications->count(); @endphp
												<a class="nav-link icon ps-0 ms-0" data-bs-toggle="dropdown" id="badgecount">
												    @include('includes.admin.badgecount')
												</a>
												<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated p-0 notification-dropdown-container">
													<div class="header-dropdown-list message-menu" id="message-menu">
    													<div class="dropdown-header border-bottom d-flex justify-content-between">

    														<div id="markasreadcount">
    															@include('includes.admin.markasreadcount')

    														</div>
    													</div>
													    <div id="notifyreading">
														    @include('includes.admin.allnotify')
												        </div>

													</div>
													<div class="text-center p-2">
														<a href="{{route('notificationpage')}}" class="smark-all">{{lang('See All Notifications', 'notification')}} </a>
													</div>
												</div>
