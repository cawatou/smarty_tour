$(document).ready(function () {
    var $modal_passport = $('#modal-passport');

    if ($modal_passport.length) {
        var $pass_country     = $('#passport-country');
        var $pass_date_start  = $('#passport-date-start');
        var $pass_date_end    = $('#passport-date-end');
        var $pass_result      = $('#passport-result');
        var $pass_result_date = $('#passport-result-date');

        $modal_passport.on('shown.bs.modal', function () {
            $('body').addClass('modal-open');

            var $this = $(this);

            $this.find('select:not([data-select-initialized])').ikSelect({
                autoWidth:   false,
                ddFullWidth: false
            }).attr('data-select-initialized', '');

            if (!$pass_date_start.hasClass('hasDatepicker')) {
                $pass_date_start.datepicker({
                    defaultDate:    '+1w',
                    changeMonth:    false,
                    numberOfMonths: 2,
                    dateFormat:     'dd.mm.yy',
                    onSelect:       function () {
                        passportCalculateDate();
                    },
                    onClose:        function (selectedDate) {
                        $pass_date_end.datepicker('option', 'minDate', selectedDate);
                    }
                });
            }

            if (!$pass_date_end.hasClass('hasDatepicker')) {
                $pass_date_end.datepicker({
                    defaultDate:    '+1w',
                    changeMonth:    false,
                    numberOfMonths: 2,
                    dateFormat:     'dd.mm.yy',
                    onSelect:       function () {
                        passportCalculateDate();
                    },
                    onClose: function( selectedDate ) {
                        $pass_date_start.datepicker('option', 'maxDate', selectedDate);
                    }
                });
            }
        }).on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });

        $modal_passport.find('.input-text, select').change(function () {
            passportCalculateDate();
        });

        function passportCalculateDate () {
            if (!$pass_country.val() || !$pass_date_start.val() || !$pass_date_end.val()) {
                $pass_result.hide();
                $pass_result_date.text('');

                return;
            }

            var duration   = parseInt($pass_country.find('option#passport-country-option-'+ $pass_country.val()).attr('data-days'));
            var date_start = $pass_date_start.val();
            var date_end   = $pass_date_end.val();

            var _date_start = new Date(date_start.substring(6, 10), date_start.substring(3, 5) - 1, date_start.substring(0, 2));
            var _date_end   = new Date(date_end.substring(6, 10),   date_end.substring(3, 5) - 1,   date_end.substring(0, 2));

            var date_millisec       = 24 * 60 * 60 * 1000;
            var total_tour_duration = (_date_end.getTime() - _date_start.getTime()) / date_millisec;

            if (isNaN(duration) || duration <= 0 || _date_start >= _date_end) {
                $pass_result.hide();
                $pass_result_date.text('');

                return;
            }

            _date_start.setDate(_date_start.getDate() + duration + total_tour_duration);

            $pass_result.show();
            $pass_result_date.text(
                (_date_start.getDate() < 10 ? '0' : '')
                    +
                _date_start.getDate()
                    +
                '.'
                    +
                (_date_start.getMonth() < 9 ? '0' : '')
                    +
                (_date_start.getMonth() + 1)
                    +
                '.'
                    +
                _date_start.getFullYear()
            );
        };
    }

    var $modal_complain_activator = $('#modal-complain-activator');
    var $modal_complain = $('#modal-complain');

    var selected_staff_id = false;

    if ($modal_complain_activator.length) {
        $modal_complain_activator.on('click', function () {
            if ($modal_complain.hasClass('modal-loaded')) {
                $modal_complain.modal('show');

                return false;
            }

            $.ajax({
                url:      $(this).attr('data-source') +'?ajax',
                dataType: 'json'
            })
            .done(function (data) {
                $modal_complain.addClass('modal-loaded').find('.modal-body-inner').html(data.html);

                $modal_complain.modal('show');
            });

            return false;
        });
    }

    if ($modal_complain.length) {
        $modal_complain.on('shown.bs.modal', function () {
            $('body').addClass('modal-open');

            var $this = $(this);

            $this.find('select:not([data-select-initialized])').ikSelect({
                autoWidth:   false,
                ddFullWidth: false
            }).attr('data-select-initialized', '');

            $this.find('.captcha-reload').click(function() {
                var $this = $(this).find('img'),
                    n     = new Date(),
                    url   = $this.attr('src').split('?');

                $this.attr('src', url[0] +'?'+ n.getTime());

                return false;
            });

            $this.find('.input-text-phone-number').mask('7 (999) 999-99-99');
        }).on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });

        $modal_complain.on('change', '.ik-select-office-id', function () {
            var $this   = $(this);
            var $select = $modal_complain.find('.ik-select-staff-id');

            $select.find('optgroup').remove();

            if (!$this.val()) {
                return;
            }

            var html = [];

            var _city_val   = $this.val();
            var _office_val = $.trim($this.find(':selected').text());

            // Due to last change
            _city_val = null;

            $.each(_json_office_staffs, function (city_id, office_staff) {
                if (_city_val === null || _city_val === city_id) {
                    $.each(office_staff, function (office_name, staffs) {
                        if (jsonLength(staffs) > 0) {
                            if (_office_val === null || _office_val === office_name) {
                                html.push('<optgroup label="'+ office_name.replace(/"/g, "&quot;") +'">');

                                $.each(staffs, function (staff_id, staff_name) {
                                    html.push('<option value="'+ staff_id +'"'+ (selected_staff_id == staff_id ? ' selected="selected"' : '') +'>'+ staff_name +'</option>');
                                });

                                html.push('</optgroup>');
                            }
                        }
                    });
                }
            });

            $select.append(html.join(' '));

            $select.ikSelect('reset');
        });
    }

    $(document).on('submit', '.modal-feedback-quality-form', function () {
        var $this = $(this);

        $.ajax({
            url:      $this.attr('action') +'?ajax',
            type:     'POST',
            data:     $this.serialize(),
            dataType: 'json'
        }).done(function (data) {
            if (data.html) {
                $modal_complain.find('.modal-body-inner').html(data.html);

                setTimeout(
                    function () {
                        if (typeof data.data.feedback_extended_data !== 'undefined') {
                            selected_staff_id = data.data.feedback_extended_data.staff_id;
                        }

                        // Just in case
                        if (!selected_staff_id) {
                            selected_staff_id = false;
                        }

                        $modal_complain.trigger('shown.bs.modal').find('.ik-select-office-id').change();
                    },
                    0
                );
            }
        });

        return false;
    });

    var $modal_callback_activator = $('#modal-callback-activator');
    var $modal_callback = $('#modal-callback');

    if ($modal_callback_activator.length) {
        $modal_callback_activator.on('click', function () {
            if ($modal_callback.hasClass('modal-loaded')) {
                $modal_callback.modal('show');

                return false;
            }

            $.ajax({
                url:      $(this).attr('data-source') +'?ajax',
                dataType: 'json'
            })
            .done(function (data) {
                $modal_callback.addClass('modal-loaded').find('.modal-body-inner').html(data.html);

                $modal_callback.modal('show');
            });

            return false;
        });
    }

    if ($modal_callback.length) {
        $modal_callback.on('shown.bs.modal', function () {
            $('body').addClass('modal-open');

            var $this = $(this);

            $this.find('select:not([data-select-initialized])').ikSelect({
                autoWidth:   false,
                ddFullWidth: false
            }).attr('data-select-initialized', '');

            var $request_office_other = $this.find('#request_extended_office_other');

            $this.find('#request_extended_office').ikSelect().change(function () {
                var $this = $(this);

                if ($this.val() === 'other') {
                    $request_office_other.show();
                } else {
                    $request_office_other.hide();
                }
            });

            $this.find('.captcha-reload').click(function() {
                var $this = $(this).find('img'),
                    n     = new Date(),
                    url   = $this.attr('src').split('?');

                $this.attr('src', url[0] +'?'+ n.getTime());

                return false;
            });

            $this.find('.input-text-phone-number').mask('7 (999) 999-99-99');
        }).on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });
    }

    $(document).on('submit', '.modal-request-callback-form', function () {
        var $this = $(this);

        $.ajax({
            url:      $this.attr('action') +'?ajax',
            type:     'POST',
            data:     $this.serialize(),
            dataType: 'json'
        }).done(function (data) {
            if (data.html) {
                $modal_callback.find('.modal-body-inner').html(data.html);

                if ($modal_callback.find('.form-successful').length) {
                    $modal_callback.removeClass('modal-loaded');
                }

                setTimeout(
                    function () {
                        $modal_callback.trigger('shown.bs.modal');
                    },
                    0
                );
            }
        });

        return false;
    });

    function jsonLength (json) {
        var key, count = 0;

        for (key in json) {
            if (json.hasOwnProperty(key)) {
                count++;
            }
        }

        return count;
    }

    var $cityHelperTooltip = $('.city-tooltip-helper');

    if ($cityHelperTooltip.length) {
        if (!$.cookie('user_are_we_correct_u')) {
            $cityHelperTooltip.parent().show();
        }

        $cityHelperTooltip.find('.city-tooltip-helper-ok').click(function () {
            $cityHelperTooltip.fadeOut('fast');

            $.cookie('user_are_we_correct_u', true, {expires: 30, path: '/'});

            return false;
        });

        $cityHelperTooltip.find('.city-tooltip-helper-cancel').click(function () {
            $cityHelperTooltip.fadeOut('fast', function () {
                $.cookie('user_are_we_correct_u', true, {expires: 30, path: '/'});

                $('.header-city-current').click();
            });


            return false;
        });
    }
});