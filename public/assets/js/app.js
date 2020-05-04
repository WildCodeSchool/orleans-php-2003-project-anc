tinymce.init({
    selector: 'textarea#tinyMCE',
    max_width: 1050,
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | preview media fullpage | ' +
        'forecolor backcolor | help',
    content_css: '/assets/css/admin/tinyMCE.css',
});
