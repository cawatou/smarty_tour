var 
tracking_wait = false,
DxTracking = function() {
    if (!tracking_wait) {
        tracking_wait = true;
        jQuery.getJSON(TRACKING_URL, function(data) {
            if (data.code == 'NOCHANGES') {
                tracking_wait = false;
            } else if (data.code == 'ISCHANGES') {

                $('#modal_tracking').modal('show');
                $('<audio autoplay loop><source src="' + TRACKING_MP3 + '" type="audio/ogg"></audio>').appendTo('body');
            }

        });
    }
}

jQuery(document).ready(function() {
    setInterval(DxTracking, 60000);
});