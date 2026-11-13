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
                                    <h1 class="page-title">
                                        <span class="font-weight-normal text-muted ms-2">{{lang('Custom Notifications', 'menu')}}</span>
                                    </h1>
                                </div>
                                <div class="page-rightheader ms-md-auto">
                                    <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                                        <div class="d-flex">
                                            <div class="btn-list">
                                                @can('Custom Notifications Employee')

                                                <a href="{{route('mail.employee')}}" class="btn btn-success">{{lang('Compose for Employees')}}</a>
                                                @endcan
                                                @can('Custom Notifications Customer')

                                                <a href="{{route('mail.customer')}}" class="btn btn-info ">{{lang('Compose for Customers')}}</a>
                                                @endcan

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Page header-->

                            <!-- Custom Notification List -->
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h4 class="card-title">{{lang('Custom Notifications List')}}</h4>
                                        </div>
                                        <div class="card-body" >
                                            <div class="table-responsive spruko-delete">
                                                @can('Custom Notifications Delete')

                                                <button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Supprimer')}}</button>
                                                @endcan

                                                <table class="table table-bordered border-bottom text-nowrap w-100 ticketdeleterow" id="customnotifications">
                                                    <thead>
                                                        <tr>
                                                            <th>{{lang('Sl.No')}}</th>
                                                            @cannot('Custom Notifications Delete')

                                                            <th>
                                                                <input type="checkbox"  id="customCheckAll" disabled>
                                                                <label  for="customCheckAll"></label>
                                                            </th>
                                                            @endcannot
                                                            @can('Custom Notifications Delete')

                                                            <th>
                                                                <input type="checkbox"  id="customCheckAll" >
                                                                <label  for="customCheckAll"></label>
                                                            </th>
                                                            @endcan

                                                            <th >{{lang('Nom')}}</th>
                                                            <th >{{lang('User Type')}}</th>
                                                            <th >{{lang('Subject')}}</th>
                                                            <th >{{lang('Actions')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i = 1; @endphp
                                                        @foreach($customnotify as $customnotifys)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>
                                                                    @if(Auth::user()->can('Project Delete'))

                                                                    <input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$customnotifys->id}}" />
                                                                    @else

                                                                    <input type="checkbox" name="custom_checkbox[]" class="checkall" value="{{$customnotifys->id}}" disabled />
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @foreach($customnotifys->touser()->paginate(1) as $submail)
                                                                        @if($submail->touser != null)
                                                                            @if( $customnotifys->touser()->paginate(1) > '1')
                                                                                Multiple Employees
                                                                            @else
                                                                              {{Str::limit($submail->touser->name, '40') }} {{(Str::limit($submail->touser->getRoleNames()[0], '40'))}}
                                                                            @endif
                                                                        @endif
                                                                        @if($submail->tocust_id != null)
                                                                            @if($customnotifys->touser()->paginate(1) > '1')
                                                                                Multiple Customers
                                                                            @else
                                                                               {{Str::limit($submail->tocust->username, '40')}}
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    @foreach($customnotifys->touser()->get() as $submail)
                                                                        @if($submail->touser != null)
                                                                        @if($loop->first)

                                                                        Employees
                                                                        @endif
                                                                        @endif
                                                                        @if($submail->tocust_id != null)
                                                                            @if($submail->tocust->userType == 'Customer')
                                                                            @if($loop->first)

                                                                              Customer
                                                                            @endif
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td>{{Str::limit($customnotifys->mailsubject, '40')}}</td>
                                                                <td>
                                                                    <div class = "d-flex">
                                                                    @if(Auth::user()->can('Custom Notifications View'))

                                                                        <a href="javascript:void(0)" data-id="{{$customnotifys->id}}" onclick="viewc(event.target)" class="action-btns1 edit-testimonial">
                                                                            <i class="feather feather-eye text-primary" data-id="{{$customnotifys->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i>
                                                                        </a>
                                                                    @else
                                                                        ~
                                                                    @endif
                                                                    @if(Auth::user()->can('Custom Notifications Delete'))

                                                                        <a href="javascript:void(0)" data-id="{{$customnotifys->id}}" class="action-btns1" onclick="deletecustom(event.target)" >
                                                                            <i class="feather feather-trash-2 text-danger" data-id="{{$customnotifys->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
                                                                        </a>
                                                                    @else
                                                                        ~
                                                                    @endif

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
                            </div>
                            <!-- End Custom Notification List -->
                            @endsection

        @section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

        <script type="text/javascript">

			"use strict";

			(function($)  {

                // Csrf field
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Datatable
                // $('#customnotifications').DataTable({
                //     responsive: true,
				// 	language: {
				// 		searchPlaceholder: search,
				// 		scrollX: "100%",
				// 		sSearch: '',
				// 	},
				// 	order:[],
				// 	columnDefs: [
				// 		{ "orderable": false, "targets":[ 0,1,4] }
				// 	],
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
                $('#customnotifications').dataTable({
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

                // Mass Delete
                $('body').on('click', '#massdeletenotify', function () {
                    var id = [];
                    $('.checkall:checked').each(function(){
                        id.push($(this).val());
                    });
                    if(id.length > 0){
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
                                    url:"{{ route('notifyall.delete')}}",
                                    method:"post",
                                    data:{id:id},
                                    success:function(data)
                                    {
                                        toastr.success(data.success);
                                        location.reload();

                                    },
                                    error:function(data){
                                        console.log(data);
                                    }
                                });
                            }
                        });
                    }else{
                        toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
                    }
                });

                // Checkbox check all
                $('#customCheckAll').on('click', function() {
                    $('.checkall').prop('checked', this.checked);
                });

                $('.form-select').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
                $('#customCheckAll').prop('checked', false);

                $('.checkall').on('click', function(){
                    if($('.checkall:checked').length == $('.checkall').length){
                        $('#customCheckAll').prop('checked', true);
                    }else{
                        $('#customCheckAll').prop('checked', false);
                    }
                });

            })(jQuery);

            // View the custom notification
            function viewc(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/customnotification/${id}')}}`;
                $('#questionError').text('');
                $('#answerError').text('');
                $.ajax({
                    url: _url,
                    type: "GET",
                    success: function(response) {
                        if(response) {
                            $(".modal-title").text('View CustomNotification');
                            $("#mailsubject").html(response.mailsubject);
                            $("#mailtest").html(response.mailtext);
                            $('#addfaq').modal('show');

                        }
                    }
                });
            }

            // Delete the custom notification
            function deletecustom(event) {
                var id  = $(event).data("id");
                let _url = `{{url('/admin/customnotification/delete/${id}')}}`;
                let _token   = $('meta[name="csrf-token"]').attr('content');
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
                            url: _url,
                            type: 'DELETE',
                            data: {
                                _token: _token
                            },
                            success: function(response) {

                                toastr.success(response.success);
                                location.reload();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            }

        </script>

        @endsection
            @section('modal')

            @include('admin.custom-notification.model')

            @endsection

