jQuery.fn.center = function () {
    this.css('position','absolute');
    this.css('top', ($(window).height() - this.height()) / 2 + $(window).scrollTop() + 'px');
    this.css('left', ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + 'px');
    return this;
};

jQuery.fn.isIE = function() {
    return navigator.userAgent.match(/msie/);
};

jQuery.fn.imgReload = function () {
    var n = new Date(),
        url = $(this).attr('src').split('?');
    $(this).attr('src', url[0] + '?' + n.getTime());
    return this;
};

$(document).ready(function() {
    // blank link
    $('a.blank').click(function() {
        return !window.open(this.href);
    });

    $('.site-footer .icon-secure-login').click(function () {
        var $this   = $(this);
        var $parent = $this.parent();

        if ($parent.hasClass('activated')) {
            $parent.removeClass('activated');
        } else {
            $parent.addClass('activated');
        }
    });

    $('.captcha-reload').click(function() {
        var $this = $(this).find('img'),
            n     = new Date(),
            url   = $this.attr('src').split('?');

        $this.attr('src', url[0] +'?'+ n.getTime());

        return false;
    });

    if ($.fn.fancybox) {
        $('.fancybox-hotels').fancybox();
    }

    if ($('.jcarousel').length) {
        $('.jcarousel').each(function () {
            var $carousel = $(this);

            $carousel.jcarousel();

            $carousel.parent().find('.jcarousel-control-prev')
                .on('jcarouselcontrol:active', function () {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function () {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '-=1'
                });

            $carousel.parent().find('.jcarousel-control-next')
                .on('jcarouselcontrol:active', function () {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function () {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '+=1'
                });

            if ($carousel.parent().find('ul li').length <= 4) {
                $carousel.parent().find('.jcarousel-control-prev, .jcarousel-control-next').addClass('inactive');
            }
        });

    }

    // captcha reload
    $('body, .modal').on('click', 'img.captcha-reload', function() {
        $(this).imgReload();
        return false;
    });

    var $tooltip_city = $('.city-tooltip-helper');

    $('.header-city-current').click(function () {
        var $target = $(this).next();

        if ($target.hasClass('show-durpdown')) {
            $target.removeClass('show-durpdown');
        } else {
            $target.addClass('show-durpdown');
        }

        if ($tooltip_city.length) {
            $tooltip_city.remove();
        }

        return false;
    });

    $('.cities-list-wrapper').click(function (e) {
        if (e.target.nodeName === 'A') {
            return true;
        }

        return false;
    });

    $('body').bind('click', function () {
        $('.cities-list-wrapper').removeClass('show-durpdown');
    });

    // fancybox
    if (jQuery.fn.fancybox) {
        $('a.fancy').fancybox({
            'transitionIn': 'none',
            'transitionOut': 'none',
            'nextEffect': 'fade',
            'prevEffect': 'fade',
            'titleShow': false
        });
    }

    if (jQuery.fn.waterwheelCarousel) {
        $('[data-waterwheel]').each(function () {
            var $this = $(this);

            var data = {
                autoPlay:      $this.attr('data-waterwheel-interval'),
                flankingItems: 9
            };

            if (!$this.attr('data-waterwheel-interval')) {
                data.autoPlay = 0;
            }

            if (jQuery.fn.fancybox && $this.attr('data-waterwheel-fancybox')) {
                data.clickedCenter = function ($item) {
                    $.fancybox.open(
                        {
                            href:  $item.attr('data-waterwheel-source'),
                            title: $item.attr('alt')
                        }
                    );
                };
            }

            $this.waterwheelCarousel(data);
        });
    }

    if (jQuery.fn.ikSelect) {
        $('.ik-select').ikSelect({
            autoWidth:   false,
            ddFullWidth: false
        });
    }

    var $city_selector = $('.city-selector');

    if ($city_selector.find('ul li').length) {
        $city_selector.find('ul').slideUp(0);

        $city_selector.on('click', function (e) {
            if ($city_selector.hasClass('opened')) {
                $city_selector.removeClass('opened').find('ul').slideUp('fast');

                return true;
            }

            $city_selector.addClass('opened').find('ul').slideDown('fast');

            e.stopPropagation();
        });

        $('body').bind('click', function () {
            if ($city_selector.hasClass('opened')) {
                $city_selector.removeClass('opened').find('ul').slideUp('fast');
            }
        });
    }

    var $hotel_search_tour_input = $('#hotel_seach_tour');

    if ($hotel_search_tour_input.length && window.AutocompleteHotelNoncacheable) {
        $hotel_search_tour_input.data('sys.autocomplete', new AutocompleteHotelNoncacheable($hotel_search_tour_input)).on('typeahead:update', function (e, item, data) {
            window.location.href = $(this).attr('data-href') + data.id;
        });
    }
});