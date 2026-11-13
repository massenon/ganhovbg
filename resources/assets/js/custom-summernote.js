// Summernote
$('.summernote').summernote({
    placeholder: '',
    tabsize: 1,
    height: 200,
// 	toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
// 	['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], // ['height', ['height']],
// 	['table', ['table']], ['insert', ['link']], ['view', ['fullscreen']], ['help', ['help']]],
// disableDragAndDrop:true,
    toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
    ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], // ['height', ['height']],
    ['table', ['table']], ['insert', ['link']], ['view', ['fullscreen']], ['help', ['help']]],
    callbacks: {
        onImageUpload: function(e){}
    },
});
