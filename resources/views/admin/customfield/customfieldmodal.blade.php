				<!-- Assigned Tickets-->
				<div class="modal fade sprukosearch"  id="customfieldopen" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" ></h5>
								<button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<form method="POST" enctype="multipart/form-data" id="customfieldopen_form" name="customfieldopen_form">
								@csrf
								@honeypot

								<input type="hidden" name="customfieldopen_id" id="customfieldopen_id">
								<div class="modal-body pb-0">

									<div class="form-group ">
                                        <label for="" class="form-label">{{lang('Label field name')}} <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="sprukofieldname">
                                        <span id="sprukofieldnameError" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label"> {{lang('Field label type')}}</label>
                                        <select class="form-control select2_modalcustomfield" name="fieldtype" id="sprukofieldtype">

                                          </select>
                                    </div>
                                    <div class="form-group sprukofieldopen">
                                        <label for="" class="form-label mb-0">{{lang('Field options')}} <span class="text-red">*</span> </label>
                                        <small class="text-muted mb-2 d-block">( {{lang('You have to add the comma-separated values.')}})</small>
                                        <textarea name="fieldoptions" class="form-control" id="optionsfields" cols="30" rows="5" placeholder="{{lang('a,k,n')}}"></textarea>
                                        <span id="sprukooptionsfieldsError" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                                <label class="form-label">{{lang('View On')}}<span class="text-red">*</span></label>
                                            </div>
                                            <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9 custom-controls-stacked d-md-flex  d-md-max-block">
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display" name="display" value="both">
                                                    <span class="custom-control-label">{{lang('Both')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display1" name="display" value="createticket">
                                                    <span class="custom-control-label">{{lang('Create Tickets')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display2" name="display" value="registerform">
                                                    <span class="custom-control-label">{{lang('Register')}}</span>
                                                </label>

                                                 <span id="displayError" class="text-danger alert-message"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="switch_section">
                                            <div class="d-flex mt-3">
                                                <a class="onoffswitch2">
                                                    <input type="checkbox"  name="requiredfields" id="requiredfields" class=" toggle-class onoffswitch2-checkbox">
                                                    <label for="requiredfields" class="toggle-class onoffswitch2-label" ></label>
                                                </a>
                                                <label class="form-label ps-2">{{lang('Required field')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group sprukoprivacyfield">
                                        <div class="switch_section">
                                            <div class="d-flex mt-3">
                                                <a class="onoffswitch2">
                                                    <input type="checkbox"  name="privacyfields" id="privacyfields" class=" toggle-class onoffswitch2-checkbox">
                                                    <label for="privacyfields" class="toggle-class onoffswitch2-label" ></label>
                                                </a>
                                                <label class="form-label ps-2">{{lang('Privacy')}}
                                                    <small class="text-muted">({{lang('If you select this option, the content in the field will be encrypted.')}})
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="switch_section">
                                            <div class="d-flex mt-2">
                                                <a class="onoffswitch2">
                                                    <input type="checkbox"  name="status" id="status" class=" toggle-class onoffswitch2-checkbox">
                                                    <label for="status" class="toggle-class onoffswitch2-label" ></label>
                                                </a>
                                                <label class="form-label ps-2">{{lang('Status')}}</label>
                                            </div>
                                        </div>
                                    </div>

								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Sauvegarder')}}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- End Assigned Tickets  -->


