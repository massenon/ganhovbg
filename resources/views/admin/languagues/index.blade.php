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
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Language List')}}</span></h1>
        </div>
    </div>
    <!--End Page header-->


    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title">{{lang('Language List')}}</h4>
                <div class="card-options">
                    @can('Languages Create')
                    <a href="{{route('admin.languages.create')}}" class="btn btn-secondary mt-sm-0 mt-2 me-3" >
                        <i class="feather feather-plus"></i> {{lang('Add Languages')}}
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive spruko-delete">
                    <table id="support-articlelists" class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-3x">{{ lang('Sl.No') }}</th>
                                <th class="tb-w-10x">{{ lang('Language Name') }}</th>
                                <th class="tb-w-3x">{{ lang('Language Code') }}</th>
                                <th class="tb-w-3x">{{ lang('Translation status') }}</th>
                                <th class="tb-w-7x">{{ lang('Create date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($languages as $language)
                                <tr class="item">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $language->languagename . ' - ' . $language->languagenativename }} @if (setting('default_lang') == $language->languagecode) ({{ lang('Default') }}) @endif</td>
                                    <td><a href="{{ route('admin.languages.translate', $language->languagecode) }}">{{ $language->languagecode }}</a></td>
                                    <td>
                                        @if ($language->translates_count != 0)
                                            <span class="badge bg-yellow text-dark">{{ $language->translates_count }}
                                                {{ lang('Translations are missing') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ lang('Translate are completed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $language->created_at->translatedFormat(setting('date_format')) }}</td>
                                    <td>
                                        <div class = "d-flex">

                                            @can('Languages Edit')
                                            <a href="{{ route('admin.languages.edit', $language->id) }}"  class="action-btns1">
                                                <i class="feather feather-edit text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i>
                                            </a>
                                            @endcan
                                            @can('Languages Translate')

                                            <a href="{{ route('admin.languages.translate', $language->languagecode) }}"  class="action-btns1">
                                                <i class="feather feather-repeat text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Translate')}}"></i>
                                            </a>
                                            @endcan
                                            @can('Languages Delete')

                                            @if (setting('default_lang') != $language->languagecode)
                                            <a href="javascript:void(0)" data-id="{{$language->id}}" class="action-btns1" id="delete-language" >
                                                <i class="feather feather-trash-2 text-danger" data-id="{{$language->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i>
                                            </a>
                                            @endif
                                            @endcan


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

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{gn_asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{gn_asset('assets/js/support/support-sidemenu.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

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

                    // Datatable
                    // 	$('#support-articlelists').dataTable({
                    // 		responsive: true,
                    // 		language: {
                    // 			searchPlaceholder: search,
                    // 			scrollX: "100%",
                    // 			sSearch: '',
                    // 		},
                    // 		order:[],
                    // 		columnDefs: [
                    // 			{ "orderable": false, "targets":[ 0,1,4] }
                    // 		],
                    // 	})
                });

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
            $('#support-articlelists').dataTable({
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

            // Variables
            var SITEURL = '{{url('')}}';

            // Delete Language
            $('body').on('click', '#delete-language', function () {

                var _id = $(this).data("id");
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
                            type: "post",
                            url: SITEURL + "/admin/languages/destroy/"+_id,
                            success: function (data) {
                                toastr.success(data.success);
                                location.reload();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        </script>

@endsection
