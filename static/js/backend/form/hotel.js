var hotelsDescriptionDataMany = manyRows.extend({
    hideBtn: function($row, sel) {
        $row.find(sel).closest('li').addClass('disabled');
    },

    showBtn: function($row, sel) {
        $row.find(sel).closest('li').removeClass('disabled');
    },

    countUpRow: function () {
        var
            that  = this,
            $opts = this.$container.find('.options-wrapper');

        $opts.each(function () {
            var $rows = $(this).children(this.row_class);
            var total = $rows.length;

            $rows.each(function (index) {
                var $row = $(this);

                if (total == 1) {
                    that.hideUp($row);
                    that.hideDown($row);
                    that.hideDel($row);
                } else if (index == 0) { // first line
                    that.hideUp($row);
                    that.showDown($row);
                    that.showDel($row);
                } else if (index == total - 1) { // last line
                    that.showUp($row);
                    that.hideDown($row);
                    that.showDel($row);
                } else {
                    that.showUp($row);
                    that.showDown($row);
                    that.showDel($row);
                }
            });
        });
    }
});

jQuery(document).ready(function () {
    new hotelsDescriptionDataMany(HOTEL_DATA_STORAGE);
    initRedactor(HOTEL_REDACTOR_ID);

    var $hotel_title = $('#'+ HOTEL_TITLE);

    if ($hotel_title.length) {
        $hotel_title.on('change', function () {
            $.ajax({
                url:      $hotel_title.attr('data-check-url'),
                data:     {
                    hotel_title: $hotel_title.val(),
                    ajax: ''
                },
                type:     'POST',
                dataType: 'json'
            }).success(function (data) {
                $hotel_title.parent().removeClass('hotel-title-state-success hotel-title-state-error');

                if (data.is_unique) {
                    $hotel_title.parent().addClass('hotel-title-state-success');
                } else {
                    $hotel_title.parent().addClass('hotel-title-state-error');
                }
            }).error(function (e) {
            });
        });
    }

    Suggest.initialize($('#country_id'), $('#resort_id'));
});