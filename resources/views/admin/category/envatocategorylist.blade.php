                <!-- Add Envato Api Modal-->
                <div class="modal fade"  id="addEnvatoapi" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" ></h5>
                                <button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <form method="POST" enctype="multipart/form-data" id="categoryenvato_form" name="categoryenvato_form">

                                @honeypot
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">{{lang('Choisir une catégorie')}} </label>
                                        <div class="custom-controls-stacked d-md-flex" >
                                            <select multiple="multiple" class="form-control select2_envato"  name="categorys_id[]" data-placeholder="{{lang('Choisir une catégorie')}}" id="categorys" >

                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="envato_enable" value="1">
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a>
                                    <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Sauvegarder')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End  Add Envato Api Modal  -->
