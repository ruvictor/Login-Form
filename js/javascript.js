$(document).ready(function() {
	var auth = $('#logform');
	var alerta = $('.login_response');
	auth.on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			url: '../core/ajax/login.php',
			type: 'POST',
			dataType: 'html',
			data: auth.serialize(),
			beforeSend: function() {
				alerta.fadeOut();
				alerta.fadeIn();
				alerta.html('Loading...');
			},
			success: function(data) {
				alerta.html(data).fadeIn();
				auth.trigger('reset');
			},
			error: function(e) {
				console.log(e)
			}
		});
	});
});