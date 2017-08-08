/*
 * jQuery Show Password Plugin
 * http://github.com/davist11/jQuery-Show-Password
 *
 * Copyright (c) 2010 Trevor Davis (http://trevordavis.net)
 * Dual licensed under the MIT and GPL licenses.
 * Uses the same license as jQuery, see:
 * http://jquery.org/license
 *
 * @version 0.3
 *
 * Example usage:
 * $(':password').showPassword({
 *   linkClass: 'show-password-link',
 *   linkText: 'Show',
 *   showPasswordLinkText: 'Hide',
 *   showPasswordInputClass: 'password-showing',
 *   linkRightOffset: 0,
 *   linkTopOffset: 0
 * });
 */
;(function($) {
// Add button to show password in input field
$.fn.showPassword = function(options) {
  var opts = $.extend({}, $.fn.showPassword.defaults, options);

  return this.each(function() {
    var $this = $(this),
        elPosition = $this.position(),
        elWidth = $this.outerWidth(),
        elHeight = $this.outerHeight(),
        $parent = $this.parent(),
        parentHeight = $parent.height();

    // Support for the Metadata Plugin.
    var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
    
    $parent.height(parentHeight);
  
    //Create the link
    var $link = $('<a/>', {
      href: '#',
      'class': o.linkClass,
      click: function(e) {
        var $showPassInput = $parent.find('.'+o.showPasswordInputClass);
        if($this.css('display') === 'none') { //If the regular input is hidden, show it
          $(this).html('<span>' + o.linkText + '</span>');
          $this.val($showPassInput.val()).show();
          $showPassInput.hide();
        } else { //If the showing password input is hidden, show it
          $(this).html('<span>' + o.showPasswordLinkText + '</span>');
          $showPassInput.val($this.val()).show();
          $this.hide();
        }
        e.preventDefault();
      },
      css: {
        //left: elPosition.left + elWidth,
        right: o.linkRightOffset,
        top: o.linkTopOffset//elPosition.top + elHeight
      },
      html: '<span>' + o.linkText + '</span>'
    }).appendTo($parent);
    
    $link.hide();
    $this.keyup(function() {
        if ($this.length > 0) {
            $link.show();
        }
    })
    
    var $toggleAnchor = $parent.find('.'+o.linkClass),
        toggleAnchorWidth = $toggleAnchor.outerWidth(),
        toggleAnchorLeft = parseInt($toggleAnchor.css('left'), 10),
        toggleAnchorHeight = $toggleAnchor.outerHeight(),
        toggleAnchorTop = parseInt($toggleAnchor.css('top'), 10);
    /*
    $toggleAnchor.css({
      'left': (toggleAnchorLeft - toggleAnchorWidth - o.linkRightOffset),
      'top': (toggleAnchorTop - toggleAnchorHeight - o.linkTopOffset)
    });
    */
    //Create the input to switch
    $('<input/>', {
      'class': o.showPasswordInputClass,
      css: {
        display: 'none',
        left: elPosition.left,
        top: elPosition.top
      },
      type: 'text'
    }).appendTo($parent).addClass('form-control').attr('placeholder', $this.attr('placeholder'));
    
    //When form is submitted and password is hidden, update the password val
    $this.closest('form').bind('submit', function() {
      if($this.css('display') === 'none') {
        $this.val($this.siblings('.'+o.showPasswordInputClass).val());
      }
    });
    
  });
};

// default options
$.fn.showPassword.defaults = {
  linkClass: 'show-password-link',
  linkText: 'Show',
  showPasswordLinkText: 'Hide',
  showPasswordInputClass: 'password-showing',
  linkRightOffset: 0,
  linkTopOffset: 0
};

})(jQuery);