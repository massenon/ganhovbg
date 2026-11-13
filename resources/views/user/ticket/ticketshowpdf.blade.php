<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>#{{$showprintticket->subject}}</title>
        <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
        <link href="{{gn_asset('assets/css/style.css')}}" rel="stylesheet" />



    </head>
    <body>
        <div class="text-center fs-20 font-weight-800 pb-1">
            #{{$showprintticket->ticket_id}} - <h3>{{ $showprintticket->subject }} </h3>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="border p-4 w-100">
                    <table >
                        <tr>
                            <td><strong>{{lang('Ticket Category :')}}</strong></td>
                            @if($showprintticket->category)
                                <td>{{$showprintticket->category->name}}</td>
                            @else
                                <td>Null</td>
                            @endelse
                            @endif
                        </tr>
                        <tr style="">
                            <td><strong>{{lang('Status :')}}</strong></td>
                            <td>{{$showprintticket->status}}</td>
                        </tr>
                        <tr>
                            <td><strong>{{lang('Reply Status :')}}</strong></td>
                            <td>{{$showprintticket->replystatus}}</td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class="col-9">
                <div class="border p-4">
                    <div>
                        <h4>{{ $showprintticket->subject }} </h4>
                    </div>
                    <div>
                        <span>{!! $showprintticket->message !!}</span>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <span>Created - {{$showprintticket->created_at->timezone(setting('default_timezone'))->format('d-m-y')}} {{$showprintticket->created_at->timezone(setting('default_timezone'))->format('h:i A')}}</span>
                        </div>
                        <div class="col-6">
                            <span>By
                                @if($showprintticket->user_id == null)

                                    @if($showprintticket->cust != null)


                                        {{$showprintticket->cust->username}}
                                        <span class="fs-12 text-muted">({{$showprintticket->cust->userType}})</span>


                                    @endif
                                @endif

                                @if(setting('customer_panel_employee_protect') == 'on')
                                    {{setting('employeeprotectname')}}
                                @else
                                    @if($showprintticket->user_id != null)
                                        @if($showprintticket->users != null)


                                            {{$showprintticket->users->name}}
                                            @if(!empty($showprintticket->users->getRoleNames()[0]))
                                            <span>({{$showprintticket->users->getRoleNames()[0]}})</span>
                                            @endif

                                        @endif
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>

                    <div>
                        @foreach ($showprintticket->getMedia('ticket') as $ticketss)

                            <div>
                                <img src="{{$ticketss->getFullUrl()}}" class="br-5" alt="{{$ticketss->file_name}}">

                            </div>
                        @endforeach
                    </div>
                </div>



                @php $commentsprints = $showprintticket->comments()->latest()->get(); @endphp


                @foreach($commentsprints as $commentsprint)

                @if($commentsprint->cust_id != null)
                {{--Customer Reply status--}}

                <div class="border p-4 mt-2">
                    <!--- Image -->
                    <div>

                        @if($commentsprint->cust != null)
                            @if ($commentsprint->cust->image == null)

                            <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
                            @else

                            <img class="media-object brround avatar-lg" alt="{{$commentsprint->cust->image}}" src="{{asset('uploads/profile/'. $commentsprint->cust->image)}}">
                            @endif
                        @else

                        <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
                        @endif


                    </div>
                    <!--- username -->
                    <div>
                        @if($commentsprint->cust != null)

                        <h5 class="font-weight-semibold">
                            {{ $commentsprint->cust->username }}
                            <span class="border p-1 br-7 ms-2">
                                {{ $commentsprint->cust->userType }}
                            </span>
                        </h5>
                        @else

                        <h5 class="font-weight-semibold text-muted">~</h5>
                        @endif
                    </div>
                    <!--- comment -->
                    <div>
                        {!! $commentsprint->comment !!}
                    </div>
                    <!-- comment Images --->
                    <div>
                        @foreach ($commentsprint->getMedia('comments') as $commentss)

                        <div class="file-image-1">
                            <div class="product-image ">

                                <img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">

                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <span>{{lang('Created')}} - {{$commentsprint->created_at->timezone(setting('default_timezone'))->format('d-m-y')}}
                                {{$showprintticket->created_at->timezone(setting('default_timezone'))->format('h:i A')}}</span>
                        </div>
                        <div class="col-6">
                            <span>{{lang('Replied By')}}
                                @if($commentsprint->user_id == null)

                                    @if($commentsprint->cust != null)


                                        {{$commentsprint->cust->username}}
                                        <span class="fs-12 text-muted">({{$commentsprint->cust->userType}})</span>


                                    @endif
                                @endif

                                @if(setting('customer_panel_employee_protect') == 'on')
                                    {{setting('employeeprotectname')}}
                                @else
                                    @if($commentsprint->user_id != null)
                                        @if($commentsprint->user != null)


                                            {{$commentsprint->user->name}}
                                            @if(!empty($commentsprint->user->getRoleNames()[0]))
                                            <span>({{$commentsprint->user->getRoleNames()[0]}})</span>
                                            @endif

                                        @endif
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{--Customer Reply status end--}}
                @endif
                @if($commentsprint->user_id != null)
                    {{--Admin Reply status--}}

                        <div class="border p-4 mt-2">
                            <!--- Image -->
                            <div>
                                @if(setting('customer_panel_employee_protect') == 'on')
                                    <img src="{{asset('uploads/profile/user-profile.png')}}"
                                    class="media-object brround avatar-lg" alt="default">
                                @else
                                    @if($commentsprint->user != null)
                                        @if ($commentsprint->user->image == null)

                                        <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
                                        @else

                                        <img class="media-object brround avatar-lg" alt="{{$commentsprint->user->image}}" src="{{asset('uploads/profile/'. $commentsprint->user->image)}}">
                                        @endif
                                    @else

                                        <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg" alt="default">
                                    @endif
                                @endif


                            </div>
                            <!--- username -->
                            <div>
                                @if(setting('customer_panel_employee_protect') == 'on')
                                    <h5 class="font-weight-semibold">{{setting('employeeprotectname')}}</h5>
                                @else
                                    @if($commentsprint->user != null)
                                        <h5 class="font-weight-semibold">{{ $commentsprint->user->name }}@if(!empty($commentsprint->user->getRoleNames()[0]))<span class="border p-1 br-7 ms-2">{{ $commentsprint->user->getRoleNames()[0] }}</span>@endif</h5>
                                    @else
                                        <h5 class="font-weight-semibold text-muted">~</h5>
                                    @endif
                                @endif
                            </div>
                            <!--- comment -->
                            <div>
                                {!! $commentsprint->comment !!}
                            </div>
                            <!-- comment Images --->
                            <div>
                                @foreach ($commentsprint->getMedia('comments') as $commentss)

                                <div class="file-image-1">
                                    <div class="product-image ">

                                        <img src="{{$commentss->getFullUrl()}}" class="br-5" alt="{{$commentss->file_name}}">

                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <span>{{lang('Created')}} - {{$commentsprint->created_at->timezone(setting('default_timezone'))->format('d-m-y')}} {{$showprintticket->created_at->timezone(setting('default_timezone'))->format('h:i A')}}</span>
                                </div>
                                <div class="col-6">
                                    <span>{{lang('Replied By')}}
                                        @if($commentsprint->user_id == null)

                                            @if($commentsprint->cust != null)


                                                {{$commentsprint->cust->username}}
                                                <span class="fs-12 text-muted">({{$commentsprint->cust->userType}})</span>


                                            @endif
                                        @endif

                                        @if(setting('customer_panel_employee_protect') == 'on')
                                            {{setting('employeeprotectname')}}
                                        @else
                                            @if($commentsprint->user_id != null)
                                                @if($commentsprint->user != null)


                                                    {{$commentsprint->user->name}}
                                                    @if(!empty($commentsprint->user->getRoleNames()[0]))
                                                    <span>({{$commentsprint->user->getRoleNames()[0]}})</span>
                                                    @endif

                                                @endif
                                            @endif
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    {{--Admin Reply status end--}}

                @endif
                @endforeach
            </div>
        </div>

    </body>
</html>
