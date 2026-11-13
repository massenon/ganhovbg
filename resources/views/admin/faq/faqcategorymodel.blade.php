  		<!-- Add FAQ CATEGORY-->
          <div class="modal fade"  id="addfaqcat" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"></h5>
						<button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<form method="POST" id="faqcat_form">
                        <input type="hidden" name="faqcat_id" id="faqcat_id">
                        @csrf
                        @honeypot
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control" placeholder="{{lang('FAQ Category')}}" name="faqcategoryname" id="faqcatname"  autofocus required>
                                <span id="faqcatnameError" class="text-danger alert-message"></span>
                            </div>

                            <div class="form-group">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex mt-4">
                                        <label class="form-label pe-2">{{lang('Status')}}</label>
                                        <a class="onoffswitch2">
                                            <input type="checkbox"  name="status" id="status" class=" toggle-class onoffswitch2-checkbox"  checked>
                                            <label for="status" class="toggle-class onoffswitch2-label" ></label>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary" id="faqcatbtn">{{lang('Sauvegarder')}}</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
		<!-- End  Add FAQ CATEGORY  -->
