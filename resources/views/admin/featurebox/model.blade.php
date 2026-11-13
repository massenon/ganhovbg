                        <!-- Add FeatureBox-->
                        <div class="modal fade"  id="addfeature" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" ></h5>
                                        <button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data" id="featurebox_form" name="featurebox_form">
                                        <input type="hidden" name="featurebox_id" id="featurebox_id">
                                        @csrf
                                        @honeypot
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                                                <input type="text" class="form-control" name="title" id="name">
                                                <span id="nameError" class="text-danger alert-message"></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{lang('Description')}} <span class="text-red">*</span></label>
                                                <textarea class="form-control"  name="subtitle" id="description"></textarea>
                                                <span id="descriptionError" class="text-danger alert-message"></span>

                                                <div id="count">
                                                    <span id="current_count">0</span>
                                                    <span id="maximum_count">/ 255</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{lang('URL')}}</label>
                                                <input type="text" class="form-control mb-4" name="featureboxurl" id="featureboxurl">
                                                <div>
                                                    <input type="checkbox" name="url_checkbox" id="url_checkbox" class="urlcheckall" autocomplete="off">
                                                    <span class="ms-1">{{lang('Open in a new tab')}}</span>
                                                </div>
                                                <span id="featureboxurlError" class="text-danger alert-message"></span>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{lang('Upload Image')}}</label>
                                                <div class="input-group file-browser">
                                                    <input class="form-control " id="image" name="image" type="file" >
                                                </div>
                                                <small class="text-muted"><i>{{lang('Filesize should not be morethan 10MB', 'filesetting')}}</i></small>
                                                <div>
                                                    <span id="ImageError" class="text-danger alert-message"></span>
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
                        <!-- End  Add FeatureBox  -->


                         <!--Count the words   -->
                        <script type="text/javascript">
                                "use strict";
                            $('textarea').keyup(function() {
                                var characterCount = $(this).val().length,
                                current_count = $('#current_count'),
                                maximum_count = $('#maximum_count'),
                                count = $('#count');
                                current_count.text(characterCount);
                            });
                        </script>
