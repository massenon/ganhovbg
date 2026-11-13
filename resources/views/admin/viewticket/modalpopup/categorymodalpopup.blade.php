  		<!-- Category List-->
          <div class="modal fade sprukosearchcategory"  id="addcategory" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" ></h5>
						<button  class="close" data-bs-dismiss="modal" aria-label="Fermer">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<form method="POST" enctype="multipart/form-data" id="sprukocategory_form" name="sprukocategory_form">
                        <input type="hidden" name="ticket_id" class="ticket_id">
                        @csrf
                        @honeypot
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">{{lang('Choisir une catégorie')}}</label>
                                <div class="custom-controls-stacked d-md-flex" >
									<select class="form-control select4-show-search" data-placeholder="{{lang('Choisir une catégorie')}}" name="category" id="sprukocategorylist" >

									</select>
								</div>
								<span id="CategoryError" class="text-danger"></span>
                            </div>
							<div class="form-group" id="envatopurchase">
							</div>
							<div class="form-group" id="selectssSubCategory" style="display: none;">

								<label class="form-label mb-0 mt-2">{{lang('Sous-catégorie')}}</label>
								<select  class="form-control subcategoryselect"  data-placeholder="{{lang('Sélectionnez la sous-catégorie')}}" name="subscategory" id="subscategory">

								</select>
								<span id="subsCategoryError" class="text-danger alert-message"></span>

							</div>
							<div class="form-group" id="selectSubCategory">
							</div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Fermer')}}</a>
                            <button type="submit" class="btn btn-secondary sprukoapiblock" id="btnsave" >{{lang('Sauvegarder')}}</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
		<!-- End Category List  -->
