  		<!-- Add Password Reset-->
          <div class="modal fade"  id="addpasswordreset" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" ></h5>
						<button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<form method="POST" enctype="multipart/form-data" id="sprukopasswordreset_form" name="sprukopasswordreset_form">
                        <input type="hidden" name="sprukopasswordreset_id" class="sprukopasswordreset_id">
                        @csrf
                        @honeypot
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">{{lang('Mot de passe')}}</label>
                                <div class="input-group">
                                    <input type="password" class="form-control sprukopsdauto" name="resetpassword" required>
                                    <div class="input-group-text p-0">
                                        <button type="button"  class="btn btn-light-2  sprukovisipsd"><i class="fe fe-eye"></i></button>
                                        <button type="button" class="btn btn-light-2   br-br-5 br-tr-5 sprukogenratepsd" >{{lang('Generate Password')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary sprukoempchange" id="sprukoempchange" >{{lang('Sauvegarder')}}</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
		<!-- End  Password Reset  -->


        <script>
            "use strict";
            let sprukogenpsd = document.querySelector('.sprukogenratepsd');
            let sprukopsdauto = document.querySelector('.sprukopsdauto');
            let sprukovisipsd = document.querySelector('.sprukovisipsd i');
            let sprukovisipsds = document.querySelector('.sprukovisipsd');

            sprukogenpsd.addEventListener('click', () => {
                var password = '';
                var strs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' + 
                        'abcdefghijklmnopqrstuvwxyz0123456789@#$';
                
                for (let i = 1; i <= 10; i++) {
                    var char = Math.floor(Math.random()
                                * strs.length + 1);
                    
                    password += strs.charAt(char)
                }
                sprukopsdauto.value = password;
            });

            sprukovisipsds.addEventListener('click', ()=>{
                
                if(sprukopsdauto.getAttribute('type') == "text"){
                    
                    sprukopsdauto.setAttribute('type', 'password');
                    sprukovisipsd.removeAttribute('class', 'fe fe-eye-off');
                    sprukovisipsd.setAttribute('class', 'fe fe-eye');
                }else if(sprukopsdauto.getAttribute('type') == "password"){
                    sprukopsdauto.setAttribute('type', 'text');
                    sprukovisipsd.removeAttribute('class', 'fe fe-eye');
                    sprukovisipsd.setAttribute('class', 'fe fe-eye-off');
                }
            });
            
           
            
        </script>