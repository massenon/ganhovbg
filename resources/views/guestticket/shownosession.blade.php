
@extends('layouts.usermaster')


        @section('content')

        <!-- Section -->
        <section>
            <div class="bannerimg cover-image" data-bs-image-src="{{asset('assets/images/photos/banner1.jpg')}}">
                <div class="header-text mb-0">
                    <div class="container">
                        <div class="row text-white">
                            <div class="col">
                                <h1 class="mb-0">GANXÔ Alerte VBG</h1>
                            </div>
                            <div class="col col-auto">
                                <ol class="breadcrumb text-center">
                                    <li class="breadcrumb-item">
                                        <a href="{{url('/')}}" class="text-white-50">{{lang('Accueil')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="#" class="text-white">Formulaire d'Alerte</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Section -->

        <!--Section-->
        <section>
            <div class="cover-image sptb">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 justify-content-center mx-auto text-center">

                            <div class="card">
                                <div class="card-body p-8 text-center">
                                    <div class="display-1 text-danger mb-5 font-weight-bold"><i class="fa fa-ban" aria-hidden="true"></i></div>
                                    <h6 class="mt-5 fs-20 leading-normal">{{lang('You are not authorised to view the ticket page.')}}</h6>
                                    {{-- <p class="mt-3 mb-5 fs-16"> {{lang('Please register your account with us to access more features.')}} </p> --}}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Section-->

        @endsection


