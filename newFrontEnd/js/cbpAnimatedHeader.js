/**
 * cbpAnimatedHeader.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
		header = document.querySelector( '.navbar-default' ),
		didScroll = false;

	function init() {
		classie.add( header, 'navbar-shrink' );
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();

})();
