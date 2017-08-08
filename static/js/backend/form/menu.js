jQuery(document).ready(function() {
    $('.js-menu-type').change(function(e) {
        $('.js-path-box').removeClass('hidden');
        $('.js-common-box').addClass('hidden');
        $('.js-cmd-box').addClass('hidden');
        $('.js-page-box').addClass('hidden');

        if (this.value == 'MENU_ROOT') {
            $('.js-path-box').addClass('hidden');
        } else if (this.value == 'CMD') {
            $('.js-cmd-box').removeClass('hidden');
        } else if (this.value == 'EMPTY') {
            $('.js-common-box').removeClass('hidden').find('input').val('#').prop('readonly', true);
        } else if (this.value == 'LINK') {
            $('.js-common-box').removeClass('hidden').find('input').prop('readonly', false);
            if ($('.js-common-box').find('input').val() == '#') {
                $('.js-common-box').find('input').val('');
            }
        } else if (this.value == 'PAGE') {
            $('.js-page-box').removeClass('hidden');
        }
    });

    $('.js-menu-type').change();
});