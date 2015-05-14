$(function() {
	$("#login").submit(function(event) {
		event.preventDefault();
		var password = $("#password", $(this)).val(),
			$this = $(this),
			p = hex_sha512(password);
		$("#password", $(this)).val('');
		$("input,button", $(this)).prop('disabled',true);
		$.ajax({
			method: "POST",
			url: '/account',
			data: {
				action: "login",
				email: $("#email",$this).val(),
				p: p
			},
			dataType: "json"
		}).done(function(data) {
			console.log(data);
			if (data.success) {
				console.log("Success!");
			} else {
				$("input", $this).val('');
				$("input,button", $this).prop('disabled',false);
			}
		}).error(function(jqXHR, textStatus, errorThrown) {
			console.error(jqXHR);
		});
	});
	
	$("#registration_form").submit(function(event) {
		event.preventDefault();
		var user = $("#username", $(this)).val(),
			email = $("#email", $(this)).val(),
			pass = $("#password", $(this)).val(),
			confirm = $("#confirmpwd", $(this)).val(),
			$this = $(this);
		$("input,button", $(this)).prop('disabled',true);
		if (user === '' || email === '' || pass === '' || confirm === '') {
			alert("Please fill out all fields.");
			$("input", $(this)).val('');
			$("input,button", $(this)).prop('disabled', false);
		} else {
			$("#password,#confirmpwd", $(this)).val('');
			if (pass == confirm) {
				$.ajax({
					method: "POST",
					url: '/account',
					data: {
						action: "register",
						user: user,
						email: email,
						p: hex_sha512(pass)
					},
					dataType: "json"
				}).done(function(data) {
					if (data.success) {
						console.log("Success!");
					} else {
						console.log("Failure", data.success, data);
						$("input", $this).val('');
						$("input,button", $this).prop('disabled',false);
					}
				});
			} else {
				alert("Be sure to repeat your password twice, <strong>exactly</strong>.");
			}
		}
	});
});