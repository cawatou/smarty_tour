var productDeparture = Base.extend({
    templates: {
        'departure': null
    },

    $wrapper: null,
    wrapper_selector: '',

    options: {
        max_departures: 3
    },

    constructor: function (wrapper_selector) {
        if (typeof wrapper_selector === 'undefined') {
            wrapper_selector = '#product-departure-components';
        }

        var $tpl = $('#template-departure');
        this.templates.departure = $tpl.html();
        $tpl.remove();

        $tpl = null;

        this.$wrapper         = $(wrapper_selector);
        this.wrapper_selector = wrapper_selector;

        var that = this;

        this.$wrapper.find('> .product-departure-component').each(function () {
            that.initialize($(this));
        });

        this._recheckButtons();
    },

    initialize: function ($object) {
        if (typeof $object !== 'object' || $object === null) {
            throw 'Passed object must actually be an object';
        }

        $object.find('[data-datepicker]').each(function () {
            var $dpicker = $(this);

            $dpicker.datepicker({
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                showButtonPanel: false
            });
        });

        $object.find('.input-type-hotel').each(function () {
            var $input = $(this);

            $input.data('sys.autocomplete', new AutocompleteHotel($input));
        });
    },

    add: function () {
        var _tpl = this.templates.departure;

        _tpl = _tpl.replace(/#COUNTER#/ig, this._getNextCounter());
        var $tpl = $(_tpl);

        this.$wrapper.append($tpl);

        this._recheckButtons();

        this.initialize($tpl);

        $tpl = null;

        return this;
    },

    remove: function ($object) {
        $object.remove();

        this._recheckButtons();

        return this;
    },

    _recheckButtons: function () {
        var that = this;

        var $objects      = that.$wrapper.find('.product-departure-component');
        var total_objects = $objects.length;

        var i = 1;

        $objects.each(function () {
            var $object = $(this);

            if (i > that.options.max_departures) {
                $object.remove();

                return;
            }

            if (i === that.options.max_departures) {
                $object.find('button.btn[data-departure-add]').hide();
                $object.find('button.btn[data-departure-remove]').show();

                i++;

                return;
            }

            if (i === 1 && total_objects === 1) {
                $object.find('button.btn[data-departure-add]').show();
                $object.find('button.btn[data-departure-remove]').hide();

                return;
            }

            if (i === total_objects) {
                $object.find('button.btn[data-departure-add]').show();
                $object.find('button.btn[data-departure-remove]').show();

                return;
            }

            $object.find('button.btn[data-departure-add]').hide();
            $object.find('button.btn[data-departure-remove]').show();

            i++;
        });

        $objects = null;

        return that;
    },

    _getNextCounter: function () {
        return new Date().getTime();
    }
});