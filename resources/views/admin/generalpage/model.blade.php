                    <!-- Add or Edit Privacy Policy & Terms of Use model-->
                    <div class="modal fade"  id="addtestimonial" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" ></h5>
                                    <button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <form method="POST" enctype="multipart/form-data" id="pages_form" name="pages_form">
                                    <input type="hidden" name="pages_id" id="pages_id">
                                    @csrf
                                    @honeypot
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="pagename" id="pagename" >
                                            <span id="nameError" class="text-danger alert-message"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{lang('Description')}} <span class="text-red">*</span></label>
                                            <textarea class="form-control summernote"  name="pagedescription" id="pagedescription"></textarea>
                                            <span id="descriptionError" class="text-danger alert-message"></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-controls-stacked d-md-flex  d-md-max-block">
                                                <label class="form-label mt-1 me-4">{{lang('View On:')}} <span class="text-red">*</span></label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input" id="display" name="display" value="both">
                                                    <span class="custom-control-label">{{lang('View On Both')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input " id="display1" name="display" value="header">
                                                    <span class="custom-control-label">{{lang('View on header')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input " id="display2" name="display" value="footer">
                                                    <span class="custom-control-label">{{lang('View on footer')}}</span>
                                                </label>
                                            </div>
                                            <span id="displayError" class="text-danger alert-message"></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="switch_section">
                                                <div class="switch-toggle d-flex  d-md-max-block mt-4 ms-0 ps-0">
                                                    <label class="form-label pe-1 me-6">{{lang('Status')}}:</label>
                                                    <a class="onoffswitch2">
                                                        <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                                        <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" "></label>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a>
                                        <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Sauvegarder')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End  Add or Edit Privacy Policy & Terms of Use  -->