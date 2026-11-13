                                                
													<i class="feather feather-bell header-icon"></i>
													<!-- Counter - Alerts -->
													@php $badgecount = auth()->user()->unreadNotifications->count() @endphp
													@if($badgecount == '0')

													<span class="badge badge-gray">0</span>
													@else
													<span class="badge badge-success badge-counter pulse-success side-badge">{{ $badgecount }}</span>
													@endif