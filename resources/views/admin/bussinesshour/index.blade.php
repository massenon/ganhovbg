@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL Sweet-Alert css -->
<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Bussiness Hours',
                'menu')}}</span></h1>
    </div>
</div>
<!--End Page header-->

<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card">
        <form action="{{route('admin.bussinesshour.bussinesshourtitle')}}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="card-header border-0">
                <h4 class="card-title">{{lang('Bussiness Hours Title')}}</h4>
                <div class="card-options card-header-styles">
                    <small class="me-1 mt-1">{{lang('Show Section')}}</small>
                    <div class="float-end mt-0">
                        <div class="switch-toggle">
                            <a class="onoffswitch2">
                                <input type="checkbox" name="businesshoursswitch" id="businesshoursswitch"
                                    class=" toggle-class onoffswitch2-checkbox" value="on"
                                    {{setting('businesshoursswitch')=='on' ? 'checked' : '' }}>
                                <label for="businesshoursswitch" class="toggle-class onoffswitch2-label"></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('businesshourstitle') is-invalid @enderror"
                        name="businesshourstitle" value="{{setting('businesshourstitle')}}">
                    @error('businesshourstitle')

                    <span class="invalid-feedback" role="alert">
                        <strong>{{ lang($message) }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Sub-Title')}}</label>
                    <input type="text" class="form-control @error('businesshourssubtitle') is-invalid @enderror"
                        name="businesshourssubtitle" value="{{setting('businesshourssubtitle')}}">
                    @error('businesshourssubtitle')

                    <span class="invalid-feedback" role="alert">
                        <strong>{{ lang($message) }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="col-xl-6 col-sm-12 col-lg-12">
                        <div class="spfileupload">
                            <div class="row">
                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                                    <div class="form-group">
                                        <div class="@error('supporticon') is-invalid @enderror">
                                            <label class="form-label fs-16">{{lang('Upload Supporticon')}}</label>
                                            <div class="input-group file-browser">
                                                <input class="form-control " name="supporticon" type="file"
                                                    autocomplete="off">

                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than
                                                    500kb', 'filesetting')}}</i></small>
                                        </div>
                                        @error('supporticon')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                    <div class="file-image-1 ms-sm-auto sprukologoss ms-sm-auto">
                                        <div class="product-image sprukologoimages">
                                            @if(setting('supporticonimage') != null)
                                            <button class="btn ticketnotedelete border-white text-gray logosdelete">
                                                <i class="feather feather-x"></i>
                                            </button>
                                            <img src="{{asset('uploads/support/'. setting('supporticonimage'))}}"
                                                class="br-5" alt="logo">
                                            @else
                                            <img src="{{asset('assets/images/support/support.png')}}" class="br-5"
                                                alt="logo">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-footer">
                <div class="form-group float-end">
                    <button class="btn btn-secondary">{{lang('Sauvegarder les modifications')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Bussiness Hours', 'menu')}}</h4>
        </div>
        <form action="{{route('admin.bussinesshour.store')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="table-responsive table-bussiness-hours">
                    <table class="table card-table table-vcenter text-nowrap mb-0">
                        <thead>
                            <tr class="">
                                <th class="w-20 border-bottom-0 ">{{lang('Days')}}</th>
                                <th class="w-20 border-bottom-0">{{lang('Closed/Open')}}</th>
                                <th class="w-20 border-bottom-0">{{lang('Start-time')}}</th>
                                <th class="w-20 border-bottom-0">{{lang('End-time')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $timestart = ['12:00 AM','12:30 AM','1:00 AM','1:30 AM','2:00 AM','2:30 AM','3:00 AM','3:30 AM','4:00 AM','4:30 AM','5:00 AM','5:30 AM','6:00 AM','6:30 AM','7:00 AM','7:30 AM','8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM','6:30 PM','7:00 PM','7:30 PM','8:00 PM','8:30 PM','9:00 PM','9:30 PM','10:00 PM','10:30 PM','11:00 PM','11:30 PM'];
                            @endphp
                            <tr class="border-bottom-transparent">
                                <td class="">
                                    <input type="hidden" name="bussinessid1" value="1">
                                    <select name="bussiness1"
                                        class="form-control select2 select2-show-search sprukoweeks"
                                        data-placeholder="{{lang('Select Days')}}">
                                        <option label="{{lang('Select Days')}}"></option>
                                        <option value="Mon" {{$bussiness1 !=null ? $bussiness1->weeks == 'Mon' ?
                                            'selected': '' :''}}>{{lang('Mon')}}</option>
                                        <option value="Tue" {{$bussiness1 !=null ? $bussiness1->weeks == 'Tue' ?
                                            'selected': '' :''}}>{{lang('Tue')}}</option>
                                        <option value="Wed" {{$bussiness1 !=null ? $bussiness1->weeks == 'Wed' ?
                                            'selected': '' :''}}>{{lang('Wed')}}</option>
                                        <option value="Thu" {{$bussiness1 !=null ? $bussiness1->weeks == 'Thu' ?
                                            'selected': '' :''}}>{{lang('Thu')}}</option>
                                        <option value="Fri" {{$bussiness1 !=null ? $bussiness1->weeks == 'Fri' ?
                                            'selected': '' :''}}>{{lang('Fri')}}</option>
                                        <option value="Sat" {{$bussiness1 !=null ? $bussiness1->weeks == 'Sat' ?
                                            'selected': '' :''}}>{{lang('Sat')}}</option>
                                        <option value="Sun" {{$bussiness1 !=null ? $bussiness1->weeks == 'Sun' ?
                                            'selected': '' :''}}>{{lang('Sun')}}</option>
                                    </select>
                                </td>
                                <td class="">
                                    <select name="status1" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness1 !=null ? $bussiness1->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness1 !=null ? $bussiness1->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime1"
                                        class="form-control select2 select2-show-search sprukostarttime" data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness1 !=null ? $bussiness1->starttime == '24H' ? 'selected' : '' :''}}>24H</option>

                                        </optgroup>

                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness1 !=null ? $bussiness1->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach

                                    </select>
                                </td>

                                <td class="tr_clone">

                                    <select name="endtime1"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness1 !=null ? $bussiness1->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach

                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid2" value="2">
                                    <input name="bussiness2" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status2" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness2 !=null ? $bussiness2->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness2 !=null ? $bussiness2->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime2"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness2 !=null ? $bussiness2->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>

                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness2 !=null ? $bussiness2->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach

                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime2"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness2 !=null ? $bussiness2->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid3" value="3">
                                    <input name="bussiness3" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status3" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness3 !=null ? $bussiness3->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness3 !=null ? $bussiness3->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime3"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness3 !=null ? $bussiness3->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness3 !=null ? $bussiness3->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime3"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness3 !=null ? $bussiness3->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid4" value="4">
                                    <input name="bussiness4" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status4" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness4 !=null ? $bussiness4->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness4 !=null ? $bussiness4->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime4"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness4 !=null ? $bussiness4->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness4 !=null ? $bussiness4->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime4"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness4 !=null ? $bussiness4->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid5" value="5">
                                    <input name="bussiness5" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status5" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness5 !=null ? $bussiness5->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness5 !=null ? $bussiness5->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime5"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness5 !=null ? $bussiness5->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness5 !=null ? $bussiness5->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime5"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness5 !=null ? $bussiness5->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid6" value="6">
                                    <input name="bussiness6" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status6" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness6 !=null ? $bussiness6->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness6 !=null ? $bussiness6->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime6"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness6 !=null ? $bussiness6->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness6 !=null ? $bussiness6->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime6"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness6 !=null ? $bussiness6->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr_weeks">
                                    <input type="hidden" name="bussinessid7" value="7">
                                    <input name="bussiness7" class="form-control sprukoweeks" readonly>

                                </td>
                                <td class="">
                                    <select name="status7" class="form-control select2 select2-show-search sprukoopen"
                                        data-placeholder="{{lang('Select Status')}}">
                                        <option label="{{lang('Select Status')}}"></option>
                                        <option value="Opened" {{$bussiness7 !=null ? $bussiness7->status == 'Opened' ?
                                            'selected' :'' :''}}>{{lang('Opened')}}</option>
                                        <option value="Closed" {{$bussiness7 !=null ? $bussiness7->status == 'Closed' ?
                                            'selected' :'' :''}}>{{lang('Fermé')}}</option>
                                    </select>
                                </td>
                                <td class="tr_clone1">
                                    <select name="starttime7"
                                        class="form-control select2 select2-show-search sprukostarttime"
                                        data-placeholder="{{lang('Select StartTime')}}">
                                        <option label="{{lang('Select StartTime')}}"></option>
                                        <optgroup>
                                            <option value="24H" {{$bussiness7 !=null ? $bussiness7->starttime == '24H' ?
                                                'selected' : '' :''}}>24H</option>

                                        </optgroup>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness7 !=null ? $bussiness7->starttime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tr_clone">
                                    <select name="endtime7"
                                        class="form-control select2 select2-show-search sprukoendtime"
                                        data-placeholder="{{lang('Select EndTime')}}">
                                        <option label="{{lang('Select EndTime')}}"></option>
                                        @foreach($timestart as $time)
                                        <option value="{{$time}}" {{ $bussiness7 !=null ? $bussiness7->endtime == $time ? 'selected' : '' :''}}>{{$time}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group float-end">
                    <button  id="bussinesshourRest" class="btn btn-danger mx-2">{{lang('Reset')}}</button>

                    <button id="bussinesshourSubmit" disabled="disabled" type="submit" class="btn btn-primary">{{lang('Sauvegarder')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')

<script src="{{gn_asset('assets/js/select2.js')}}"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script type="text/javascript">
    "use strict";

    function bussinesshourSubmit(){
        let startStatus = 0,
         endStatus = 0,
        openEle = 0;
        document.querySelectorAll('.sprukoopen').forEach((ele, ind)=>{
            if(ele.value){
                if(ele.value === "Opened"){
                    openEle += 1;
                }
                let currentEle = ele;
                if(currentEle.closest('td').nextElementSibling.querySelector('.sprukostarttime').value){
                    startStatus += 1;
                    if(currentEle.closest('td').nextElementSibling.querySelector('.sprukostarttime').value === "24H"){
                        endStatus += 1;
                    }
                }
                if(currentEle.closest('td').nextElementSibling.nextElementSibling.querySelector('.sprukoendtime').value){
                    endStatus += 1;
                }
            }
        })
        if((openEle == startStatus) && (openEle == endStatus)){
            let subBtn = document.querySelector('#bussinesshourSubmit');
            subBtn.disabled = false;
        }
        else{
            let subBtn = document.querySelector('#bussinesshourSubmit');
            subBtn.disabled = true;
        }
    }
    bussinesshourSubmit()


    let dayListEle = document.querySelectorAll('.sprukoweeks');
            let dayListArr = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            $('.sprukoweeks').on('change', daySort);
            function daySort(){
                let startDay =  dayListEle[0].value;
                if(startDay){
                    for(let i = 0; i<= dayListArr.length; i++){
                        if(dayListArr[i] === startDay){
                            let newDayList = reorder(dayListArr, i);
                            dayListEle.forEach((element, ind) => {
                                if(ind >= 1){
                                    element.value = newDayList[ind]
                                }
                            });

                        }
                    }
                    $('.sprukoopen').val('Opened').trigger('change.select2');
                    $('.sprukostarttime').val('0').trigger('change.select2');
                    $('.sprukostarttime').prop('disabled', false);
                    $('.sprukoendtime').val('0').trigger('change.select2');
                    $('.sprukoendtime').prop('disabled', false);
                }
                bussinesshourSubmit();
            }


            function reorder(data, index) {
            return data.slice(index).concat(data.slice(0, index))
            };
            $('.sprukostarttime').on('change', function(e){

                let value = e.target.value,
                tdfind = $(this).closest('tr').find('.tr_clone');

                let find = tdfind[0];
                let selectEle = find.firstElementChild;
                if(value == '24H'){
                    $(this).closest('tr').find('.tr_clone select').val('').trigger('change');
                    selectEle.disabled = true;
                }else{
                    selectEle.disabled = false;
                }

                bussinesshourSubmit();
            });
            $('.sprukoendtime').on('change', function(e){
                bussinesshourSubmit();
            });

            $('.sprukoopen').on('change', function(e){

                let sprukovalue = e.target.value,
                    tdfind1 = $(this).closest('tr').find('.tr_clone1'),
                    tdfind2 = $(this).closest('tr').find('.tr_clone');

                    let find1 = tdfind1[0];
                    let selectEle1 = find1.firstElementChild;
                    let find2 = tdfind2[0];
                    let selectEle2 = find2.firstElementChild;

                    if(sprukovalue == 'Closed'){
                        $(this).closest('tr').find('.tr_clone select').val('').trigger('change');
                        $(this).closest('tr').find('.tr_clone1 select').val('').trigger('change');
                        selectEle1.disabled = true;
                        selectEle2.disabled = true;

                        selectEle2.value = null;
                    }else{

                        selectEle1.disabled = false;
                        selectEle2.disabled = false;
                    }
                    bussinesshourSubmit();
            });

            $(window).on('load', function(){
                let startDay =  dayListEle[0].value;
                if(startDay){
                    for(let i = 0; i<= dayListArr.length; i++){
                        if(dayListArr[i] === startDay){
                            let newDayList = reorder(dayListArr, i);
                            dayListEle.forEach((element, ind) => {
                                if(ind >= 1){
                                    element.value = newDayList[ind]
                                }
                            });

                        }
                    }
                }
                let starttimevalue = $('.sprukostarttime');
                
                $.map(starttimevalue, function( val, i ) {
                    // Do something
                    let value = $(val).val(),
                        tdfind = $(val).closest('tr').find('.tr_clone');
                    let find = tdfind[0];
                    let selectEle = find.firstElementChild;
                     if(value == '24H'){

                        selectEle.disabled = true;
                    }
                });

                let sprukoopen = $('.sprukoopen');
                $.map(sprukoopen, function( value, i ) {
                    // Do something
                    let val = $(value).val(),
                        tdfind1 = $(value).closest('tr').find('.tr_clone1'),
                        tdfind2 = $(value).closest('tr').find('.tr_clone');

                    let find1 = tdfind1[0];
                    let selectEle1 = find1.firstElementChild;
                    let find2 = tdfind2[0];
                    let selectEle2 = find2.firstElementChild;

                    if(val == 'Closed'){

                        selectEle1.disabled = true;
                        selectEle2.disabled = true;
                    }
                });

                // Logos Delete
				$('body').on('click', '.logosdelete', function(e){
					e.preventDefault();
					let id = $(this).data('id');
					let logo = $(this).val();
					swal({
						title: `{{lang('Are you sure want to remove this logo?')}}`,
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
					if (willDelete) {
							$.ajax({
								type: "post",
								url: "{{route('admin.bussinesslogodelete')}}",
								data: {
									'supporticonimage': 'supporticonimage',

								},
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

                $('body').on('click', '#bussinesshourRest', function(){
                    $('.sprukoweeks').html('')
                    dayListEle.forEach( e => {e.value = ''})
                    $('.sprukoopen').html('')
                    $('.sprukostarttime').html('')
                    $('.sprukoendtime').html('')
                })
            });

</script>


@endsection
