@extends('layouts.usermaster')



                                @section('content')

                                <!-- Section -->
                                <section>
                                    <div class="bannerimg cover-image" data-bs-image-src="{{asset('assets/images/photos/banner1.jpg')}}">
                                        <div class="header-text mb-0">
                                            <div class="container ">
                                                <div class="row text-white">
                                                    <div class="col">
                                                        <h3 class="mb-0">{{lang('Rating')}}</h3>
                                                    </div>
                                                    <div class="col col-auto">
                                                        <ol class="breadcrumb text-center">
                                                            <li class="breadcrumb-item">
                                                                <a href="#" class="text-white-50">{{lang('Accueil')}}</a>
                                                            </li>
                                                            <li class="breadcrumb-item active">
                                                                <a href="#" class="text-white">{{lang('Rating')}}</a>
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
                                     <!--Page header-->
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6 col-sm-12 m-auto">
                                            <div class="row row-sm">
                                                <div class="col-md-12 m-auto text-center">
                                                    <div class="page-header d-block">
                                                        <div class="page-leftheader">
                                                            <h3 class="text-center">{{$ticket->subject}} <strong class="fs-22">#{{$ticket->ticket_id}}</strong></h3>
                                                            {{-- <p class="text-center text-muted">{{lang('langconvert.menu.feedbackrecent')}}</p> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header d-block">
                                                    <h4 class="card-title ">{{lang('We Love Your Feedback!')}}</h4>
                                                    <span class="text-muted">{{lang('We are always looking for ways to improve and would love to know how we did recently.')}}</span>
                                                </div>
                                                <div class="border-top">
                                                    <form method="POST" action="{{url('/ticket/rating')}}">
                                                        <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                                                        @csrf
                                                        <div class="row p-5  m-auto">
                                                            <div class="card-body">
                                                                <div class="form-group mb-5">
                                                                    <label class="ps-1 me-5 text-center">
                                                                        <input type="radio" class="ratingticket" name="ratingticket" value="1">
                                                                        <img src="{{asset('assets/images/rattings/rating1.png')}}" alt="img" class="w-7">
                                                                        <div class=" p-0 border-top-0 bd-t-0 py-1">
                                                                            <h6 class="mb-0 text-red">{{lang('Worst')}}</h6>
                                                                        </div>
                                                                    </label>

                                                                    <label class="ps-1 me-5 text-center">
                                                                        <input type="radio" class="ratingticket" name="ratingticket" value="2">
                                                                        <img src="{{asset('assets/images/rattings/rating2.png')}}" alt="img" class="w-7">
                                                                         <div class="border-top-0 bd-t-0 py-1">
                                                                            <h6 class="mb-0 text-danger">{{lang('Poor')}}</h6>
                                                                        </div>
                                                                    </label>

                                                                    <label class="ps-1 me-5 text-center">
                                                                        <input type="radio" class="ratingticket" name="ratingticket" value="3">
                                                                        <img src="{{asset('assets/images/rattings/rating3.png')}}" alt="img" class="w-7">
                                                                         <div class=" p-0 border-top-0 bd-t-0 py-1">
                                                                            <h6 class="mb-0 text-orange">{{lang('Average')}}</h6>
                                                                        </div>
                                                                    </label>

                                                                    <label class="ps-1 me-5 text-center">
                                                                        <input type="radio" class="ratingticket" name="ratingticket" value="4">
                                                                        <img src="{{asset('assets/images/rattings/rating4.png')}}" alt="img" class="w-7">
                                                                         <div class=" p-0  border-top-0 bd-t-0 py-1">
                                                                            <h6 class="mb-0 text-yellow">{{lang('Good')}}</h6>
                                                                        </div>
                                                                    </label>

                                                                    <label class="ps-1 me-5 text-center">
                                                                        <input type="radio" class="ratingticket" name="ratingticket" value="5">
                                                                        <img src="{{asset('assets/images/rattings/rating5.png')}}" alt="img" class="w-7">
                                                                         <div class=" p-0 border-top-0 bd-t-0 py-1">
                                                                            <h6 class="mb-0 text-success">{{lang('Excellent')}}</h6>
                                                                        </div>
                                                                    </label>

                                                                </div>
                                                                <div class="form-group">
                                                                    <p>{{lang('Add a Comment about the quality of support you received (optional):')}}</p>
                                                                    <textarea name="ratingcomment" class="form-control" id="" cols="30" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="form-group float-end">
                                                                <input type="submit" value="{{lang('Submit')}}" class="btn btn-secondary">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Row-->
                                </section>
                                <!--Section-->

                                @endsection

