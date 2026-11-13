(function($) {
    "use strict";

	//______summernote
	$('.summernote').summernote({
		placeholder: '',
		tabsize: 1,
		height: 200,
        disableDragAndDrop:true,
	});

	//______summernote
	$('.editsummernote').summernote({

		tabsize: 1,
		height: 200,
        disableDragAndDrop:true,
	});


	// ______________ Attach Remove
	$(document).on('click', '[data-toggle="remove"]', function(e) {
		let $a = $(this).closest(".attach-supportfiles");
		$a.remove();
		e.preventDefault();
		return false;
	});


})(jQuery);
