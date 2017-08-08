$(document).ready(function () {
    $('.jumpy-wompy').each(function () {
        var $this = $(this);

        var $wrapper = $this.parents('.customer-data-element');

        var limit = $this.attr('data-limit');
        var $next = $wrapper.find($this.attr('data-next'));

        $this.bind('keyup', function (e) {
            if ($(this).val().length >= limit) {
                $next.focus();
            }
        });
    });
});