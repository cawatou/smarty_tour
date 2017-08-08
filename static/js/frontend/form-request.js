$(document).ready(function() {
    $('#request_form_extended_date_start').datepicker({
        defaultDate:    '+1w',
        changeMonth:    false,
        numberOfMonths: 2,
        dateFormat:     'dd.mm.yy',
        onClose:        function (selectedDate) {
            $('#request_form_extended_date_end').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#request_form_extended_date_end').datepicker({
        defaultDate:    '+1w',
        changeMonth:    true,
        numberOfMonths: 2,
        dateFormat:     'dd.mm.yy',
        onClose:        function (selectedDate) {
            $('#request_form_extended_date_start').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('#request_form_user_phone').mask('7 (999) 999-99-99');

    var $request_office_other = $('#request_extended_office_other');

    $('#request_extended_office').ikSelect().change(function () {
        var $this = $(this);

        if ($this.val() === 'other') {
            $request_office_other.show();
        } else {
            $request_office_other.hide();
        }
    });

    var $form = $('#form-request');

    $form.submit(function () {
        if ($form.find('input[type="hidden"][name="request_custom_staff"]').length === 0) {
            $form.append('<input type="hidden" name="request_custom_staff" value="1">');
        }
    });
});