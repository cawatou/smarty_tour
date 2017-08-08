jQuery(document).ready(function () {
    $(document).on(
        'click',
        '.add-attr',

        function (e) {
            var $this   = $(this);
            var $parent = $this.closest('.row-attr');
            var $clone  = $parent.clone();

            $parent.find('.add-attr').addClass('hidden');
            $parent.find('.remove-attr').removeClass('hidden');

            var _input_name = new Date().getTime();

            $clone.find('input, select, textarea').each(function () {
                var $input = $(this);

                if ($input.attr('type') === 'checkbox' || $input.attr('type') === 'radio') {
                    $input.removeAttr('checked');
                } else {
                    $input.val('');
                }

                var data_name = $input.attr('data-name');

                if (typeof data_name !== 'undefined') {
                    $input.attr('name', data_name.replace(/#COUNTER_HOTELS#/ig, _input_name));
                }
            });

            $clone.find('.dropdown-menu').remove();
            $clone.find('span.notice').html('');
            $clone.find('.product-departure-url-wrapper.invisible').removeClass('invisible');

            $clone.appendTo($parent.parent());

            return false;
        }
    );

    $(document).on(
        'click',
        '.remove-attr',

        function () {
            if (confirm('Вы уверены?')) {
                $(this).closest('.row-attr').remove();
            }

            return false;
        }
    );

    Suggest.initialize($('#filter_search_country'), $('#filter_search_resort'));

    $('#filter_search_country').change(function () {
        var $this = $(this);

        var russia_id = $this.attr('data-russia-id');

        if (typeof russia_id === 'undefined' || !russia_id) {
            return;
        }

        if ($this.val() == russia_id) {
            $('.dependable-country-russia').show();
        } else {
            $('.dependable-country-russia').hide();
        }
    }).change();

    initLightRedactor(PROD_LIGHT_REDACTOR_ID);
    initRedactor(PROD_REDACTOR_ID);

    var $resort_id_wrapper = $('#filter_search_resort_wrapper');
    var $product_resort_name_wrapper = $('#product_resort_name_wrapper');

    $resort_id_wrapper.find('select').bind('change', function () {
        $product_resort_name_wrapper.removeClass('hidden');

        if ($(this).val()) {
            $product_resort_name_wrapper.addClass('hidden');
        }
    }).trigger('change');

    var $checkbox_is_highlight                 = $('.checkbox-change-is-highlight');
    var $checkbox_is_discount_applied          = $('.checkbox-change-is-discount-applied');
    var $checkbox_is_discount_dependable       = $('.dependable-is-discount-applied');
    var $checkbox_is_discount_dependable_input = $('.input-special-hotel-is-promoprice').children('input');

    $('body').bind('product:price:add', function () {
        $checkbox_is_discount_dependable       = $('body').find('.dependable-is-discount-applied');
        $checkbox_is_discount_dependable_input = $('body').find('.input-special-hotel-is-promoprice').children('input');
    }).bind('product:price:remove', function () {
        $checkbox_is_discount_dependable       = $('body').find('.dependable-is-discount-applied');
        $checkbox_is_discount_dependable_input = $('body').find('.input-special-hotel-is-promoprice').children('input');
    });

    $checkbox_is_discount_applied.change(function () {
        if ($checkbox_is_discount_applied.is(':checked')) {
            $checkbox_is_discount_dependable.show();
        } else {
            $checkbox_is_discount_dependable.hide();
        }
    }).change();

    $checkbox_is_highlight.change(function () {
        if ($checkbox_is_highlight.is(':checked')) {
            $checkbox_is_discount_dependable_input.prop('checked', true);
        } else {
            $checkbox_is_discount_dependable_input.prop('checked', false);
        }
    });

    $checkbox_is_discount_dependable_input.change(function () {
        if ($checkbox_is_discount_dependable_input.length === $checkbox_is_discount_dependable_input.filter(':checked').length) {
            $checkbox_is_highlight.prop('checked', true);
        } else {
            $checkbox_is_highlight.prop('checked', false);
        }
    });
});