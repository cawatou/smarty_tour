$(document).ready(function () {
    //scrollfix link
    $('a.scrollfix').click(function() {
        $.cookie('dx_scrollfix', $(window).scrollTop(), {path: "/"});
        return true;
    });

    if ($.cookie('dx_scrollfix')) {
        $(window).scrollTop($.cookie('dx_scrollfix'));
        $.cookie('dx_scrollfix', null, {path: "/"});
    }

    // blank link
    $('a.blank').click(function() {
        return !window.open(this.href);
    });

    if (!/msie/.test(navigator.userAgent.toLowerCase())) {
        $('.adm-tree .adm-node:last-child').css({'background-color':'#FFF'});
    }

    $('.btn-ref').click(function () {
        if ($(this).attr('data-href')) {
            if ($(this).attr('data-target')) {
                window.open($(this).attr('data-href'), $(this).attr('data-target'));
            } else {
                window.location = $(this).attr('data-href');
            }
        }
        return false;
    });

    $('.btn-preview').each(function() {
        $(this)
            .click(function() { return false; })
            .popover({
                html: true,
                content : '<img src="' + $(this).data('image-path') + '">'
            });
    });

	if (jQuery.fn.showPassword) {
		$(':password').showPassword({
			linkClass: 'form-password-toggle', //Class to use for the toggle link
			linkText: '<i class="fa fa-eye"></i>', //Text for the link
			showPasswordLinkText: '<i class="fa fa-eye-slash"></i>', //Text for the link when password is not masked
			showPasswordInputClass: 'form-password-showing', //Class for the text input that will show the password
			linkRightOffset: 12, //Offset from the right of the parent
			linkTopOffset: 32 //Offset from the top of the parent
		});	
	}

    if (jQuery.fn.stupidtable) {
        $('.stupidtable').stupidtable();
    }
});