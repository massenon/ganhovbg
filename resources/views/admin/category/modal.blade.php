                <!-- Add testimonial-->
                <div class="modal fade sprukosubcat"  id="addtestimonial" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" ></h5>
                                <button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <form method="POST" enctype="multipart/form-data" id="testimonial_form" name="testimonial_form">
                                <input type="hidden" name="testimonial_id" id="testimonial_id">
                                @csrf
                                @honeypot
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                                <label class="form-label d-flex align-items-center">{{lang('Nom')}} <span class="text-red ms-1">*</span> </label>
                                            </div>
                                            <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9">
                                                <input type="text" class="form-control" name="name" id="name">
                                                <span id="nameError" class="text-danger alert-message"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                                <label class="form-label">{{lang('View On:')}}  <span class="text-red">*</span></label>
                                            </div>
                                            <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9 custom-controls-stacked d-md-flex  d-md-max-block">
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display" name="display" value="both">
                                                    <span class="custom-control-label">{{lang('View On Both')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display1" name="display" value="ticket">
                                                    <span class="custom-control-label">{{lang('View On Tickets')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                    <input type="radio" class="custom-control-input display" id="display2" name="display" value="knowledge">
                                                    <span class="custom-control-label">{{lang('View On Knowledge')}}</span>
                                                </label>
                                                <span id="displayError" class="text-danger alert-message"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="priority_hide">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                                    <label class="form-label">{{lang('Choose Priority :')}}</label>
                                                </div>
                                                <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9 custom-controls-stacked d-md-flex  d-md-max-block">
                                                    <label class="custom-control form-radio success me-4">
                                                        <input type="radio" class="custom-control-input" id="priority" name="priority" value="Low">
                                                        <span class="custom-control-label">{{lang('Low')}}</span>
                                                    </label>
                                                    <label class="custom-control form-radio success me-4">
                                                        <input type="radio" class="custom-control-input " id="priority1" name="priority" value="Medium">
                                                        <span class="custom-control-label">{{lang('Medium')}}</span>
                                                    </label>
                                                    <label class="custom-control form-radio success me-4">
                                                        <input type="radio" class="custom-control-input " id="priority2" name="priority" value="High">
                                                        <span class="custom-control-label">{{lang('High')}}</span>
                                                    </label>
                                                    <label class="custom-control form-radio success me-4">
                                                        <input type="radio" class="custom-control-input " id="priority3" name="priority" value="Critical">
                                                        <span class="custom-control-label">{{lang('Critical')}}</span>
                                                    </label>
                                                    <span id="priorityError" class="text-danger alert-message"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-3">
                                                <label class="form-label pe-1 me-6">{{lang('Status :')}}</label>
                                            </div>
                                            <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-9">
                                                <a class="onoffswitch2">
                                                    <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                                    <label for="myonoffswitch18" class="toggle-class onoffswitch2-label"></label>
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
                <!-- End  Add testimonial  -->
