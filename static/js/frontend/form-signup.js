$(document).ready(function() {
    var $form_base     = $('#form-signup-sms');
    var $form_base_tel = $form_base.find('.input-subscribe');

    var _is_unsub = 0;

    var $form_code      = $('#form-signup-sms-code');
    var $form_code_code = $form_code.find('.input-code-code');

    var $form_congratz = $('#form-signup-sms-congratz');

    $form_base.find('.unsubscribe').click(function () {
        _is_unsub = 1;

        $form_base.submit();

        return false;
    });

    $form_base.find('.subscribe').click(function () {
        _is_unsub = 0;

        $form_base.submit();

        return false;
    });

    $form_base.submit(function () {
        if ($form_base_tel.val().length !== 11) {
            alert('Неверно набран номер');

            return false;
        }

        $.ajax({
            type:     'GET',
            url:      $form_base.attr('action'),
            data:     'phone='+ $form_base_tel.val(),
            dataType: 'JSON'
        }).done(function (data) {
            if (data.message !== 'ok') {
                alert('Ошибка, Пожалуйста повторите позже');

                return false;
            }

            $form_base.hide();
            $form_code.show();
        });

        return false;
    });

    $form_code.submit(function () {
        if (!$form_code_code.val()) {
            alert('Введите код подтверждения');

            return false;
        }

        $.ajax({
            type:     'GET',
            url:      $form_code.attr('action'),
            data:     'sms_code='+ $form_code_code.val() +'&phone='+ $form_base_tel.val() +'&unsub='+ _is_unsub,
            dataType: 'JSON'
        }).done(function (data) {
            if (data.message !== 'ok') {
                alert('Ошибка, Пожалуйста повторите позже');

                return false;
            }

            $form_code.hide();
            $form_congratz.show();

            if (_is_unsub) {
                $form_congratz.text('Отписка прошла успешно');
            }
        });

        return false;
    });
});