$(document).ready(function () {
    var $price = $('#order_price');
    var CURRENT_ORDER_PRICE = $price.val();

    $price.change(function () {
        if (!confirm('Вы действительно хотите изменить цену?')) {
            $price.val(CURRENT_ORDER_PRICE);

            return false;
        }

        CURRENT_ORDER_PRICE = $price.val();
    });
});