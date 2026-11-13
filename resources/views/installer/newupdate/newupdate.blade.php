@extends('layouts.updatemaster')

@section('title')
    {{ trans('Update version') }} {{$version}}
@endsection


@section('container')
        <form method="get" action="{{route('admin.thirdupdate',$version)}}" class="tabs-wrap">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">

                <div class="buttons">
                    <button class="button" type="submit" id="buttonfinal" onclick="button(this)">
                        {{ trans('Update') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </form>

@endsection
@section('scripts')

@endsection
