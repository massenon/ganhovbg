@extends('layouts.usermaster')


@section('content')


                                <!-- Section -->
                                <section>
                                    <div class="bannerimg cover-image" data-bs-image-src="{{asset('assets/images/photos/banner1.jpg')}}">
                                        <div class="header-text mb-0">
                                            <div class="container ">
                                                <div class="row text-white">
                                                    <div class="col">
                                                        <h1 class="mb-0">{{lang('Notification', 'menu')}}</h1>
                                                    </div>
                                                    <div class="col col-auto">
                                                        <ol class="breadcrumb text-center">
                                                            <li class="breadcrumb-item">
                                                                <a href="{{url('/')}}" class="text-white-50">{{lang('Accueil')}}</a>
                                                            </li>
                                                            <li class="breadcrumb-item active">
                                                                <a href="#" class="text-white">{{lang('Notification', 'menu')}}</a>
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
                                                        <!-- Row-->
                                                        <div class="row">
                                                            <div class="col-xl-9 col-md-12 col-lg-8 loaddata">

                                                                @if(auth()->guard('customer')->user())
                                                                    @forelse( $notifications as $created_at => $notificationss)
                                                                    @php

                                                                    $today = \Carbon\Carbon::parse(now());
                                                                    $yesterday = \Carbon\Carbon::yesterday();
                                                                    $createdat = \Carbon\Carbon::parse($created_at);
                                                                    $dateformat1 = \Carbon\Carbon::parse($created_at)->format('Y-m-d');

                                                                    @endphp
                                                                    @if($createdat->format('Y-m-d') ==  $today->format('Y-m-d'))

                                                                        <div class="badge badge-light-1 p-2 px-3 fs-16 ms-0 mt-0 mb-3">{{lang('Today')}}</div>

                                                                    @elseif($createdat->format('Y-m-d') ==  $yesterday->format('Y-m-d'))

                                                                        <div class="badge badge-light-1 p-2 px-3 fs-16 ms-0 mt-3 mb-3">{{lang('Yesterday')}}</div>
                                                                    @else

                                                                        <div class="badge badge-light-1 p-2 px-3 fs-16 ms-0 mt-3 mb-3">{{$createdat->timezone(Auth::guard('customer')->user()->timezone)->format(setting('date_format'))}}</div>
                                                                    @endif

                                                                    @foreach ($notificationss as $notification)
                                                                        @if($notification->data['status'] == 'New')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}">
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                    <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-burnt-orange badge-notify br-13 ms-2 mt-0"> {{lang('New Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your new ticket has been created', 'notification')}}  <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                    <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-burnt-orange badge-notify br-13 ms-2 mt-0"> {{lang('New Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your new ticket has been created', 'notification')}}  <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif

                                                                        @endif

                                                                        @if($notification->data['status'] == 'Closed')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-danger badge-notify br-13 ms-2 mt-0"> {{lang('Closed Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket has been closed', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-danger badge-notify br-13 ms-2 mt-0"> {{lang('Closed Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket has been closed', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif

                                                                        @endif

                                                                        @if($notification->data['status'] == 'On-Hold')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-warning badge-notify br-13 ms-2 mt-0"> {{lang('On-Hold Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket status is On-Hold', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-warning badge-notify br-13 ms-2 mt-0"> {{lang('On-Hold Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket status is On-Hold', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}"  data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif
                                                                        @endif
                                                                        @if($notification->data['status'] == 'overdue')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-danger-light badge-notify br-13 ms-2 mt-0"> {{lang('Overdue Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket status is overdue', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-danger-light badge-notify br-13 ms-2 mt-0"> {{lang('Overdue Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket status is overdue', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif
                                                                        @endif
                                                                        @if($notification->data['status'] == 'Re-Open')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-teal badge-notify br-13 ms-2 mt-0"> {{lang('Re-Open Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket has been Reopened', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-teal badge-notify br-13 ms-2 mt-0"> {{lang('Re-Open Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('Your ticket has been Reopened', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif
                                                                        @endif
                                                                        @if($notification->data['status'] == 'Inprogress')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-info badge-notify br-13 ms-2 mt-0"> {{lang('Inprogress Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('You got a new reply on this ticket', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else

                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="me-4  alt-notify" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path fill="#ffce6d" d="M18,13.18463V10c0-3.31372-2.68628-6-6-6s-6,2.68628-6,6v3.18463C4.83832,13.59863,4.00146,14.69641,4,16v2c0,0.00037,0,0.00073,0,0.00116C4.00031,18.5531,4.44806,19.00031,5,19h14c0.00037,0,0.00073,0,0.00116,0C19.5531,18.99969,20.00031,18.55194,20,18v-2C19.99854,14.69641,19.16168,13.59863,18,13.18463z"/><path fill="#ffae0c" d="M8.14233 19c.4472 1.72119 1.99689 2.99817 3.85767 3 1.86078-.00183 3.41046-1.27881 3.85767-3H8.14233zM12 4c.34149 0 .67413.03516 1 .08997V3c0-.55231-.44769-1-1-1s-1 .44769-1 1v1.08997C11.32587 4.03516 11.65851 4 12 4z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{Str::limit($notification->data['title'], '50', '...')}} <span class="badge badge-info badge-notify br-13 ms-2 mt-0"> {{lang('Inprogress Ticket', 'notification')}} </span></span>
                                                                                        <p class="fs-13 mb-0">{{lang('You got a new reply on this ticket', 'notification')}} <strong>{{ $notification->data['ticket_id'] }}</strong> <a  href="{{$notification->data['link']}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @endif
                                                                        @endif
                                                                        @if ($notification->data['status'] == 'mail')

                                                                            @if($notification->read_at != null)

                                                                            <div class="card mb-3 notify-read">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                <div class="">
                                                                                    <svg class="alt-notify mail me-4" xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#8ac0c3" d="M19,20H5a3.00328,3.00328,0,0,1-3-3V7A3.00328,3.00328,0,0,1,5,4H19a3.00328,3.00328,0,0,1,3,3V17A3.00328,3.00328,0,0,1,19,20Z"/><path fill="#3c969c" d="M22,7a3.00328,3.00328,0,0,0-3-3H5A3.00328,3.00328,0,0,0,2,7V8.061l9.47852,5.79248a1.00149,1.00149,0,0,0,1.043,0L22,8.061Z"/></svg>
                                                                                </div>
                                                                                <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{$notification->data['mailsubject']}}<span class="badge badge-success badge-notify br-13 ms-2 mt-0" style="background-color: {{$notification->data['mailsendtagcolor']}}">{{$notification->data['mailsendtag']}}</span></span>
                                                                                        <p class="fs-13 mb-0 pe-6">{{Str::limit($notification->data['mailtext'], '400', '...')}}<a  href="{{route('customer.notiication.view', $notification->id)}}" data-id="{{$notification->id}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>
                                                                            </div>
                                                                            @else
                                                                            <div class="card mb-3">
                                                                                <a href="javascript:" class="ticketnotetrash notifydeletespruko" data-id="{{$notification->id}}" >
                                                                                    <i class="fe fe-trash-2" data-id="{{$notification->id}}"></i>
                                                                                </a>
                                                                                <div class="d-flex p-4 border-bottom-0">
                                                                                    <div class="">
                                                                                        <svg class="alt-notify mail me-4" xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#8ac0c3" d="M19,20H5a3.00328,3.00328,0,0,1-3-3V7A3.00328,3.00328,0,0,1,5,4H19a3.00328,3.00328,0,0,1,3,3V17A3.00328,3.00328,0,0,1,19,20Z"/><path fill="#3c969c" d="M22,7a3.00328,3.00328,0,0,0-3-3H5A3.00328,3.00328,0,0,0,2,7V8.061l9.47852,5.79248a1.00149,1.00149,0,0,0,1.043,0L22,8.061Z"/></svg>
                                                                                    </div>
                                                                                    <div class="mt-0 text-start">
                                                                                        <span class="fs-16 font-weight-semibold">{{$notification->data['mailsubject']}} <span class="badge badge-success badge-notify br-13 ms-2 mt-0" style="background-color: {{$notification->data['mailsendtagcolor']}}">{{$notification->data['mailsendtag']}}</span></span>
                                                                                        <p class="fs-13 mb-0 pe-6">{{Str::limit($notification->data['mailtext'], '400', '...')}}<a  href="{{route('customer.notiication.view', $notification->id)}}" data-id="{{$notification->id}}" class="ms-3 text-blue mark-as-read">{{lang('View')}}</a></p>
                                                                                    </div>
                                                                                </div>
                                                                                <span class="text-end mb-2 me-3 fs-12 text-muted">
                                                                                    {{$notification->created_at->timezone(Auth::guard('customer')->user()->timezone)->format(setting('time_format'))}}
                                                                                </span>

                                                                            </div>
                                                                            @endif

                                                                        @endif
                                                                        {{-- @if($loop->last)
                                                                            <a href="#" id="mark-all">
                                                                                Mark all as read
                                                                            </a>
                                                                        @endif --}}
                                                                @endforeach
                                                                @empty

                                                                <div class="card">
                                                                    <div class="card-body h-100 w-100">
                                                                        <div class="main-content text-center">
                                                                            <div class="notification-icon-container p-4">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M21.9,21.1l-19-19C2.7,2,2.4,2,2.2,2.1C2,2.3,2,2.7,2.1,2.9l4.5,4.5C6.2,8.2,6,9.1,6,10v4.1c-1.1,0.2-2,1.2-2,2.4v2C4,18.8,4.2,19,4.5,19h3.7c0.5,1.7,2,3,3.8,3c1.8,0,3.4-1.3,3.8-3h2.5l2.9,2.9c0.1,0.1,0.2,0.1,0.4,0.1c0.1,0,0.3-0.1,0.4-0.1C22,21.7,22,21.3,21.9,21.1z M7,10c0-0.7,0.1-1.3,0.4-1.9l5.9,5.9H7V10z M13,20.8c-1.6,0.5-3.3-0.3-3.8-1.8h5.6C14.5,19.9,13.8,20.5,13,20.8z M5,18v-1.5C5,15.7,5.7,15,6.5,15h7.8l3,3H5z M9.6,5.6c1.9-1,4.3-0.7,5.9,0.9C16.5,7.4,17,8.7,17,10v3.3c0,0.3,0.2,0.5,0.5,0.5h0c0.3,0,0.5-0.2,0.5-0.5V10c0-3.1-2.4-5.7-5.5-6V2.5C12.5,2.2,12.3,2,12,2s-0.5,0.2-0.5,0.5V4c-0.8,0.1-1.6,0.3-2.3,0.7c0,0,0,0,0,0C8.9,4.8,8.8,5.1,9,5.4C9.1,5.6,9.4,5.7,9.6,5.6z"/></svg>
                                                                            </div>
                                                                            <h4 class="mb-1">{{lang('There are no new notifications to display', 'notification')}}</h4>
                                                                            <p class="text-muted">{{lang('There are no notifications. We will notify you when the new notification arrives.', 'notification')}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforelse

                                                                {{  auth()->guard('customer')->user()->notifications()->paginate(10)->links('admin.notificationpagination') }}
                                                                @endif
                                                            </div>

                                                            <div class="col-xl-3 col-md-12 col-lg-4 mt-5 mt-lg-0">
                                                                <div class="card">
                                                                    <div class="card-header border-0">
                                                                        <h4 class="card-title">{{lang('Filter Notifications', 'notification')}}</h4>
                                                                    </div>
                                                                    <form id="notifysearch" method="POST">
                                                                        <div class="card-body pb-0">
                                                                            <div class="form-group">
                                                                                <input type="search" class="form-control notifysearch" name="notifysearch" id="" placeholder="Rechercher…">
                                                                                <i class="fe fe-search search-element"></i>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <form  method="POST">
                                                                        <div class="card-body">
                                                                            <div class="form-group">
                                                                                <h6 class="mb-4">{{lang('Sort by Status', 'notification')}}</h6>
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" class="notifystatus form-check-input" name="notifystatus[]"  value="New" id="flexCheckDefaultq">
                                                                                    <label for="flexCheckDefaultq" class=" ms-2">{{lang('Nouvelle Arlette VBG')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]"  value="Closed" id="flexCheckDefaultw">
                                                                                    <label for="flexCheckDefaultw" class=" ms-2">{{lang('Arlette VBG fermées')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]"  value="On-Hold" id="flexCheckDefaulte">
                                                                                    <label for="flexCheckDefaulte" class=" ms-2">{{lang('Alertes VBG en attente')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]"  value="overdue" id="flexCheckDefaultr">
                                                                                    <label for="flexCheckDefaultr" class=" ms-2">{{lang('Alerte VBG non résolues')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]"  value="Re-Open" id="flexCheckDefaultt">
                                                                                    <label for="flexCheckDefaultt" class=" ms-2">{{lang('Re-Open Tickets', 'notification')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]"  value="Inprogress" id="flexCheckDefaultf">
                                                                                    <label for="flexCheckDefaultf" class=" ms-2">{{lang('Inprogress Tickets', 'notification')}}</label>
                                                                                </div>
                                                                                <div class=" form-check">
                                                                                    <input type="checkbox" class="form-check-input notifystatus" name="notifystatus[]" value="mail" id="flexCheckDefaultqq">
                                                                                    <label for="flexCheckDefaultqq" class=" ms-2">{{lang('Custom Notifications', 'notification')}}</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Row-->
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!--Section-->

@endsection

@section('scripts')

        <!--Showmore Js-->
        <script src="{{asset('assets/js/jquery.showmore.js')}}"></script>

        <!--Showmore Custom Js-->
        <script type="text/javascript">
            "use strict";

            let SITEURL = '{{url('/')}}';

            // Csrf Field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

            let readMore = document.querySelectorAll('.readmores')
            readMore.forEach(( element, index)=>{
                if(element.clientHeight <= 120)    {
                    element.children[0].children[0].classList.add('end')
                }
                else{
                    element.children[0].children[0].classList.add('readMore')
                }
            })
            $(`.readMore`).showmore({
                closedHeight: 60,
                buttonTextMore: 'Read more',
                buttonTextLess: 'Close',
                buttonCssClass: 'showmore-button',
                animationSpeed: 0.5
            });


            $('.notifystatus').on('click', function(e){

                    let statusnotify = [];

                    $("input[name='notifystatus[]']:checked").each(function(){
                        statusnotify.push(this.value);
                    });
                    $.ajax({
                        url: SITEURL + "/customer/notifystatus",
                        method:"post",
                        data:{
                            statusnotify:statusnotify,
                            _token: "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {   $('.loaddata').empty();
                            $('.loaddata').append(data.html)

                        },
                        error:function(data){
                            console.log(data);
                        }
                });
            })

            $('.notifysearch').on('keyup focusin', function(e){

                let notifysearch = e.target.value;

                $.ajax({
                    url: SITEURL + "/customer/notifysearch",
                    method:"post",
                    data:{
                        notifysearch:notifysearch,
                        _token: "{{ csrf_token() }}",
                    },
                    success:function(data)
                    {
                        $('.loaddata').empty();
                        $('.loaddata').append(data.html)

                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            })

            $('.notifydeletespruko').on('click', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                console.log(id);
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
                            url:"{{ url('customer/notify/delete')}}",
                            method:"post",
                            data:{id:id},
                            success:function(data)
                            {
                                location.reload();
                                toastr.success(data.success);

                            },
                            error:function(data){

                            }
                        });
                    }
                });
            });


        </script>


@endsection
