		<!-- Back to top -->
		<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>


		<!--Moment js-->
		<script src="{{gn_asset('assets/plugins/moment/moment.js')}}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{gn_asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


		<!-- P-scroll js-->
		<script src="{{gn_asset('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>

		<!-- Select2 js -->
		<script src="{{gn_asset('assets/plugins/select2/select2.full.min.js')}}"></script>

		<!--INTERNAL Horizontalmenu js -->
		<script src="{{gn_asset('assets/plugins/horizontal-menu/horizontal-menu.js')}}"></script>

		<!--INTERNAL Sticky js -->
		<script src="{{gn_asset('assets/plugins/sticky/sticky2.js')}}"></script>

		<!--gn:page-scripts-->@yield('scripts')<!--/gn:page-scripts-->

		<!--INTERNAL Toastr js -->
		<script src="{{gn_asset('assets/plugins/toastr/toastr.min.js')}}"></script>

		<!--INTERNAL sweetalert js -->
		<script src="{{gn_asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


		<script type="text/javascript">

		    "use strict";

			// Custom js
			@php echo customcssjs('CUSTOMJS') @endphp

			@guest
			@else
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->image != null)

			// Remove Image
			var SITEURL = '{{url('')}}';
			function deletePost(event) {
				var id  = $(event).data("id");
				let _url = `{{url('/customer/image/remove/${id}')}}`;

				let _token   = $('meta[name="csrf-token"]').attr('content');

				swal({
					title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
					text: "{{lang('This might erase your records permanently', 'alerts')}}",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							url: _url,
							type: 'DELETE',
							data: {
							_token: _token
						},
						success: function(response) {
							toastr.success(response.success);
							location.reload();
						},
						error: function (data) {
							console.log('Error:', data);
									}
						});
					}
				});
			}

			@endif
			@if(auth()->guard('customer')->user())

            let playAudio = ()=>{
                let audio = new Audio();
                audio.src = "../assets/sounds/norifysound.mp3";
                audio.load();
                audio.play();
            }

			setInterval( function() {

                $.ajax({
                    url: "{{route('customer.update.notificationalerts')}}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $.map( res, function( value, index ) {
                            if(index === 0){
                                toastr.success('{{lang("You have")}} '+ res.length +' {{lang("new notification")}}');
                                readnotify();
                                playAudio();
                            }
                        });
                    }
                });

                notificationsreading();
                badgecount();
                markasreadcount();
            }, 5000);
            function readnotify(){

                $.ajax({
                    url: "{{route('customer.update.notificationalertsread')}}",
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {

                    }
                });
            }

            $('.notifyreading').load('{{route('customer.notificationsreading')}}')

            function notificationsreading()
            {

                $('.notifyreading').load('{{route('customer.notificationsreading')}}')

            }
            function badgecount()
            {

                $('.badgecount').load('{{route('customer.badgecount')}}')

            }
            function markasreadcount()
            {


                $('.markasreadcount').load('{{route('customer.markasreadcount')}}')

            }

			// Mark as Read
			function sendMarkRequest(id = null) {
				return $.ajax("{{ route('customer.markNotification') }}", {
					method: 'GET',
					data: {
						id
					}
				});
			}
			(function($) {
				$('.mark-as-read').on('click', function() {
					let request = sendMarkRequest($(this).data('id'));
					request.done(() => {
						$(this).parents('div.alert').remove();
					});
				});
				$('.cmark-all').on('click', function() {
					let request = sendMarkRequest();
					request.done(() => {
						$('div.alert').remove();
					})
				});

				$('body').on('click', '.mark-read', function(e){
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: '{{route('customer.notify.markallread')}}',
                        success: function (data) {
                            notificationsreading();
                            badgecount();
                            // location.reload();
                        },
                        error: function (data) {
                        console.log('Error:', data);
                        }
                    });
                });


			})(jQuery);
			@endif

			@endguest

		</script>

		<!-- Custom html js-->
		<script src="{{gn_asset('assets/js/custom.js')}}"></script>
