@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />


		@endsection

							@section('content')

                                <!--Page header-->
                                <div class="page-header d-lg-flex d-block">
                                    <div class="page-leftheader">
                                        <div class="page-title d-flex align-items-center">{{$ticket->ticket_id}} {{$ticket->subject}}

                                            <span class="badge fs-11 badge-pill bg-info-transparent text-info mx-2">{{lang($ticket->status)}}</span>

                                        </div>
                                        <div class="ticket-title">
                                            <ul class="fs-13 font-weight-normal custom-ul d-flex">
                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i>{{$ticket->created_at->timezone(setting('default_timezone'))->translatedFormat(setting('date_format'))}}</li>
                                                @if($ticket->priority != null)
                                                    @if($ticket->priority == "Low")
                                                        <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($ticket->priority)}}</li>

                                                    @elseif($ticket->priority == "High")
                                                        <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($ticket->priority)}}</li>

                                                    @elseif($ticket->priority == "Critical")
                                                        <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($ticket->priority)}}</li>

                                                    @else
                                                        <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($ticket->priority)}}</li>
                                                    @endif
                                                @else
                                                    ~
                                                @endif

                                                @if($ticket->category_id != null)
                                                @if($ticket->category != null)

                                                <li class="px-2 text-muted"><i class="fe fe-grid me-1 fs-14" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Catégorie')}}"></i>{{$ticket->category->name}}</li>

                                                    @else

                                                    ~
                                                    @endif
                                                @else

                                                    ~
                                                @endif

                                                @if($ticket->last_reply == null)
                                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$ticket->created_at->diffForHumans()}}</li>
                                                @else

                                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$ticket->last_reply->diffForHumans()}}</li>

                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--End Page header-->

                                <!-- row -->
                                <div class="container">
                                    <ul class="notification">
                                        @foreach($ticket->ticket_history as $tickethistory)
                                        <li>
                                            <div class="notification-time">
                                                <span class="date">{{$tickethistory->created_at->timezone(setting('default_timezone'))->translatedFormat(setting('date_format'))}}</span>
                                                <span class="time">{{$tickethistory->created_at->timezone(setting('default_timezone'))->translatedFormat(setting('time_format'))}}</span>
                                            </div>
                                            <div class="notification-icon">
                                                <a href="javascript:void(0);"></a>
                                            </div>
                                            <div class="notification-body">
                                                <div class="media mt-0">
                                                    <div class="media-body">
                                                        {!!$tickethistory->ticketactions !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>



                                    {{-- <div class="text-center mb-4">
                                        <button class="btn btn-primary">Load more</button>
                                    </div> --}}
                                </div>
                                <!-- row closed -->

							@endsection


		@section('scripts')






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





			})(jQuery);


		</script>

		@endsection
		@section('modal')



		@endsection
