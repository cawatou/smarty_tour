(function($){
    $.fn.dxDatepicker = function() {
        if ($.fn.datepicker) {
            var $inp = $(this);
            $inp.datepicker({
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                showButtonPanel: false,
                beforeShow: function() {
                    setTimeout(function(){
                        $('.ui-datepicker').css('z-index', 3);
                    }, 0);
                }
            });
            if ($inp.data('change-year')) {
                $inp.datepicker("option", "changeYear", true);
            }

            if ($inp.data('change-month')) {
                $inp.datepicker("option", "changeMonth", true);
            }

            if ($inp.data('year-range')) {
                $inp.datepicker("option", "yearRange", $inp.data('year-range'));
            }

            if ($inp.closest('.input-group').find('.fa-calendar').length) {
                $inp.closest('.input-group').find('.input-group-addon').css('cursor', 'pointer').click(function() {
                    $inp.datepicker("show");
                })
            }

            $inp.change(function() {
                var dt = $(this).val();
                if (/^\d\d\.\d\d\.\d{4}$/.test(dt)) {
                    var
                        a0 = function(x) {
                            return (x < 10 ? '0' : '') + x;
                        },
                        t = dt.split('.'),
                        ndt = new Date (+t[2], t[1] - 1, +t[0]);

                    with (ndt) var tst = [a0(getDate()), a0(getMonth() + 1), getFullYear()].join('.');

                    if (tst == dt) {
                        return true;
                    }
                }
                $(this).val('');
            });
        }
    };
})(jQuery);

jQuery(function ($) {
    if (jQuery.fn.datepicker) {
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            weekHeader: 'Нед',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
    }

    $('input.datepicker').each(function() {
        $(this).dxDatepicker();
    });
});