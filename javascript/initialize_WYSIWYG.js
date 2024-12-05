document.addEventListener("DOMContentLoaded", function() {
    // Initialize TinyMCE
    tinymce.init({
        selector: 'textarea.wysiwyg-editor',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
    });

    // Listen for form submit button click
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        tinymce.triggerSave(); // Ensure TinyMCE content is saved to the textarea
    });
});