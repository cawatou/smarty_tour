function toggleWysiwyg(type, map) {
    for (var i in map) {
        map[i].hide().find('textarea').attr('disabled', true);
    }
    map[type].show().find('textarea').attr('disabled', false);
}

jQuery(document).ready(function () {
    initRedactor(textarea_wysiwyg_id);
    initCodeMirror(textarea_code_id);

    var $toggle = $('#'+toggle_id),
        toggle_map = {
            'TEXT': $('#' + box_pain_id),
            'WYSIWYG': $('#' + box_wysiwyg_id),
            'CODE': $('#' + box_code_id)
        };

    $toggle.change(function() {
        toggleWysiwyg($toggle.val(), toggle_map);
    });
    toggleWysiwyg($toggle.val(), toggle_map);
});