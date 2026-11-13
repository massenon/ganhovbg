
<table class="table table-bordered border-bottom text-nowraps w-100 ticketdeleterow" id="supportticket-dashe">
    <thead>
        <tr >
            <th  class="wpx-40 text-center">{{lang('Sl.No')}}</th>
            @can('Ticket Delete')

            <th class="wpx-40 text-center">
                <input type="checkbox"  id="customCheckAll">
                <label  for="customCheckAll"></label>
            </th>
            @endcan
            @cannot('Ticket Delete')

            <th class="wpx-40 text-center">
                <input type="checkbox"  id="customCheckAll" disabled>
                <label  for="customCheckAll"></label>
            </th>
            @endcannot

            <th >{{lang('Détails Arlette')}}</th>
            <th>{{lang('Victime')}}</th>
            <th >{{lang('Status')}}</th>
            <th >{{lang('Assign To')}}</th>
            <th >{{lang('Actions')}}</th>

        </tr>
    </thead>
    <tbody id="refresh">
            @php $i = 1 @endphp
        @foreach ($alltickets as $tickets)
        @if($tickets->myassignuser_id == null && $tickets->selfassignuser_id == null)

        <tr {{$tickets->replystatus == 'Replied'? 'class=bg-success-transparent': ''}}>
            <td class="wpx-40 text-center">
                {{$i++}}
            </td>
            <td class="wpx-40 text-center">
                @if(Auth::user()->can('Ticket Delete'))
                    <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$tickets->id}}" />
                @else
                    <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{$tickets->id}}" disabled />
                @endif
            </td>
            <td class="overflow-hidden ticket-details">
                <div class="d-flex align-items-center">
                    <div class="">
                        @if($tickets->ticketnote->isEmpty())
                            @if($tickets->overduestatus != null)

                            <div class="ribbon ribbon-top-right1 text-danger">
                                <span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
                            </div>

                            @endif
                        @else

                            <div class="ribbon ribbon-top-right text-warning">
                                <span class="bg-warning text-white">{{lang('Note')}}</span>
                            </div>

                            @if($tickets->overduestatus != null)
                            <div class="ribbon ribbon-top-right1 text-danger">
                                <span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
                            </div>
                            @endif

                        @endif

                        <a href="{{url('admin/ticket-view/' . $tickets->ticket_id)}}" class="fs-14 d-block font-weight-semibold">{{$tickets->subject}}</a>

                        <ul class="fs-13 font-weight-normal d-flex custom-ul">
                            <li class="pe-2 text-muted">#{{$tickets->ticket_id}}</span>
                            <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i> {{$tickets->created_at->timezone(Auth::user()->timezone)->translatedFormat(setting('date_format'))}}</li>

                            @if($tickets->priority != null)
                                @if($tickets->priority == "Low")
                                    <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

                                @elseif($tickets->priority == "High")
                                    <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

                                @elseif($tickets->priority == "Critical")
                                    <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>

                                @else
                                    <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priorité')}}">{{lang($tickets->priority)}}</li>
                                @endif
                            @else
                                ~
                            @endif

                            @if($tickets->category_id != null)
                                @if($tickets->category != null)

                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Catégorie')}}"><i class="fe fe-grid me-1 fs-14" ></i>{{Str::limit($tickets->category->name, '40')}}</li>

                                @else

                                ~
                                @endif
                            @else

                                ~
                            @endif

                            @if($tickets->last_reply == null)
                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$tickets->created_at->diffForHumans()}}</li>

                            @else
                            <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{$tickets->last_reply->diffForHumans()}}</li>

                            @endif

                            @if($tickets->purchasecodesupport != null)
                            @if($tickets->purchasecodesupport == 'Supported')

                            <li class="px-2 text-success font-weight-semibold">{{lang('Assistance active')}}</li>
                            @endif
                            @if($tickets->purchasecodesupport == 'Expired')

                            <li class="px-2 text-danger-dark font-weight-semibold">{{lang('Assistance expirée')}}</li>
                            @endif
                            @endif

                        </ul>
                    </div>
                </div>
            </td>
            <td>
                {{$tickets->cust->username}} ({{ lang($tickets->cust->userType) }})
            </td>
            <td>
                @if($tickets->status == "New")

                <span class="badge badge-burnt-orange">{{lang($tickets->status)}}</span>

                @elseif($tickets->status == "Re-Open")

                <span class="badge badge-teal">{{lang($tickets->status)}}</span>

                @elseif($tickets->status == "Inprogress")

                <span class="badge badge-info">{{lang($tickets->status)}}</span>

                @elseif($tickets->status == "On-Hold")

                <span class="badge badge-warning">{{lang($tickets->status)}}</span>

                @else

                <span class="badge badge-danger">{{lang($tickets->status)}}</span>

                @endif
            </td>
            <td>
                @if(Auth::user()->can('Ticket Assign'))
                    @if($tickets->status == 'Suspend' || $tickets->status == 'Closed')
                        <div class="btn-group">
                            @if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)

                            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" disabled>{{lang('Multi Assign')}} <span class="caret"></span></button>
                            <button data-id="{{$tickets->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
                            @elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)

                            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{$tickets->selfassign->name}} (self) <span class="caret"></span></button>
                            <button data-id="{{$tickets->id}}" class="btn btn-outline-primary" id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></button>
                            @else

                            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"  disabled>{{lang('Attribuer')}}<span class="caret"></span></button>
                            @endif

                        </div>
                    @else
                        @if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id == null)

                            <div class="btn-group">
                                <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
                                    <li>
                                        <a href="javascript:void(0);" id="selfassigid" data-id="{{$tickets->id}}">{{lang('Self Assign')}}</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-id="{{$tickets->id}}" id="assigned">
                                        {{lang('Attribuer à un prestataire')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="btn-group">
                                @if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)
                                    @if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
                                    <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
                                    @else
                                    <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Multi Assign')}} <span class="caret"></span></button>
                                    <a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
                                    @endif

                                @elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)
                                @if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
                                <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
                                @else
                                <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{$tickets->selfassign->name}} (self) <span class="caret"></span></button>
                                <a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-outline-primary btn-sm" id="btnremove" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{lang('Unassign')}}" aria-label="Retirer l’attribution"><i class="fe fe-x"></i></a>
                                @endif
                                @else

                                <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">{{lang('Attribuer')}} <span class="caret"></span></button>
                                @endif

                                <ul class="dropdown-menu" role="menu">
                                    <li class="dropdown-plus-title">{{lang('Attribuer')}} <b aria-hidden="true" class="fa fa-angle-up"></b></li>
                                    <li>
                                        <a href="javascript:void(0);" id="selfassigid" data-id="{{$tickets->id}}">{{lang('Self Assign')}}</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-id="{{$tickets->id}}" id="assigned">
                                        {{lang('Attribuer à un prestataire')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        @endif
                    @endif
                @else
                    ~
                @endif
            </td>
            <td>
                @if(Auth::user()->can('Ticket Edit'))

                <a href="{{url('admin/ticket-view/' . $tickets->ticket_id)}}" class="btn btn-sm action-btns edit-testimonial"><i class="feather feather-eye text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Modifier')}}"></i></a>
                @else
                    ~
                @endif
                @if(Auth::user()->can('Ticket Delete'))
                <a href="javascript:void(0)" data-id="{{$tickets->id}}" class="btn btn-sm action-btns" id="show-delete" ><i class="feather feather-trash-2 text-danger" data-id="{{$tickets->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Supprimer')}}"></i></a>
                @else
                ~
                @endif
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

<script>

// $('#supportticket-dashe').DataTable({

//     language: {
//         searchPlaceholder: search,
//         sSearch: '',

//     },
//     order:[],
//     columnDefs: [
//         { "orderable": false, "targets":[ 0,1,6] }
//     ],
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
$('#supportticket-dashe').dataTable({
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

$('#customCheckAll').prop('checked', false);
$('.checkall').on('click', function(){
    if($('.checkall:checked').length == $('.checkall').length){
        $('#customCheckAll').prop('checked', true);
    }else{
        $('#customCheckAll').prop('checked', false);
    }
});

// Checkbox checkall
$('#customCheckAll').on('click', function() {
    $('.checkall').prop('checked', this.checked);
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
}); // __________Popover
</script>
