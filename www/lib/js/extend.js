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
 * Format a number with grouped thousands
 * @param {Number} decimals
 * @param {String} dec_point
 * @param {String} thousands_sep
 * @return {String} A formatted version of number
 */
String.prototype.numberFormat = function ( decimals, dec_point, thousands_sep) {
	var number = (this + '').replace(/[^0-9+\-Ee.]/g, ''),
		n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
};
Number.prototype.numberFormat = String.prototype.numberFormat;

/**
 * Return cookie value by name
 * @param {String} name
 * @return {String|Null} value
 */
document.getCookie = function (name) {
    var matches = document.cookie.match(name + '=(.*?)(;|$)');
    return matches ? matches[1] : null;
};

/**
 * Set cookie
 * @param {String} name
 * @param {String} value
 * @param {Number} days
 */
document.setCookie = function (name, value, days) {
    var expires = new Date();
    expires.setDate(expires.getDate() + days);
    document.cookie = name + '=' + value + '; expires=' + expires.toUTCString();
};

/**
 * Add link to bookmarks
 * Use: <a href="http://example.com" onclick="return AddFavorite(this);"></a>
 * @param {Object} el Tag "a" dom node
 * @return {Boolean} Follow url or not
 */
window.AddFavorite = function (el) {
	var title = document.title,
		url = window.location.href;

	try {
		// Internet Explorer
		window.external.AddFavorite(url, title);
	} catch (e) {
		try {
			// Mozilla
			window.sidebar.addPanel(title, url, '');
		}
		catch (e) {
			// Opera
			if (typeof(opera) == 'object') {
				el.rel = 'sidebar';
				el.title = title;
				el.url = url;
				el.href = url;

				return true;
			} else {
				// Unknown
				alert('Нажмите Ctrl+D чтобы добавить страницу в закладки');
			}
		}
	}

	return false;
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