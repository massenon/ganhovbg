<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ trans('Update Version') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-16x16.png') }}" sizes="16x16"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-32x32.png') }}" sizes="32x32"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-96x96.png') }}" sizes="96x96"/>
        <link href="{{ asset('installer/css/style.css') }}" rel="stylesheet"/>
        @yield('style')
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div class="master">
            <div class="box">
                 <div class="text-center main-logo"> <img src="{{ asset('installer/img/logo-white.png') }}" class="header-brand-img desktop-lgo" alt="logo"> </div>
                <div class="box-content">
                    <div class="header">
                        <h1 class="header__title">@yield('title')</h1>
                    </div>
                    <div class="main">
                        @if (session('message'))
                            <p class="alert text-center">
                                <strong>
                                    @if(is_array(session('message')))
                                        {{ session('message')['message'] }}
                                    @else
                                        {{ session('message') }}
                                    @endif
                                </strong>
                            </p>
                        @endif

                        @yield('container')
                    </div>
                </div>
                <div class="copyright"> Copyright © {{ now()->format('Y') }} <a href="javascript:void(0);">Uhelp</a>. Developed by <a href="javascript:void(0);"> Spruko Technologies Pvt.Ltd. </a> All rights reserved </div>
            </div>


        </div>
        @yield('scripts')

    </body>
</html>
