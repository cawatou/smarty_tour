jQuery(document).ready(function () {
    if (typeof TEXTAREA_WYSIWYG_ID !== 'undefined') {
        initRedactor(TEXTAREA_WYSIWYG_ID);
    }
    if (typeof TEXTAREA_CODE_ID !== 'undefined') {
        initCodeMirror(TEXTAREA_CODE_ID);
    }

});