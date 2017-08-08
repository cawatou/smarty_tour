$(document).ready(function () {
    var $form_feedback_propose = $('.form-feedback-propose');

    var _loaded = false;

    $('.ik-select-office-id').change(function () {
        if (!_loaded) {
            _loaded = true;

            return;
        }

        var $this   = $(this);
        var $select = $form_feedback_propose.find('.ik-select-staff-id');

        $select.find('optgroup').remove();

        if (!$this.val()) {
            return;
        }

        var html = [];

        var _city_val   = $this.val();
        var _office_val = $.trim($this.find(':selected').text());

        // Due to last change
        _city_val = null;

        var selected_staff_id = $select.attr('data-value');

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

    function jsonLength (json) {
        var key, count = 0;

        for (key in json) {
            if (json.hasOwnProperty(key)) {
                count++;
            }
        }

        return count;
    }
});