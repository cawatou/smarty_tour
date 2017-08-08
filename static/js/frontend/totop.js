jQuery(function($){
    var $toTop = $('.toTop');
    var params = {
        UP: {
            scroll: 0,
            selector: 'toTop-up',
            text: '&uarr; Наверх'
        },
        DOWN: {
            scroll: null,
            selector: 'toTop-down',
            text: '&darr; Назад'
        }
    };
    var _scroll = function() {
        if ($(window).scrollTop() > 200) {
            $toTop.fadeIn(300);
        } else {
            if (!$toTop.hasClass('toTop-process')) {
                if ($toTop.hasClass(params.UP.selector)) {
                    $toTop.fadeOut(300);
                }

                if ($toTop.hasClass(params.DOWN.selector)) {
                    $toTop
                        .removeClass(params.DOWN.selector)
                        .addClass(params.UP.selector)
                        .find('a').html(params.UP.text);
                }
            }
        }

        if ($(window).scrollTop() == 0 || $(window).scrollTop() == params.DOWN.scroll) {
            $toTop.removeClass('toTop-process');
        }
    }

    $(window).bind('scroll', _scroll);
    _scroll();

    $toTop.click(function(){
        var scroll = 0;
        if ($toTop.hasClass(params.UP.selector)) {
            params.DOWN.scroll = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
            $toTop.removeClass(params.UP.selector).addClass(params.DOWN.selector);
        } else if ($toTop.hasClass(params.DOWN.selector)) {
            scroll = params.DOWN.scroll;
            $toTop.removeClass(params.DOWN.selector).addClass(params.UP.selector);
        }

        $toTop.addClass('toTop-process');

        $('body,html').stop().animate({
            scrollTop: scroll
        }, 800, function() {
            if ($toTop.hasClass(params.UP.selector)) {
                $toTop.find('a').html(params.UP.text);
            } else if ($toTop.hasClass(params.DOWN.selector)) {
                $toTop.find('a').html(params.DOWN.text);
            }
        });
        return false;
    });
});
