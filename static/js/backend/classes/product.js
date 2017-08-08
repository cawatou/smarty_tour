var hotelsListMany = manyRows.extend({
    $tpl_price: null,
    $is_discount_applied: null,
    $is_promo_discount: null,

    constructor: function (params) {
        this.$tpl_price = $('.template-hotel-price-dot').clone().html();
        $('.template-hotel-price-dot').remove();

        this.$is_discount_applied = $('input.checkbox-change-is-discount-applied');

        if (!this.$is_discount_applied.length) {
            this.$is_discount_applied = null;
        }

        this.$is_promo_discount = $('input.checkbox-change-is-highlight');

        if (!this.$is_promo_discount.length) {
            this.$is_promo_discount = null;
        }

        this.base(params);
    },

    addRow: function ($source) {
        var
            $row = this.$tpl.clone(),
            id   = new Date().getTime(),
            that = this;

        $row.attr('data-counter', id).insertAfter($source);

        $row.find('input, textarea, select').each(function () {
            $(this).attr('name', $(this).attr('name').replace(/#ID#/ig, id));
        });

        $row.find('[data-counter]').each(function () {
            $(this).attr('data-counter', id);
        });

        var $dep_wrapper = $('#row-departure-components');
        var $departures  = $dep_wrapper.find('.row-component-departure-date');

        $departures.each(function () {
            that.addNewPrice($(this), $departures);
        });

        this.initRow($row);
    },

    hideBtn: function($row, sel) {
        $row.find(sel).closest('li').addClass('disabled');
    },

    showBtn: function($row, sel) {
        $row.find(sel).closest('li').removeClass('disabled');
    },

    initRow: function ($row) {
        var $hotel_name = $row.find('input.input-special-hotel-name');
        $hotel_name.data('sys.autocomplete', new AutocompleteHotel($hotel_name));

        if (this.$is_discount_applied !== null) {
            if (!this.$is_discount_applied.is(':checked')) {
                $row.find('.dependable-is-discount-applied').hide();
            } else {
                $row.find('.dependable-is-discount-applied').show();
            }
        }

        if (this.$is_promo_discount !== null) {
            if (!this.$is_promo_discount.is(':checked')) {
                $row.find('.dependable-is-discount-applied input').attr('checked', false);
            } else {
                $row.find('.dependable-is-discount-applied input').attr('checked', true);
            }
        }

        var oldHotelName = $hotel_name.val();

        $row.on(
            'typeahead:update',
            'input.input-special-hotel-name',

            function (e, item, data) {
                var $this = $(this);

                var $parent_attr = $this.parents('.row-component-hotel');

                if (data.stars) {
                    $parent_attr.find('.input-special-hotel-stars').val(data.stars);
                }

                var $url_wrapper = $parent_attr.find('.input-special-hotel-url-wrapper');

                if (!$url_wrapper.hasClass('invisible')) {
                    $url_wrapper.addClass('invisible');
                }

                $this.parent().find('.notice').html('<a href="'+ $this.attr('data-url-hotel-edit') +''+ data.id +'" title="'+ data.name +'" class="blank" target="_blank">'+ data.name +'</a>');

                oldHotelName = data.name;

                e.stopPropagation();
                e.preventDefault();

                return false;
            }
        )
        .on(
            'change',
            'input.input-special-hotel-name',

            function (e) {
                var $this = $(this);

                if ($this.val() != oldHotelName) {
                    $this.parents('.row-component-hotel').find('.input-special-hotel-url-wrapper').removeClass('invisible');

                    var $this_parent = $this.parent();

                    $this_parent.find('.notice').html('');
                    $this_parent.find('input[type="hidden"]').val('');

                    oldHotelName = $this.val();
                }
            }
        );

        this.base($row);
    },

    countUpRow: function () {
        var that = this;

        var $rows = this.$container.find('.row-component-hotel');
        var total = $rows.length;

        $rows.each(function (index) {
            var $row = $(this);

            $row.find('.hotel-iteration-id').css('top', '5px');

            if (total == 1) {
                that.hideUp($row);
                that.hideDown($row);
                that.hideDel($row);

                $row.find('.hotel-iteration-id').css('top', '32px');
            } else if (index == 0) { // first line
                that.hideUp($row);
                that.showDown($row);
                that.showDel($row);

                $row.find('.hotel-iteration-id').css('top', '32px');
            } else if (index == total - 1) { // last line
                that.showUp($row);
                that.hideDown($row);
                that.showDel($row);
            } else {
                that.showUp($row);
                that.showDown($row);
                that.showDel($row);
            }

            $row.find('label').show();

            if (index !== 0) {
                $row.find('label').hide();
            }

            $row.find('.hotel-iteration-id').text(index + 1);
        });

        return {"total": total};
    },

    initPrice: function ($object) {
        var is_highlight = $('.checkbox-change-is-highlight').is(':checked');

        if (is_highlight) {
            $object.find('.input-special-hotel-is-promoprice input').prop('checked', true);
        }

        return false;
    },

    addNewPrice: function ($departure, $departures) {
        var that = this;

        this.$container.find('.row-component-hotel').each(function (index) {
            var i = index + 1;

            var $line = $(this);

            var total_prices = $line.find('.column-special-hotel-price').length;

            if (total_prices === $departures.length) {
                // All the prices are added already
                return;
            }

            var _tpl = that.$tpl_price;

            _tpl = _tpl.replace(/#COUNTER_HOTELS#/ig, $line.attr('data-counter')).replace(/#ID#/ig, $line.attr('data-counter')).replace(/#COUNTER#/ig, $departure.attr('data-counter')).replace(/#DEPARTURE_ID#/ig, $departure.attr('id'));
            var $tpl = $(_tpl);

            var departure_title = $departures.eq(total_prices).find('.input-special-departure-date').val();

            if (!departure_title) {
                departure_title = 'Цена';
            }

            $tpl.find('label').html(departure_title);

            if (i !== 1) {
                $tpl.find('label').hide();
            }

            $line.find('.column-special-hotel-url').before($tpl);

            that.initPrice($tpl);

            $tpl = null;
        });

        $('body').trigger('product:price:add');

        return this;
    },

    removeExistingPrice: function ($departure) {
        this.$container.find('.'+ $departure.attr('id')).remove();
    }
});

var productDepartureMaintainer = Base.extend({
    templates: {
        'departure_date': null
    },

    $wrapper: null,

    selectors: {
        'wrapper':       '#row-departure-components',
        'template_line': '.template-departure-date-line',
        'line':          '.row-component-departure-date'
    },

    options: {
        max_departures: 2
    },

    constructor: function () {
        var $tpl = $(this.selectors.template_line);
        this.templates.departure_date = $tpl.html();
        $tpl.remove();

        $tpl = null;

        this.$wrapper = $(this.selectors.wrapper);

        var that = this;

        if (this.getWrapper().find(this.selectors.line).length === 0) {
            this.add();
        }

        this.getWrapper().find(this.selectors.line).each(function () {
            that.initLine($(this));
        });

        this._recheckButtons();
        this._recheckLinkeds();
    },

    initLine: function ($object) {
        var that = this;

        $object.find('.input-special-departure-date').each(function () {
            var $datepicker = $(this);

            $datepicker.datetimepicker({
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                showButtonPanel: false
            });

            $datepicker.change(function () {
                that.getWrapper().trigger('departure.date.changed', [$datepicker.val(), $object, that.getAll()]);
            });
        });
    },

    getAll: function () {
        return this.getWrapper().find('.row-component-departure-date');
    },

    add: function () {
        var _tpl = this.templates.departure_date;

        _tpl = _tpl.replace(/#COUNTER#/ig, this._getNextCounter());
        var $tpl = $(_tpl);

        this.getWrapper().append($tpl).trigger('departure.added', [$tpl, this.getWrapper().find(this.selectors.line)]);

        this._recheckButtons();

        this.initLine($tpl);

        $tpl = null;

        return this;
    },

    remove: function ($object) {
        $object.remove();

        this._recheckButtons();

        this.getWrapper().trigger('departure.removed', [$object]);

        return this;
    },

    getWrapper: function () {
        return this.$wrapper;
    },

    _recheckButtons: function () {
        var that = this;

        var $objects      = this.getWrapper().find(this.selectors.line);
        var total_objects = $objects.length;

        $objects.each(function (i) {
            // Because index starts from 0
            i++;

            var $object = $(this);

            if (i > that.options.max_departures) {
                $object.remove();

                return;
            }

            if (i === that.options.max_departures) {
                $object.find('button.btn-departure-add').hide();
                $object.find('button.btn-departure-remove').show();

                i++;

                return;
            }

            if (i === 1 && total_objects === 1) {
                $object.find('button.btn-departure-add').show();
                $object.find('button.btn-departure-remove').hide();

                return;
            }

            if (i === total_objects) {
                $object.find('button.btn-departure-add').show();
                $object.find('button.btn-departure-remove').show();

                return;
            }

            $object.find('button.btn-departure-add').hide();
            $object.find('button.btn-departure-remove').show();

            i++;
        });

        that._recheckLinkeds();

        $objects = null;

        return this;
    },

    _recheckLinkeds: function () {
        var that = this;

        var $objects      = this.getWrapper().find(this.selectors.line);
        var total_objects = $objects.length;

        if (total_objects == 1) {
            $('#linked_products').removeClass('hidden');
        } else {
            $('#linked_products').addClass('hidden');
            $('#linked_products').find('input').val('');
        }

        $objects = null;

        return this;
    },

    _getNextCounter: function () {
        return new Date().getTime();
    }
});

var productHotelMaintainer = Base.extend({
    templates: {
        'hotel':     null,
        'price_dot': null
    },

    $wrapper: null,

    selectors: {
        wrapper: '#row-hotel-components'
    },

    constructor: function () {
        var $tpl = $('.template-hotel-line');
        this.templates.hotel = $tpl.html();
        $tpl.remove();

        $tpl = null;

        var $tpl = $('.template-hotel-price-dot');
        this.templates.price_dot = $tpl.html();
        $tpl.remove();

        $tpl = null;

        this.$wrapper = $(this.selectors.wrapper);

        var that = this;

        if (this.$wrapper.children('.row-component-hotel').length === 0) {
            this.add(this.$wrapper.children(), $('#row-departure-components').find('.row-component-departure-date'));
        }

        this.$wrapper.children('.row-component-hotel').each(function () {
            that.initLine($(this));
        });

        this._recheckButtons();
    },

    initLine: function ($object) {
        if (typeof $object !== 'object' || $object === null) {
            throw 'Passed object must actually be an object';
        }

        var $hotel_name = $object.find('input.input-special-hotel-name');
        $hotel_name.data('sys.autocomplete', new AutocompleteHotel($hotel_name));

        $object.on(
            'typeahead:update',
            'input.input-special-hotel-name',

            function (e, item, data) {
                var $this = $(this);

                var $parent_attr = $this.parents('.row-component-hotel');

                if (data.stars) {
                    $parent_attr.find('.input-special-hotel-stars').val(data.stars);
                }

                var $url_wrapper = $parent_attr.find('.input-special-hotel-url-wrapper');

                if (!$url_wrapper.hasClass('invisible')) {
                    $url_wrapper.addClass('invisible');
                }

                $this.parent().find('.notice').html('<a href="'+ $this.attr('data-url-hotel-edit') +''+ data.id +'" title="'+ data.name +'" class="blank" target="_blank">'+ data.name +'</a>');
            }
        )
        .on(
            'keyup',
            'input.input-special-hotel-name',

            function (e) {
                var $this = $(this);

                $this.parents('.row-component-hotel').find('.input-special-hotel-url-wrapper').removeClass('invisible');

                var $this_parent = $this.parent();

                $this_parent.find('.notice').html('');
                $this_parent.find('input[type="hidden"]').val('');
            }
        );
    },

    initPrice: function ($object) {
        var is_highlight = $('.checkbox-change-is-highlight').is(':checked');

        if (is_highlight) {
            $object.find('.input-special-hotel-is-promoprice input').prop('checked', true);
        }

        return false;
    },

    add: function ($object, $departures) {
        var _tpl = this.templates.hotel;

        _tpl = _tpl.replace(/#COUNTER_HOTELS#/ig, this._getNextCounter());
        var $tpl = $(_tpl);

        var that = this;

        $object.after($tpl);

        $departures.each(function () {
            that.addNewPrice($(this), $departures);
        });

        this._recheckButtons();

        this.initLine($tpl);

        $tpl = null;

        return this;
    },

    addNewPrice: function ($departure, $departures) {
        var that = this;

        this.getWrapper().find('.row-component-hotel').each(function (index) {
            var i = index + 1;

            var $line = $(this);

            var total_prices = $line.find('.column-special-hotel-price').length;

            if (total_prices === $departures.length) {
                // All the prices are added already
                return;
            }

            var _tpl = that.templates.price_dot;

            _tpl = _tpl.replace(/#COUNTER_HOTELS#/ig, $line.attr('data-counter')).replace(/#COUNTER#/ig, $departure.attr('data-counter')).replace(/#DEPARTURE_ID#/ig, $departure.attr('id'));
            var $tpl = $(_tpl);

            var departure_title = $departures.eq(total_prices).find('.input-special-departure-date').val();

            if (!departure_title) {
                departure_title = 'Цена';
            }

            if (i === 1) {
                $tpl.find('label').text(departure_title);
            } else {
                $tpl.find('label').remove();
            }

            $line.find('.column-special-hotel-url').before($tpl);

            that.initPrice($tpl);

            $tpl = null;
        });

        return this;
    },

    remove: function ($object) {
        $object.remove();

        this._recheckButtons();

        //this.getWrapper().trigger('', [$object]);

        return this;
    },

    removeExistingPrice: function ($departure) {
        this.getWrapper().find('.'+ $departure.attr('id')).remove();
    },

    _getNextCounter: function () {
        return new Date().getTime();
    }
});

$(document).ready(function () {
    var hotel_maintainer     = new hotelsListMany(PRODUCT_HOTEL_STORAGE);
    var departure_maintainer = new productDepartureMaintainer();

    departure_maintainer.getWrapper()
        .on('click', 'button.btn-departure-add', function () {
            departure_maintainer.add();

            return false;
        })
        .on('click', 'button.btn-departure-remove', function () {
            if (!confirm('Вы уверены?')) {
                return false;
            }

            departure_maintainer.remove($(this).parents('.row-component-departure-date'));

            return false;
        })
        .on('departure.added', function (event, $departure) {
            hotel_maintainer.addNewPrice($departure, departure_maintainer.getAll());

            $('body').trigger('product:price:add');
        })
        .on('departure.removed', function (event, $departure) {
            hotel_maintainer.removeExistingPrice($departure);

            $('body').trigger('product:price:remove');
        })
        .on('departure.date.changed', function (event, date, $departure, $departures) {
            $departures.each(function (d_i) {
                var $date = $(this).find('.input-special-departure-date');

                hotel_maintainer.$container.find('.input-special-hotel-price-wrapper label:eq('+ d_i +')').text(!$date.val() ? 'Цена' : $date.val());
            });
        })

        .find('.row-component-departure-date')
        .each(function () {
            hotel_maintainer.addNewPrice($(this), departure_maintainer.getAll());
        });

    hotel_maintainer.$container
        .on('click', 'button.btn-hotel-add', function () {
            hotel_maintainer.add($(this).parents('.row-component-hotel'), departure_maintainer.getAll());

            return false;
        })
        .on('click', 'button.btn-hotel-remove', function () {
            if (!confirm('Вы уверены?')) {
                return false;
            }

            hotel_maintainer.remove($(this).parents('.row-component-hotel'));

            return false;
        });
});