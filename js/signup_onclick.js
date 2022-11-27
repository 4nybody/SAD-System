$(function () { // once the document is ready, do things
	// initialize our loader overlay


	$('#signup_button').on('click', function (e) { // onclick for our signup button
		e.preventDefault();
		$.ajax({
			url: 'php/process_signup.php',
			data: $('#signup_form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function (data) {
				if ('ok' == data.status) {
					loader.hideLoader();
					window.location.href = "index.php";
				} else if ('fail' == data.status) {
					$('#error_message').html(data.message);
					loader.hideLoader();
				}
			}
		});
	});
});