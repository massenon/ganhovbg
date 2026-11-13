@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL owl-carousel css-->
		<link href="{{gn_asset('assets/plugins/owl-carousel/owl-carousel.css')}}" rel="stylesheet" />

		<!-- INTERNAL jquery.autocomplete css-->
		<link href="{{gn_asset('assets/plugins/jquery.autocomplete/jquery.autocomplete.css')}}" rel="stylesheet" />

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{gn_asset('assets/plugins/summernote/summernote.css')}}">

		<!-- INTERNAL Data table css -->
		<link href="{{gn_asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />

		@endsection

			@section('content')

			<!--Page header-->
			<div class="page-header d-xl-flex d-block">
				<div class="page-leftheader">
					<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">Footer</span></h1>
				</div>
			</div>
			<!--End Page header-->

			<!-- Footer page -->
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="card ">
					<div class="card-header border-0">
						<h4 class="card-title">Texte du pied de page</h4>
					</div>
					<form method="POST" action="{{url('admin/footer/' )}}" enctype="multipart/form-data">
						@csrf
						@honeypot
						<input type="hidden" name="id" value="1">
						<div class="card-body">
							<textarea class="form-control @error('footertext') is-invalid @enderror" rows="4" name="footertext" aria-multiline="true">{{$footertext->footertext}}</textarea>
							@error('footertext')
								<span class="invalid-feedback" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror
						</div>
						<div class="card-header border-0">
							<h4 class="card-title">Texte de copyright du pied de page</h4>
						</div>

						<div class="card-body">
							<textarea class="summernote d-none @error('copyright') is-invalid @enderror" name="copyright" aria-multiline="true">{{$footertext->copyright}}</textarea>
							@error('copyright')
								<span class="invalid-feedback" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror
						</div>

						<div class="card-footer">
							<div class="form-group float-end ">
								<input type="submit" class="btn btn-secondary" value="{{lang('Sauvegarder les modifications')}}" onclick="this.disabled=true;this.form.submit();">
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- Footer page -->

			@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{gn_asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!--INTERNAL Owl-carousel js -->
		<script src="{{gn_asset('assets/plugins/owl-carousel/owl-carousel.js')}}"></script>

		<!-- INTERNAL Summernote js  -->
		<script src="{{gn_asset('assets/plugins/summernote/summernote.js')}}"></script>


		@endsection
