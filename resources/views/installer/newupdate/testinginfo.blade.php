@extends('layouts.updatemaster')

@section('title')
    {{ trans('Testing of Info') }}
@endsection


@section('container')
        <form method="POST" action="{{url('admin/licenseinfoenter/')}}" class="tabs-wrap">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <input type="text" class="form-control @error('envato_id') is-invalid @enderror" name="envato_id">
                @error('envato_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ lang($message) }}</strong>
                    </span>
                @enderror
                <div class="buttons">
                    <button class="button" type="submit" id="buttonfinal" >
                        {{ lang('Submit Purchase Code') }}
                    </button>
                </div>
            </div>
        </form>

@endsection
@section('scripts')
    <script src="{{gn_asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
    <script src="{{gn_asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    @if (Session::has('error'))
    <script>
        toastr.error("{!! Session::get('error') !!}");
    </script>
    @elseif(Session::has('success'))
    <script>
        toastr.success("{!! Session::get('success') !!}");
    </script>
    @elseif(Session::has('info'))
    <script>
        toastr.info("{!! Session::get('info') !!}");
    </script>
    @elseif(Session::has('warning'))
    <script>
        toastr.warning("{!! Session::get('warning') !!}");
    </script>
    @endif
@endsection
