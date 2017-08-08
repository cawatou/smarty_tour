$(document).ready(function () {
    var $modal_order = $('#modal-buy-tour');

    if ($modal_order.length) {
        $modal_order.on('shown.bs.modal', function () {
            var $this = $(this);

            $this.find('select:not([data-select-initialized])').ikSelect({
                autoWidth:   false,
                ddFullWidth: false
            }).attr('data-select-initialized', '');

            $this.find('.input-masked-phone').mask('7 (999) 999-99-99');

            $this
                .find('.order-type-switcher input')
                .change(
                    function () {
                        var $type = $(this);

                        $modal_order.removeClass('order-type-selected-office order-type-selected-online').addClass('order-type-selected-'+ $type.val().toLowerCase());
                    }
                )
                .filter(':checked')
                .change();

            var $tour_get_via = $('.tour-get-via-input').filter(':checked');

            // @todo Remove false, when task is created
            if (false && $tour_get_via.length) {
                $this.find('.tour-get-via-input-value').val($tour_get_via.val());
                $this.find('.tour-get-via-input-title').val($tour_get_via.parent().children('.tour-get-via-item-text').text());
            }
        });
    }

    $(document).on('submit', '.modal-buy-tour-form', function () {
        var $this = $(this);

        var $submit = $this.find('.site-btn[type="submit"]').attr('disabled', 'disabled');

        $.ajax({
            url:      $this.attr('action') +'?ajax',
            type:     'POST',
            data:     $this.serialize() +'&'+ $this.find('.site-btn').attr('name') +'=1',
            dataType: 'json'
        }).done(function (data) {
            $submit.removeAttr('disabled');

            if (data.html) {
                var $is_success = $modal_order.find('.modal-body-inner').html(data.html).find('#modal-buy-form-successful');

                if ($is_success.length) {
                    $modal_order.removeClass('modal-loaded');
                }

                setTimeout(
                    function () {
                        $modal_order.trigger('shown.bs.modal');
                    },
                    0
                );
            }
        });

        return false;
    });

    $('#table-departure-hotels').on('click', '.order-button', function () {
        var $this = $(this);

        $.ajax({
            url:      $this.attr('data-source') +'?ajax',
            dataType: 'json'
        })
        .done(function (data) {
            $modal_order.addClass('modal-loaded').find('.modal-body-inner').html(data.html);

            $modal_order.modal('show');
        });

        return false;
    });
});