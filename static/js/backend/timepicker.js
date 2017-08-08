$(document).ready(function () {
    if (jQuery.fn.timepicker) {
        jQuery.timepicker.regional['ru'] = {
            currentText: 'Сейчас',
            closeText: 'Готово',
            amNames: ['AM', 'A'],
            pmNames: ['PM', 'P'],
            timeFormat: 'HH:mm',
            timeSuffix: '',
            timeOnlyTitle: 'Выберите время',
            timeText: 'Время',
            hourText: 'Часов',
            minuteText: 'Минут',
            secondText: 'Секунд',
            millisecText: 'Миллисекунд',
            timezoneText: 'Временная зона'
        };

        jQuery.timepicker.setDefaults(jQuery.timepicker.regional['ru']);
        $('input.timepicker').timepicker({
            showButtonPanel: false
        });
        $('input.datetimepicker').datetimepicker({
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            showButtonPanel: false
        });
    }
});