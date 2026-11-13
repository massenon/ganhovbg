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
            <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Rapport')}}</span></h1>
        </div>
    </div>
    <!--End Page header-->

    <!--Reports List-->
    <div class="row row-deck">
        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex border-0">
                    <h4 class="card-title">{{lang('Prestataires')}}</h4>
                    <div class="card-options">
                        <a class="btn btn-light btn-sm" href="{{ route('employee') }}">{{lang('Voir tout')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="userchart" class=""></div>
                    <div class="sales-chart pt-5 pb-3 d-flex mx-auto text-center justify-content-center ">
                        <div class="d-flex me-5"><span class="dot-label bg-success me-2 my-auto"></span>{{ lang('Active') }}
                        </div>
                        <div class="d-flex"><span class="dot-label bg-warning  me-2 my-auto"></span>{{ lang('Inactive') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex border-0">
                    <h4 class="card-title">{{lang('Victimes')}}</h4>
                    <div class="card-options">
                        <a class="btn btn-light btn-sm" href="{{ route('admin.customer') }}">{{lang('Voir tout')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="customerchart" class=""></div>
                    <div class="sales-chart pt-5 pb-3 d-flex mx-auto text-center justify-content-center ">
                        <div class="d-flex me-5"><span
                                class="dot-label bg-success me-2 my-auto"></span>{{ lang('Active') }}
                        </div>
                        <div class="d-flex"><span class="dot-label bg-warning  me-2 my-auto"></span>{{ lang('Inactive') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex border-0">
                    <h4 class="card-title">{{lang('Arletes/Cas VBG')}}</h4>
                    <div class="card-options">
                        <a class="btn btn-light btn-sm" href="{{ route('admin.alltickets') }}">{{lang('Voir tout')}}</a>
                    </div>

                </div>
                <div class="card-body">
                    <div id="ticketchart" class=""></div>
                    <div class="sales-chart pt-5 pb-3 d-md-flex mx-auto text-center justify-content-center ">
                        <div class="d-flex me-2"><span
                                class="dot-label badge-burnt-orange me-2 my-auto"></span>{{ lang('New') }}
                        </div>
                        <div class="d-flex me-2"><span
                                class="dot-label bg-info  me-2 my-auto"></span>{{ lang('Inprogress') }}
                        </div>
                        <div class="d-flex me-2"><span
                                class="dot-label bg-warning  me-2 my-auto"></span>{{ lang('On-hold') }}
                        </div>
                        <div class="d-flex me-2"><span class="dot-label bg-teal  me-2 my-auto"></span>{{ lang('Reopen') }}
                        </div>
                        <div class="d-flex me-2"><span
                                class="dot-label bg-danger  me-2 my-auto"></span>{{ lang('Closed') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header border-bottom-0">
                    <h3 class="card-title">
                        {{lang('Priorité des Arletes/Cas VBG')}}
                    </h3>
                </div>
                <div class="card-body pb-8">
                    <div id="priority" class="mx-auto apex-dount"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex border-bottom-0">
                    <h3 class="card-title">
                        {{ lang('Knowledge Base') }}
                    </h3>
                    <div class="card-options">
                        <a class="btn btn-light btn-sm"
                            href="{{ route('admin.article.index') }}">{{lang('Voir tout')}}</a>
                    </div>
                </div>
                <div class="card-body pb-8">
                    <div id="article" class="mx-auto apex-dount"></div>
                </div>
            </div>
        </div>


        <div class="col-xl-4 col-md-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex border-bottom-0">
                    <h3 class="card-title">{{lang('Évaluations des Prestataires')}}</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.ticketreports') }}"
                            class="btn btn-light btn-sm">{{lang('Voir tout')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach ($users as $user)
                            <a href="javascript:void(0);">
                                <div class="list-group-item border-0 px-0 pt-0">
                                    <div class="media mt-0 align-items-center">
                                        @if ($user->image == null)
                                            <img src="{{ asset('uploads/profile/user-profile.png') }}"
                                                class="avatar-lg rounded-circle me-3 my-auto" alt="">
                                        @else
                                            <img src="{{ asset('uploads/profile/' . $user->image) }}"
                                                class="avatar-lg rounded-circle me-3 my-auto" alt="">
                                        @endif

                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mt-0">
                                                    @if (!empty($user->getRoleNames()[0]))
                                                        <h5 class="mb-0 fs-13 font-weight-sembold text-dark">
                                                            {{ $user->name }}
                                                        </h5>
                                                        <p class="mb-0 fs-12 text-muted">{{ $user->getRoleNames()[0] }}
                                                        </p>
                                                    @else
                                                        <h5 class="mb-0 fs-13 font-weight-sembold text-dark">
                                                            {{ $user->name }}
                                                        </h5>
                                                    @endif

                                                </div>
                                                @php

                                                    $avgrating1 = App\Models\Employeerating::where('user_id', $user->id)
                                                        ->where('rating', '1')
                                                        ->count();
                                                    $avgrating2 = App\Models\Employeerating::where('user_id', $user->id)
                                                        ->where('rating', '2')
                                                        ->count();
                                                    $avgrating3 = App\Models\Employeerating::where('user_id', $user->id)
                                                        ->where('rating', '3')
                                                        ->count();
                                                    $avgrating4 = App\Models\Employeerating::where('user_id', $user->id)
                                                        ->where('rating', '4')
                                                        ->count();
                                                    $avgrating5 = App\Models\Employeerating::where('user_id', $user->id)
                                                        ->where('rating', '5')
                                                        ->count();

                                                    $avgr = 5 * $avgrating5 + 4 * $avgrating4 + 3 * $avgrating3 + 2 * $avgrating2 + 1 * $avgrating1;
                                                    $avggr = $avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5;

                                                    if ($avggr == 0) {
                                                        $avggr = 1;
                                                        $avg = $avgr / $avggr;
                                                    } else {
                                                        $avg = $avgr / $avggr;
                                                    }

                                                    $rating = $avg;
                                                @endphp



                                                <span
                                                    class="rating-stars block uhelp-rating ms-auto w-auto allemployeerating"
                                                    data-rating="{{ $rating }}">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--End Reports List-->
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
        class nodata {
            constructor(ele) {
                let div = document.createElement('div');
                div.classList.add("d-flex");
                div.classList.add("justify-content-center");
                div.classList.add("align-items-center");
                div.classList.add("flex-column");
                let img = document.createElement('img');
                img.src = `{{ asset('assets/images/no-data.png') }}`;
                img.style.width = "200px";
                img.style.height = "200px";
                img.rel = "no Data";
                let hr = document.createElement('hr');
                let title = document.createElement('div');
                title.classList.add("text-muted");
                title.classList.add("fs-20");
                title.textContent = "No Data Available";
                div.append(img, title);
                ele.append(div);
            }
        }

        // User Chart
        if (({{ $agentactivec }} !== 0) || ({{ $agentinactive }} !== 0)) {
            var userchart = {
                series: [{{ $agentactivec }}, {{ $agentinactive }}],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 0
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    color: '#6c6f9a',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    color: undefined,
                                    offsetY: 16,
                                    formatter: function(val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: false,
                                    label: '{{ lang('Total') }}',
                                    fontSize: '22px',
                                    fontWeight: 600,
                                    color: '#373d3f',
                                }

                            }
                        }
                    }
                },
                responsive: [{
                    options: {
                        legend: {
                            show: false,
                        }
                    }
                }],
                labels: ["Active", "Inactive"],
                colors: ['#0dcd94', '#fbc518'],
            };
            var chart = new ApexCharts(document.querySelector("#userchart"), userchart);
            chart.render();
        } else {
            let noData = new nodata(document.querySelector("#userchart"));
        }
        // End User Chart

        // Customer Chart
        if (({{ $customeractive }} !== 0) || ({{ $customerinactive }} !== 0)) {
            var customerchart = {
                series: [{{ $customeractive }}, {{ $customerinactive }}],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 0
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    color: '#6c6f9a',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    color: undefined,
                                    offsetY: 16,
                                    formatter: function(val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: false,
                                    label: '{{ lang('Total') }}',
                                    fontSize: '22px',
                                    fontWeight: 600,
                                    color: '#373d3f',
                                }

                            }
                        }
                    }
                },
                responsive: [{
                    options: {
                        legend: {
                            show: false,
                        }
                    }
                }],
                labels: ["Active", "Inactive"],
                colors: ['#0dcd94', '#fbc518'],
            };
            var chart = new ApexCharts(document.querySelector("#customerchart"), customerchart);
            chart.render();
        } else {
            let noData = new nodata(document.querySelector("#customerchart"));
        }
        // End Customer Chart

        // Ticket Chart
        if (({{ $newticket }} !== 0) || ({{ $inprogressticket }} !== 0) || ({{ $onholdticket }} !== 0) || (
                {{ $reopenticket }} !== 0) || ({{ $closedticket }} !== 0)) {
            var ticketchart = {
                series: [{{ $newticket }}, {{ $inprogressticket }}, {{ $onholdticket }}, {{ $reopenticket }},
                    {{ $closedticket }}
                ],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 0
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    color: '#6c6f9a',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    color: undefined,
                                    offsetY: 16,
                                    formatter: function(val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: false,
                                    label: '{{ lang('Total') }}',
                                    fontSize: '22px',
                                    fontWeight: 600,
                                    color: '#373d3f',
                                }

                            }
                        }
                    }
                },
                responsive: [{
                    options: {
                        legend: {
                            show: false,
                            position: 'bottom',
                            horizontalAlign: 'center',
                        }
                    }
                }],
                labels: ["New", "Inprogress", "On-Hold", "Re-Open", "Closed"],
                colors: ['#ff6f31', '#128af9', '#fbc518', '#17d1dc', '#f7284a'],
            };
            var chart = new ApexCharts(document.querySelector("#ticketchart"), ticketchart);
            chart.render();
        } else {
            let noData = new nodata(document.querySelector("#ticketchart"));
        }
        // End Ticket Chart

        $(".allemployeerating").starRating({
            readOnly: true,
            starSize: 25,
            emptyColor: '#ffffff',
            activeColor: '#F2B827',
            strokeColor: '#F2B827',
            strokeWidth: 15,
            useGradient: false
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

        let prev = {!! json_encode(lang('Previous')) !!};
        let next = {!! json_encode(lang('Next')) !!};
        let datanotavail = {!! json_encode(lang('No data available in table')) !!};
        let noentries = {!! json_encode(lang('No entries to show')) !!};
        let showing = {!! json_encode(lang('showing page')) !!};
        let ofval = {!! json_encode(lang('of')) !!};
        let maxRecordfilter = {!! json_encode(lang('- filtered from ')) !!};
        let maxRecords = {!! json_encode(lang('records')) !!};
        let entries = {!! json_encode(lang('entries')) !!};
        let show = {!! json_encode(lang('Show')) !!};
        let search = {!! json_encode(lang('Search...')) !!};
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
                emptyTable: datanotavail,
                infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                infoEmpty: noentries,
                lengthMenu: `${show} _MENU_ ${entries} `,
            },
            order: [],
            columnDefs: [{
                "orderable": false,
                "targets": [0, 1, 4]
            }],
        });

        $('.form-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });


        // Article Chart
        if (({{ $articlepublished }} !== 0) || ({{ $articleunpublished }} !== 0)) {
            var articleChart = {
                series: [{{ $articlepublished }}, {{ $articleunpublished }}],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false
                },

                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                },
                stroke: {
                    show: true,
                    width: 0
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '80%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    color: '#6c6f9a',
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    color: undefined,
                                    offsetY: 16,
                                    formatter: function(val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: false,
                                    label: '{{ lang('Total') }}',
                                    fontSize: '22px',
                                    fontWeight: 600,
                                    color: '#373d3f',
                                }

                            }
                        }
                    }
                },
                responsive: [{
                    options: {
                        legend: {
                            show: true,
                            position: 'bottom',
                            horizontalAlign: 'center',
                        }
                    }
                }],
                labels: ["Published", "Unpublished"],
                colors: ['#ff6f31', '#128af9'],
            };
            var chart = new ApexCharts(document.querySelector("#article"), articleChart);
            chart.render();
        } else {
            let noData = new nodata(document.querySelector("#article"));
        }
        // Article Chart

        // Priority Chart
        if (({{ $prioritylow }} != 0) || ({{ $prioritymedium }} != 0) || ({{ $priorityhigh }} != 0) || (
                {{ $prioritycritical }} != 0)) {
            var priority = {
                series: [{{ $prioritylow }}, {{ $prioritymedium }}, {{ $priorityhigh }}, {{ $prioritycritical }}],
                colors: ['#3366ff', '#01c353', '#ffad00', '#fe7f00'],
                chart: {
                    height: 300,
                    type: 'donut',
                },
                labels: ["Low", "Medium", "High", "Critical"],
                legend: {
                    show: true,
                    position: 'bottom'
                },
                hover: {
                    mode: null
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,

                        },
                        legend: {
                            show: true,
                            position: 'bottom'
                        }
                    }
                }]
            };
            var prioritychart = new ApexCharts(document.querySelector("#priority"), priority);
            prioritychart.render();
        } else {
            let noData = new nodata(document.querySelector("#priority"));
        }


        // Priority Chart
    </script>
@endsection
