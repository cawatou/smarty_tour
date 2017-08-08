var Autocomplete = Base.extend({
    $input:           null,
    request_url:      null,
    typeahead_config: null,
    query:            null,
    process_request:  false,

    constructor: function ($input) {
        this.$input = $input;

        this.initialize();
        this.applyTypeahead();
    },

    initialize: function () {
        this.request_url = null;

        this.typeahead_config = {
            min_length: 3,
            max_items:  10
        };
    },

    applyTypeahead: function () {
        if (!jQuery.fn.typeahead) {
            throw 'Typeahead plugin required';
        }

        var that = this;

        this.$input.typeahead({
            source: function (query, process) {
                that.query = query;

                that.getList(process);
            },
            updater: function (item) {
                return that.typeaheadUpdater(item);
            },
            matcher: function (item) {
                if (!item) {
                    return -1;
                }

                return ~item.toLowerCase().indexOf(that.query.toLowerCase());
            },
            minLength: that.typeahead_config.min_length,
            items:     that.typeahead_config.max_items
        });
    },

    /**
     * Callback for typeahead's "updated" method
     *
     * @param {String} item Selected item
     * @return {String}
     */
    typeaheadUpdater: function (item) {
        var typeahead = Cache.get(this.getCacheGroup(), 'typeahead');

        if (!typeahead) {
            return item;
        }

        if (typeof typeahead[item] === 'undefined') {
            return item;
        }

        this.$input.parent().find('input:hidden').val(typeahead[item].id);

        this.$input.trigger('typeahead:update', [item, typeahead[item]]);

        return item;
    },

    getList: function (process) {
        if (this.getCache() && this.getCache().length) {
            return process(this.getCache());
        }

        return this.requestData(process);
    },

    getRequestParams: function () {
        return {};
    },

    /**
     * Returns cache group. Usable for different autocompletes on the same page
     *
     * @return {String}
     */
    getCacheGroup: function () {
        return '';
    },

    /**
     * Returns cache key. Usable to separate data, with different filters applied
     *
     * @return {String}
     */
    getCacheKey: function () {
        return '';
    },

    getCache: function () {
        var key = this.getCacheKey();

        if (key === '') {
            // Uncachable. Return empty array, to prevent any requests
            return [];
        }

        if (Cache.get(this.getCacheGroup(), key) !== null) {
            return Cache.get(this.getCacheGroup(), key);
        }

        return null;
    },

    setCache: function (data) {
        var key = this.getCacheKey();

        if (key === '') {
            // Uncacheable. Return empty array, to prevent any requests
            return data;
        }

        Cache.set(this.getCacheGroup(), key, data);

        return this;
    },

    /**
     * Parses data after request
     *
     * @param {Object} data Data to parse
     * @return {Object}
     */
    parseResults: function (data) {
        return data;
    },

    /**
     * Requests data from remote data (you'll get here only if you'll miss cache)
     *
     * @param {Function} process Process function
     */
    requestData: function (process) {
        var that = this;

        if (this.process_request) {
            return;
        }

        this.process_request = true;

        jQuery.ajax(
            {
                url:      this.request_url,
                timeout:  3000,
                type:     'POST',
                dataType: 'json',
                data:     this.getRequestParams(),
                error:    function () {
                    alert('Server not responding. Please try again later');
                    that.process_request = false;

                    return false;
                },
                complete: function () {
                    this.process_request = false;
                }
            }
        )
        .done(
            function (data) {
                that.requestDataSuccessful(data, process);

                this.process_request = false;
            }
        );
    },

    requestDataSuccessful: function (data, process) {
        process(data);
    }
});

var AutocompleteCountry = Autocomplete.extend({
    initialize: function () {
        // Call parent initialization, prevent code duplication
        this.base();

        this.typeahead_config = {
            min_length: 1,
            max_items:  5
        };

        this.request_url = '/ajax/suggest/countries';
    },

    /**
     * Returns cache group. Usable for different autocompletes of different groups on the same page
     *
     * @return {String}
     */
    getCacheGroup: function () {
        return 'countries';
    },

    getCacheKey: function () {
        return 'c';
    },

    /**
     * Parses data after request
     *
     * @param {Object} data Data to parse
     * @return {Object}
     */
    parseResults: function (data) {
        var result    = [];
        var typeahead = {};

        $.each(data, function (key, object) {
            var _parsed = {};

            _parsed = {
                id:    object.id,
                name:  object.title
            };

            typeahead[object.title] = _parsed;

            result.push(object.title);
        });

        // Cache typeahead's value, usable for different typeaheads on the same page
        Cache.set(this.getCacheGroup(), 'typeahead', typeahead);

        return result;
    },

    getRequestParams: function () {
        var params = {};

        params.query = this.query;

        return params;
    },

    requestDataSuccessful: function (data, process) {
        if (typeof data !== 'object' || data.length === 0) {
            return [];
        }

        var parsed_data = this.parseResults(data);

        this.setCache(parsed_data);

        process(parsed_data);
    }
});

var AutocompleteResort = Autocomplete.extend({
    initialize: function () {
        // Call parent initialization, prevent code duplication
        this.base();

        this.typeahead_config = {
            min_length: 3,
            max_items:  5
        };

        this.request_url = '/ajax/suggest/resorts';
    },

    /**
     * Returns cache group. Usable for different autocompletes of different groups on the same page
     *
     * @return {String}
     */
    getCacheGroup: function () {
        return 'resorts';
    },

    getCacheKey: function () {
        var params = this.getRequestParams();

        var key = '';

        if (params.country_id) {
            key += 'c'+ params.country_id;
        }

        return key;
    },

    /**
     * Parses data after request
     *
     * @param {Object} data Data to parse
     * @return {Object}
     */
    parseResults: function (data) {
        var result    = [];
        var typeahead = {};

        $.each(data, function (key, object) {
            var _parsed = {};

            _parsed = {
                id:    object.id,
                name:  object.title
            };

            typeahead[object.title] = _parsed;

            result.push(object.title);
        });

        // Cache typeahead's value, usable for different typeaheads on the same page
        Cache.set(this.getCacheGroup(), 'typeahead', typeahead);

        return result;
    },

    getRequestParams: function () {
        var params = {};

        if (typeof Suggest !== 'undefined') {
            if (Suggest.data.countryId) {
                params.country_id = Suggest.data.countryId;
            }
        }

        if (this.$input.attr('data-chained')) {
            var $chained = $(this.$input.attr('data-chained'));

            if ($chained.length && $chained.val() && $chained.attr('data-name')) {
                if ($chained.data('sys.autocomplete')) {
                    // Chained input is autocomplete, real value stored in the hidden input next to the input
                    params[$chained.attr('data-name')] = $chained.parent().find('input:hidden').val();
                } else {
                    params[$chained.attr('data-name')] = $chained.val();
                }
            }
        }

        params.query = this.query;

        return params;
    },

    requestDataSuccessful: function (data, process) {
        if (typeof data !== 'object' || data.length === 0) {
            return [];
        }

        var parsed_data = this.parseResults(data);

        this.setCache(parsed_data);

        process(parsed_data);
    }
});

var AutocompleteHotel = Autocomplete.extend({
    initialize: function () {
        // Call parent initialization, prevent code duplication
        this.base();

        this.request_url = '/ajax/suggest/hotels';
    },

    /**
     * Returns cache group. Usable for different autocompletes of different groups on the same page
     *
     * @return {String}
     */
    getCacheGroup: function () {
        return 'hotels';
    },

    getCacheKey: function () {
        var params = this.getRequestParams();

        var key = 'h!';

        if (params.country_id) {
            key += 'c'+ params.country_id;

            if (params.resort_id) {
                key += '_';
            }
        }

        if (params.resort_id) {
            key += 'r'+ params.resort_id;
        }

        return key;
    },

    /**
     * Parses data after request
     *
     * @param {Object} data Data to parse
     * @return {Object}
     */
    parseResults: function (data) {
        var result    = [];
        var typeahead = {};

        $.each(data, function (key, object) {
            var _parsed = {};

            _parsed = {
                id:    object.id,
                name:  object.title,
                stars: object.stars
            };

            typeahead[object.title] = _parsed;

            result.push(object.title);
        });

        // Cache typeahead's value, usable for different typeaheads on the same page
        Cache.set(this.getCacheGroup(), 'typeahead', typeahead);

        return result;
    },

    getRequestParams: function () {
        var params = {};

        if (typeof Suggest !== 'undefined') {
            if (Suggest.data.countryId) {
                params.country_id = Suggest.data.countryId;
            }

            if (Suggest.data.resortId) {
                params.resort_id = Suggest.data.resortId;
            }
        }

        params.query = this.query;

        return params;
    },

    requestDataSuccessful: function (data, process) {
        if (typeof data !== 'object' || data.length === 0) {
            return [];
        }

        var parsed_data = this.parseResults(data);

        this.setCache(parsed_data);

        process(parsed_data);
    }
});

var AutocompleteHotelNoncacheable = AutocompleteHotel.extend({
    initialize: function () {
        // Call parent initialization, prevent code duplication
        this.base();

        this.request_url = '/ajax/suggest/hotels';
    },

    /**
     * Returns cache group. Usable for different autocompletes of different groups on the same page
     *
     * @return {String}
     */
    getCacheGroup: function () {
        return 'hotels';
    },

    getCacheKey: function () {
        var params = this.getRequestParams();

        var key = 'h_'+ params.query;

        return key;
    },

    /**
     * Parses data after request
     *
     * @param {Object} data Data to parse
     * @return {Object}
     */
    parseResults: function (data) {
        var result    = [];
        var typeahead = {};

        $.each(data, function (key, object) {
            var _parsed = {};

            _parsed = {
                id:    object.id,
                name:  object.title,
                stars: object.stars
            };

            typeahead[object.title +' '+ object.stars] = _parsed;

            result.push(object.title +' '+ object.stars);
        });

        // Cache typeahead's value, usable for different typeaheads on the same page
        Cache.set(this.getCacheGroup(), 'typeahead', typeahead);

        return result;
    },

    getRequestParams: function () {
        var params = {};

        if (typeof Suggest !== 'undefined') {
            if (Suggest.data.countryId) {
                params.country_id = Suggest.data.countryId;
            }

            if (Suggest.data.resortId) {
                params.resort_id = Suggest.data.resortId;
            }
        }

        params.query_based = 1;

        params.query = this.query;

        return params;
    },

    requestDataSuccessful: function (data, process) {
        if (typeof data !== 'object' || data.length === 0) {
            return [];
        }

        var parsed_data = this.parseResults(data);

        this.setCache(parsed_data);

        process(parsed_data);
    },

    /**
     * Requests data from remote data (you'll get here only if you'll miss cache)
     *
     * @param {Function} process Process function
     */
    requestData: function (process) {
        var that = this;

        jQuery.ajax(
            {
                url:      this.request_url,
                timeout:  3000,
                type:     'POST',
                dataType: 'json',
                data:     this.getRequestParams(),
                error:    function () {
                    return false;
                }
            }
        )
        .done(
            function (data) {
                that.requestDataSuccessful(data, process);
            }
        );
    }
});

var Cache = new function () {
    this.cache = {};

    this.get = function (group, key) {
        if (!this.cache[group] || !this.cache[group][key]) {
            return null;
        }

        return this.cache[group][key];
    };

    this.set = function (group, key, value) {
        if (!this.cache[group]) {
            this.cache[group] = [];
        }

        if (!this.cache[group][key]) {
            this.cache[group][key] = [];
        }

        this.cache[group][key] = value;

        return this.cache;
    };
};