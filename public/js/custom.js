/*
Template Name: Manland - Bootstrap Light Real Estate HTML Template
Author: Webartinfo
Author URI: https://themeforest.net/user/webartinfothems
Version: 1.0
*/
/*
	-- Hover Nav
	-- Select2
*/
$(document).ready(function() {
    "use strict";

    // ===========Hover Nav============	
	$('.navbar-nav li.dropdown').hover(function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500);
	}, function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500);
	});
	
	// ===========Select2============	
	$(document).ready(function() {
      $('.select2').select2();
    });
	
});