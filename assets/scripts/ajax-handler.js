jQuery(document).ready(function($) {
	var modalLogin = $('#modal-login');
	var modalRegister = $('#modal-register');
	var openLogin = $('#toggle-modal-login');
	var openRegister = $('#toggle-modal-register');
	var singleCourseRegister = $('#course-register');
	
	// This variable will monitor whether a modal needs to open or not.
	// Without it, when a modal is closed via clicking anywhere results
	// in the showing of the other one and there is no access to the content below.
	var modalNeedsToOpen = false;

	singleCourseRegister.on('click', function() {
		modalRegister.modal('show');
	});

	openLogin.on('click', function () {
		modalNeedsToOpen = true;
		modalRegister.modal('hide');
	});
	openRegister.on('click', function () {
		modalNeedsToOpen = true;
		modalLogin.modal('hide');
	});

	modalLogin.on('hidden.bs.modal', function() {
		if(modalNeedsToOpen) {
			modalRegister.modal('show');
		}
	});
	modalLogin.on('shown.bs.modal', function() {
		modalNeedsToOpen = false;
		$('#login-email').focus();
	});

	modalRegister.on('hidden.bs.modal', function() {
		if(modalNeedsToOpen) {
			modalLogin.modal('show');
		}
	});
	modalRegister.on('shown.bs.modal', function() {
		modalNeedsToOpen = false;
		$('#register-email').focus();
	});
	
	$('form#loginform').on('submit', function(e){
		e.preventDefault();
	});
	$('#login-submit').on('click', function(e){
		e.preventDefault();
		var infoStatus = $('<div class="alert alert-info alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + ajax_handler_object.loadingmessage + '</div>');
		$('form#loginform div.status').html(infoStatus);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_handler_object.ajaxurl,
			data: { 
				'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
				'login-email': $('form#loginform #login-email').val(), 
				'login-password': $('form#loginform #login-password').val(),
				'rememberme': $('form#loginform #rememberme').val(),
				'ajax-login': $('form#loginform #ajax-login').val()
			},
			success: function(data){
				if (data.loggedin == true){
					var successStatus = $('<div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.message + '</div>');
					$('form#loginform div.status').html(successStatus);
					document.location.href = ajax_handler_object.redirecturl;
				} else {
					var failStatus = $('<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.message + '</div>');
					$('form#loginform div.status').html(failStatus);
				}
			},
			error: function(data) {
				document.location.href = ajax_handler_object.redirecturl;
				// var failStatus = $('<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Something went wrong! Please try again.</div>');
			}
		});
	});
	
	$('form#registerform').on('submit', function(e){
		e.preventDefault();
	});
	$('#register-submit').on('click', function(e){
		e.preventDefault();
		var infoStatus = $('<div class="alert alert-info alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + ajax_handler_object.loadingmessage + '</div>');
		$('form#registerform div.status').html(infoStatus);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_handler_object.ajaxurl,
			data: { 
				'action': 'ajaxregister', //calls wp_ajax_nopriv_ajaxregister
				'register-email': $('form#registerform #register-email').val(), 
				'register-password': $('form#registerform #register-password').val(), 
				'ajax-register': $('form#registerform #ajax-register').val()
			},
			success: function(data){
				if (data.registration == true){
					var successStatus = $('<div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.message + '</div>');
					$('form#registerform div.status').html(successStatus);
					document.location.href = ajax_handler_object.redirecturl;
				} else {
					var failStatus = $('<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + data.message + '</div>');
					$('form#registerform div.status').html(failStatus);
				}
			},
			error: function(data) {
				document.location.href = ajax_handler_object.redirecturl;
			}
		});
	});


});