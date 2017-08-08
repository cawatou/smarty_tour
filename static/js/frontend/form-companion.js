$(document).ready(function () {
    var now_t = new Date();
    var now   = new Date(now_t.getFullYear(), now_t.getMonth(), now_t.getDate(), 0, 0, 0, 0);

    var $date_from = $('#form_companion_date_from');
    var $date_to   = $('#form_companion_date_to');

    var $checkin = $date_from.datepicker({
        weekStart: 1,
        format:    'dd.mm.yyyy',
        onRender:  function (date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        },
        onClose:   function (selectedDate) {
            $date_to.datepicker('option', 'minDate', selectedDate);
        }
    }).on('changeDate', function (event) {
        if (event.date.valueOf() > $checkout.date.valueOf()) {
            var newDate = new Date(event.date);
            newDate.setDate(newDate.getDate() + 1);
            $checkout.setValue(newDate);
        }

        $checkin.hide();
        $date_to.focus();
    }).data('datepicker');

    var $checkout = $date_to.datepicker({
        weekStart: 1,
        format:    'dd.mm.yyyy',
        onRender:  function (date) {
            return date.valueOf() <= $checkin.date.valueOf() ? 'disabled' : '';
        },
        onClose:   function (selectedDate) {
            $date_from.datepicker('option', 'maxDate', selectedDate);
        }
    }).on('changeDate', function () {
        $checkout.hide();
    }).data('datepicker');

    $('#form_companion_user_phone').mask('7 (999) 999-99-99');
});