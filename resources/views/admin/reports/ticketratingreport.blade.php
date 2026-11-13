@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL Data table css -->
<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}"
	rel="stylesheet" />
<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
<link href="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.css')}}" rel="stylesheet" />

@endsection

@section('content')

        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h1 class="page-title"><span
                        class="font-weight-normal text-muted ms-2">{{lang('Rapport')}}</span></h1>
            </div>
        </div>
        <!--End Page header-->

    	<div class="col-xl-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('Employee Reports')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered border-bottom text-nowrap w-100" id="reports">
                            <thead>
                                <tr>
                                    <th width="10">{{lang('Sl.No')}}</th>
                                    <th>{{lang('Nom')}}</th>
                                    <th>{{lang('Rating')}}</th>
                                    <th>{{lang('Overall Rating')}}</th>
                                    <th>{{lang('Total Answered Ticket')}}</th>
                                    <th>{{lang('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($users as $user)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        <div class="media mt-0 align-items-center">
                                            @if ($user->image == null)

                                            <img src="{{asset('uploads/profile/user-profile.png')}}"
                                                class="avatar avatar-md rounded-circle me-3 my-auto" alt="">

                                            @else
                                            <img src="{{asset('uploads/profile/'.$user->image)}}"
                                                class="avatar avatar-md rounded-circle me-3 my-auto" alt="">

                                            @endif

                                            <div class="media-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="mt-0">
                                                        {{-- <h5 class="mb-0 fs-13 font-weight-sembold text-dark">Samantha
                                                            Melon</h5>
                                                        <p class="mb-0 fs-11 text-muted">Agent 1</p> --}}
                                                        @if(!empty($user->getRoleNames()[0]))
                                                        <h5 class="mb-0 fs-13 font-weight-sembold text-dark">{{$user->name}}
                                                        </h5>
                                                        <small class="fs-12 text-muted"><span
                                                                class="font-weight-normal1"><b>({{$user->getRoleNames()[0]}})</b></span></small>
                                                        @else
                                                        <h5 class="mb-0 fs-13 font-weight-sembold text-dark">{{$user->name}}
                                                        </h5>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="user-pic text-center ">
                                            @if ($user->image == null)

                                            <img src="{{asset('uploads/profile/user-profile.png')}}"
                                                class="avatar avatar-xxl brround" alt="">

                                            @else
                                            <img src="{{asset('uploads/profile/'.$user->image)}}"
                                                class="avatar avatar-xxl brround" alt="">

                                            @endif
                                            @if(!empty($user->getRoleNames()[0]))
                                            <div class="h5 mb-0">{{$user->name}}</div>
                                            <small class="fs-12 text-muted"><span
                                                    class="font-weight-normal1"><b>({{$user->getRoleNames()[0]}})</b></span></small>
                                            @else
                                            <div class="h5 mb-0">{{$user->name}}</div><small
                                                class="fs-12 text-muted"></small>
                                            @endif
                                        </div> --}}
                                    </td>
                                    <td>
                                        @php

                                        $avgrating1 = App\Models\Employeerating::where('user_id',$user->id)->where('rating',
                                        '1')->count();
                                        $avgrating2 = App\Models\Employeerating::where('user_id',$user->id)->where('rating',
                                        '2')->count();
                                        $avgrating3 = App\Models\Employeerating::where('user_id',$user->id)->where('rating',
                                        '3')->count();
                                        $avgrating4 = App\Models\Employeerating::where('user_id',$user->id)->where('rating',
                                        '4')->count();
                                        $avgrating5 = App\Models\Employeerating::where('user_id',$user->id)->where('rating',
                                        '5')->count();

                                        $avgr = ((5*$avgrating5) + (4*$avgrating4) + (3*$avgrating3) + (2*$avgrating2) +
                                        (1*$avgrating1));
                                        $avggr = ($avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5);

                                        if($avggr == 0){
                                        $avggr = 1;
                                        $avg = $avgr/$avggr;
                                        }else{
                                        $avg = $avgr/$avggr;
                                        }

                                        $rating = $avg;
                                        @endphp


                                        <div class="allemployeerating pt-1" data-rating="{{$rating}}"></div>

                                    </td>
                                    <td>
                                        @php
                                        $overallcount = App\Models\Employeerating::where('user_id',$user->id)->count();



                                        @endphp

                                        {{$overallcount}}
                                    </td>
                                    @php
                                    $comenttickets = $user->comments()->where('user_id', '!=', null)->distinct()->get();

                                    $comentticketss =
                                    App\Models\Ticket\Ticket::leftJoin('comments','comments.ticket_id','tickets.id')
                                    ->where('comments.user_id', $user->id)->distinct('comments.ticket_id')->count();
                                    @endphp
                                    {{-- @foreach($comentticketss as $cc) --}}
                                    {{-- @php $comentticket = App\Models\Ticket\Ticket::where('id',
                                    $cc->ticket_id)->count(); @endphp --}}
                                    <td>

                                        {{$comentticketss}}
                                    </td>
                                    {{-- @endforeach --}}

                                    {{-- @forelse ($comentticket as $comenttickets)
                                    <td>


                                        {{$comenttickets->ticket()->where('id', $comenttickets->ticket_id)->count()}}
                                    </td>

                                    @empty
                                    <td>0</td>
                                    @endforelse --}}
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{route('admin.reports.employeedetails', $user->id)}}"
                                                class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{lang('View')}}"><i class="feather feather-eye text-primary"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


@endsection


@section('scripts')

<!-- INTERNAL Apexchart js-->
<script src="{{gn_asset('assets/plugins/apexchart/apexcharts.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
<script src="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

<script type="text/javascript">
	"use strict";


    $(".allemployeerating").starRating({
				readOnly: true,
				starSize: 25,
				emptyColor  :  '#ffffff',
				activeColor :  '#F2B827',
				strokeColor :  '#F2B827',
				strokeWidth :  15,
				useGradient : false
			});

			// Datatable
			// $('#reports').DataTable({
			// 	responsive: true,
			// 	language: {
			// 		searchPlaceholder: search,
			// 		scrollX: "100%",
			// 		sSearch: '',
			// 	},
			// 	order:[],
			// 	// columnDefs: [
			// 	// 	{ "orderable": false, "targets":[ 0,1,4] }
			// 	// ],
			// });

            let prev = {!! json_encode(lang("Previous")) !!};
            let next = {!! json_encode(lang("Next")) !!};
            let nodata = {!! json_encode(lang("No data available in table")) !!};
            let noentries = {!! json_encode(lang("No entries to show")) !!};
            let showing = {!! json_encode(lang("showing page")) !!};
            let ofval = {!! json_encode(lang("of")) !!};
            let maxRecordfilter = {!! json_encode(lang("- filtered from ")) !!};
            let maxRecords = {!! json_encode(lang("records")) !!};
            let entries = {!! json_encode(lang("entries")) !!};
            let show = {!! json_encode(lang("Show")) !!};
            let search = {!! json_encode(lang("Search...")) !!};
            // Datatable
            $('#reports').dataTable({
                language: {
                    searchPlaceholder: search,
                    scrollX: "100%",
                    sSearch: '',
                    paginate: {
                    previous: prev,
                    next: next
                    },
                    emptyTable : nodata,
                    infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                    info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                    infoEmpty: noentries,
                    lengthMenu: `${show} _MENU_ ${entries} `,
                },
                order:[],
                columnDefs: [
                    { "orderable": false, "targets":[ 0,1,4] }
                ],
            });

			$('.form-select').select2({
				minimumResultsForSearch: Infinity,
				width: '100%'
			});

</script>

@endsection
