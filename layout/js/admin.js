$(function() {
	"use strict";
	//Hide placeholder on form focus
	$("[placeholder]").focus(function() {
		$(this).attr("data-text", $(this).attr("placeholder"));
		$(this).attr("placeholder", "");
	}).blur(function() {
		$(this).attr("placeholder", $(this).attr("data-text"));
	})

	// Add asterisk after input fields
	$('input').each(function() {
		if($(this).attr('required') === 'required') {
			$(this).after('<span class="asterisk">*</span>');
		}
	});

	// Show-hide password field by clicking the eye icon
	/*
	$('.show-pass').onclick(
		if($('.password').attr()){}; // I stopped here because I fogot how to select element by a specific attribute
		function() {$('.password').attr('type', 'text');},
	);
	*/

	// Show-hide password field by hovering the eye icon
	$('.show-pass').hover(
		function() {$('.password').attr('type', 'text');},
		function() {$('.password').attr('type', 'password');}
	);

	// Confirmation message
	$('.confirm').click(function() {
		return confirm("Are you sure you want to do this ?");
	});
});