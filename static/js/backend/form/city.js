jQuery(document).ready(function () {
    $(document).on(
        'click',
        '.add-attr',

        function (e) {
            var $this   = $(this);
            var $parent = $this.closest('.row-attr');
            var $clone  = $parent.clone();

            $parent.find('.add-attr').addClass('hidden');
            $parent.find('.remove-attr').removeClass('hidden');

            $clone.find('input, select, textarea').each(function () {
                var $input = $(this);

                if ($input.attr('type') === 'checkbox' || $input.attr('type') === 'radio') {
                    $input.removeAttr('checked');
                } else {
                    $input.val('');
                }
            });

            $clone.appendTo($parent.parent());

            return false;
        }
    ).on(
        'click',
        '.remove-attr',

        function () {
            if (confirm('Вы уверены?')) {
                $(this).closest('.row-attr').remove();
            }

            return false;
        }
    );
});