/**
 * Standart objects extend
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Lib
 * @copyright (c) 2012. Mikhail Miropolskiy. All Rights Reserved.
 */

/**
 * Remove DOM node yourself
 */
HTMLElement.prototype.remove = function() {
	this.parentNode.removeChild(this);
};

/**
 * Return random number between min and max
 * @param {Number} min
 * @param {Number} max
 * @return {Number} Random number
 */
Math.rand = function(min, max) {
	var argc = arguments.length;
	 if (argc === 0) {
		 min = 0;
		 max = 2147483647;
	 } else if (argc === 1) {
		 throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
	 }
	 return Math.floor(Math.random() * (max - min + 1)) + min;
};

/**
 * Return cookie value by name
 * @param {String} name
 * @return {String|Null} value
 */
document.getCookie = function (name) {
    var matches = document.cookie.match(name + '=(.*?)(;|$)');
    return matches ? matches[1] : null
};

/**
 * Set cookie
 * @param {String} name
 * @param {String} value
 * @param {Number} days
 */
document.setCookie = function (name, value, days) {
    var expires = new Date;
    expires.setDate(expires.getDate() + days);
    document.cookie = name + '=' + value + '; expires=' + expires.toUTCString();
};

/**
 * Sticky Footer jquery plugin
 * Resize element to fill full window height
 */
(function($) {
	var resizeEl = function (el) {	
		var diff = $(window).height() - $('body').height();
		
		el.height('');

		if (diff > 0) {
			el.height(el.height() + diff);
		}
	}
	
	var methods = {	
		init: function () {
			var el = $(this);
			
			$(window).load(function() {
				resizeEl(el);
			});
			$(window).resize(function() {
				resizeEl(el);
			});
		},
		refresh: function () {
			resizeEl($(this));
		}
    };

    $.fn.stickyFooter = function(method) {
		if (methods[method]) {
			methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else {
			methods.init.apply(this, arguments);
		}	

        return this;
    };
})(jQuery);