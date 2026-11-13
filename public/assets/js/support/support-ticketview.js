/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************************!*\
  !*** ./resources/assets/js/support/support-ticketview.js ***!
  \***********************************************************/
(function ($) {
  "use strict";

  //______summernote
  $('.summernote').summernote({
    placeholder: '',
    tabsize: 1,
    height: 200,
    disableDragAndDrop: true
  });

  //______summernote
  $('.editsummernote').summernote({
    tabsize: 1,
    height: 200,
    disableDragAndDrop: true
  });

  // ______________ Attach Remove
  $(document).on('click', '[data-toggle="remove"]', function (e) {
    var $a = $(this).closest(".attach-supportfiles");
    $a.remove();
    e.preventDefault();
    return false;
  });
})(jQuery);
/******/ })()
;