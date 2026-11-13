@extends('layouts.updatemaster')

@section('title')
    {{ trans('Application Updated') }}
@endsection


@section('container')

    <div class="row">
        <div class="buttons">
            <p><strong>Your Application has been updated successfully</strong></p>
            <strong>Your Application Data is seeding please wait.. few seconds</strong>
            <h3 class="countdown mb-0"></h3>
            <a href="{{route('home')}}" class="button" type="submit" id="returnbutton">{{ trans('Return to your home') }}</a>
        </div>
    </div>

@endsection
@section('scripts')

<script src="{{gn_asset('assets/plugins/jquery/jquery.min.js')}}"></script>

<script>
    var timeleft = 90;
    var downloadTimer = setInterval(function() {
        timeleft -= 1;
        $('#returnbutton').hide();
        $('.countdown').html(`${timeleft}`);
        if(timeleft <= 0){
            clearInterval(downloadTimer);
            $('#returnbutton').show();
            $('.countdown').hide();
        }
    }, 1000);
</script>
@endsection
