jQuery(document).ready(function () {
    var $select_country = $('#filter-country-id');
    var $select_resort  = $('#filter-resort-id');

    Suggest.initialize($select_country, $select_resort);

    $select_resort.bind('reloaded:suggest', function () {
        $select_resort.ikSelect('reset').ikSelect('redraw');
    });
});