$(document).ready(function () {
    Suggest.initialize($('#filter_search_country'), $('#filter_search_resort'));

    var $resort_id_wrapper   = $('#filter_search_resort_wrapper');
    var $resort_name_wrapper = $('#resort_name_wrapper');

    $resort_id_wrapper.find('select').bind('change', function () {
        $resort_name_wrapper.removeClass('hidden');

        if ($(this).val()) {
            $resort_name_wrapper.addClass('hidden');
        }
    }).trigger('change');

    var $hotel_name = $('#order_hotel_name');

    $hotel_name.data('sys.autocomplete', new AutocompleteHotel($hotel_name));

    var $row_hotel_data = $('#row_hotel_data');

    $row_hotel_data.on(
        'typeahead:update',
        '#order_hotel_name',

        function (e, item, data) {
            var $this = $(this);

            if (data.stars) {
                $row_hotel_data.find('.input-special-hotel-stars').val(data.stars);
            }

            var $url_wrapper = $row_hotel_data.find('.input-special-hotel-url-wrapper');

            if (!$url_wrapper.hasClass('invisible')) {
                $url_wrapper.addClass('invisible');
            }

            $this.parent().find('.notice').html('<a href="'+ $this.attr('data-url-hotel-edit') +''+ data.id +'" title="'+ data.name +'" class="blank" target="_blank">'+ data.name +'</a>');
        }
    )
    .on(
        'keydown',
        '#order_hotel_name',

        function (e) {
            var $this = $(this);

            $row_hotel_data.find('.input-special-hotel-url-wrapper').removeClass('invisible');

            var $this_parent = $this.parent();

            $this_parent.find('.notice').html('');
            $this_parent.find('input[type="hidden"]').val('');
        }
    );
});