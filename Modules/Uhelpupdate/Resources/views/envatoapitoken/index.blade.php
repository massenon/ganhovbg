@extends('layouts.adminmaster')
							@section('content')

                            <!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Envato')}}</span></h1>
								</div>
							</div>
							<!--End Page header-->

                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header border-0">
                                                <h4 class="card-title">{{lang('Envato Settings')}}</h4>
                                            </div>
                                            <form action="{{route('admin.envatoapitoken.storeupdate')}}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" name="enavto_id" value="{{$apidata != null ? $apidata->id : ''}}">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label  class="form-label">{{lang('Envato Personal Api Token')}}</label>
                                                        <input type="text"  class="form-control" name="envatoapi" value="{{$apidata != null ? $apidata->envatoapitoken : ''}}">
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="form-group float-end">
                                                        <input type="submit" class="btn btn-secondary" value="{{lang('Save')}}">
                                                    </div>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @endsection