var Suggest = new function () {
    var self = this;

    this.config = {
        'urls': {
            'getCountries': '/ajax/countries',
            'getResorts':   '/ajax/resorts',
            'getHotels':    '/ajax/hotels'
        }
    };

    this.data = {
        countryId: undefined,
        resortId:  undefined,
        hotelId:   undefined
    };

    this.$selectCountries = null;
    this.$selectResorts   = null;
    this.$selectHotels    = null;

    this.initialize = function ($selectCountries, $selectResorts, $selectHotels) {
        if (!$selectCountries || $selectCountries.length === 0) {
            throw 'Countries select not exists';
        }

        if (!$selectResorts || $selectResorts.length === 0) {
            throw 'Resorts select not exists';
        }

        this.$selectCountries = $selectCountries;
        this.$selectResorts   = $selectResorts;

        if ($selectHotels) {
            this.$selectHotels = $selectHotels;
        }

        this.updateConfig();

        if ($selectCountries.val() > 0) {
            this.data.countryId = $selectCountries.val();
        }

        if ($selectResorts.val() > 0) {
            this.data.resortId = $selectResorts.val();
        }

        if ($selectHotels) {
            if ($selectHotels.val() > 0) {
                this.data.hotelId = $selectHotels.val();
            }
        }

        this.applyEvents();

        this.fill();

        return this;
    };

    this.applyEvents = function () {
        if (this.$selectCountries.val()) {
            this.data.countryId = this.$selectCountries.val();
        }

        this.$selectCountries.bind('change', function () {
            var $select = $(this);
            var _value  = $select.val();

            self.clearSelect(self.$selectResorts);

            if (self.$selectHotels) {
                self.clearSelect(self.$selectHotels);
            }

            self.data.resortId = undefined;
            self.data.hotelId  = undefined;

            if (_value) {
                if (_value != self.data.countryId) {
                    self.data.countryId = _value;
                    self.getResorts(_value);
                }
            }
        });

        if (this.$selectResorts.val()) {
            this.data.resortId = this.$selectResorts.val();
        }

        this.$selectResorts.bind('change', function () {
            var $select = $(this);
            var _value  = $select.val();

            if (self.$selectHotels) {
                self.clearSelect(self.$selectHotels);
            }

            self.data.hotelId = undefined;

            if (_value) {
                if (_value != self.data.resortId) {
                    self.data.resortId = _value;
                    self.getHotels(_value);
                }
            }
        });

        if (this.$selectHotels) {
            if (this.$selectHotels.val()) {
                this.data.hotelId = this.$selectHotels.val();
            }

            this.$selectHotels.bind('change', function () {
                var $select = $(this);
                var _value  = $select.val();

                if (_value) {
                    if (_value != self.data.hotelId) {
                        self.data.hotelId = _value;
                    }
                }
            });
        }

        return this;
    };

    this.updateConfig = function () {
        if (typeof this.$selectCountries.attr('data-source') !== 'undefined' && this.$selectCountries.attr('data-source')) {
            this.config.urls.getCountries = this.$selectCountries.attr('data-source');
        }

        if (typeof this.$selectResorts.attr('data-source') !== 'undefined' && this.$selectResorts.attr('data-source')) {
            this.config.urls.getResorts = this.$selectResorts.attr('data-source');
        }

        if (this.$selectHotels) {
            if (typeof this.$selectHotels.attr('data-source') !== 'undefined' && this.$selectHotels.attr('data-source')) {
                this.config.urls.getHotels = this.$selectHotels.attr('data-source');
            }
        }
    };

    this.getCountries = function (callback) {
        $.ajax({
            'url':      self.config.urls.getCountries,
            'data':     {},
            'dataType': 'json',
            'success':  function(data) {
                if ($.isArray(data)) {
                    return self;
                }

                var _storage = [];

                $.each(data, function (country_id, country) {
                    _storage.push('<option value="'+ country.id +'">'+ country.title +'</option>');
                });

                self.$selectCountries.removeClass('hidden').val('').append(_storage.join(''));

                if (self.$selectCountries.attr('data-wrapper')) {
                    $(self.$selectCountries.attr('data-wrapper')).removeClass('hidden');
                }

                if ($.isFunction(callback)) {
                    callback();
                }

                self.$selectCountries.trigger('reloaded:suggest');
            },
            'error': function(e) {
                alert(e);
            }
        });
    };

    this.getResorts = function (countryId, callback) {
        countryId = parseInt(countryId);

        if (isNaN(countryId) || countryId <= 0) {
            throw 'countryId required';
        }

        $.ajax({
            'url':  self.config.urls.getResorts,
            'data': {
                'country_id': countryId
            },
            'dataType': 'json',
            'success':  function (data) {
                if (self.$selectResorts.attr('data-countryId') != countryId) {
                    self.clearSelect(self.$selectResorts);
                }

                if ($.isArray(data) && data.length === 0) {
                    return self;
                }

                var _storage = [];

                $.each(data, function (resortId, resort) {
                    _storage.push('<option value="'+ resort.id +'">'+ resort.title +'</option>');
                });

                self.$selectResorts.removeClass('hidden').val('').append(_storage.join('')).attr('data-countryId', countryId);

                if (self.$selectResorts.attr('data-wrapper')) {
                    $(self.$selectResorts.attr('data-wrapper')).removeClass('hidden');
                }

                if ($.isFunction(callback)) {
                    callback();
                }

                self.$selectResorts.trigger('reloaded:suggest');
            },
            'error': function(e) {
                alert(e);
            }
        });
    };

    this.getHotels = function (resortId, callback) {
        if (!this.$selectHotels) {
            return this;
        }

        resortId = parseInt(resortId);

        if (isNaN(resortId) || resortId <= 0) {
            throw 'resortId required';
        }

        $.ajax({
            'url':      self.config.urls.getHotels,
            'data':     {
                'resort_id': resortId
            },
            'dataType': 'json',
            'success':  function(data) {
                if (self.$selectResorts.attr('data-resortId') != resortId) {
                    self.clearSelect(self.$selectHotels);
                }

                if ($.isArray(data)) {
                    return self;
                }

                var _storage = [];

                $.each(data, function (hotelId, hotel) {
                    _storage.push('<option value="'+ hotel.id +'">'+ hotel.title +'</option>');
                });

                self.$selectHotels.removeClass('hidden').val('').append(_storage.join('')).attr('data-resortId', resortId);

                if (self.$selectHotels.attr('data-wrapper')) {
                    $(self.$selectHotels.attr('data-wrapper')).addClass('hidden');
                }

                if ($.isFunction(callback)) {
                    callback();
                }
            },
            'error': function(e) {
                alert(e);
            }
        });

        return self;
    };

    this.fill = function () {
        if (this.$selectCountries.children('option').length < 2) {
            this.getCountries(function () {
                if (self.data.countryId > 0) {
                    self.$selectCountries.val(self.data.countryId);
                }
            });
        } else {
            if (this.data.countryId && this.data.countryId > 0) {
                this.$selectCountries.val(this.data.countryId);
            }
        }

        if (this.data.countryId > 0) {
            this.getResorts(
                this.data.countryId,
                function () {
                    if (self.data.resortId > 0) {
                        self.$selectResorts.val(self.data.resortId);
                    }
                }
            );

            if (this.getHotels && this.data.resortId > 0) {
                this.getHotels(
                    this.data.resortId,
                    function () {
                        if (self.data.hotelId > 0) {
                            self.$selectHotels.val(self.data.hotelId);
                        }
                    }
                );
            }
        }
    };

    this.clearSelect = function ($select) {
        $select.addClass('hidden').children('option:not([value=""])').remove();

        if ($select.attr('data-wrapper')) {
            $($select.attr('data-wrapper')).addClass('hidden');
        }

        return this;
    };
};