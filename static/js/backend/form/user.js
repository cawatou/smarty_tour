$(document).ready(function () {
    var $form   = $('.module-form');
    var $select = $form.find('.form-control-user-role');

    $select.change(function () {
        var value = $select.val().toLowerCase();

        $form.find('.row-wrappers').hide();
        $form.find('.row-wrapper-'+ value).show();
    }).change();
});