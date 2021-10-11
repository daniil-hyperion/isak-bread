let succsesColor = '2px solid #40E961'
let errorColor = '2px solid #FF5151'

function validateEmail(email) {
	const re =
		/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	return re.test(String(email).toLowerCase())
}

// Авторизация
$('#emailAuth').change(function () {
	let boolEmail = validateEmail($('#emailAuth').val())

	if ($('#emailAuth').val().length > 0) {
		if (boolEmail) {
			$('#emailAuth').css('border', succsesColor)
		} else {
			$('#emailAuth').css('border', errorColor)
		}
	} else {
		$('#emailAuth').css('border', errorColor)
	}
})

$('#passwordAuth').change(function () {
	let passValueAuthCheck = $('#passwordAuth').val()
	passValueAuthCheck = passValueAuthCheck.trim()

	if (passValueAuthCheck.length >= 8) {
		$('#passwordAuth').css('border', succsesColor)
	} else {
		$('#passwordAuth').css('border', errorColor)
	}
})

$('.btn-auth').on('click', function () {
	$.ajax({
		url: path + 'php/auth/auth.standard.php',
		method: 'POST',
		dataType: 'json',
		data: {
			email: $('#emailAuth').val(),
			password: $('#passwordAuth').val(),
		},
		success: function (data) {
			console.log(data)
			if (data.type == 'ok') {
				window.location.href = 'shop.php'
			} else {
				$('.push-window-msg').css('margin-top', '11vh')

				$('.push-window-msg').html(`<p>${data.message}</p>`)
				setTimeout(() => {
					$('.push-window-msg').css('margin-top', '-24vh')
				}, 5000)
			}
		},
	})
})

// регистрация
$('#emailReg').change(function () {
	let boolEmail = validateEmail($('#emailReg').val())

	if ($('#emailReg').val().length > 0) {
		if (boolEmail) {
			$('#emailReg').css('border', succsesColor)
		} else {
			$('#emailReg').css('border', errorColor)
		}
	} else {
		$('#emailReg').css('border', errorColor)
	}
})

$('#passwordReg').change(function () {
	let passValueAuthCheck = $('#passwordReg').val()
	passValueAuthCheck = passValueAuthCheck.trim()

	if (passValueAuthCheck.length >= 8 && $('#passwordRegExam').val().trim() >= 8) {
		if ($('#passwordReg').val() == $('#passwordRegExam').val()) {
			$('#passwordReg').css('border', succsesColor)
			$('#passwordRegExam').css('border', succsesColor)
		} else {
			$('#passwordReg').css('border', errorColor)
			$('#passwordRegExam').css('border', errorColor)
		}
	} else {
		$('#passwordRegExam').css('border', errorColor)
		$('#passwordReg').css('border', errorColor)
	}
})

$('#passwordRegExam').change(function () {
	let passValueAuthCheck = $('#passwordRegExam').val()
	passValueAuthCheck = passValueAuthCheck.trim()

	if (passValueAuthCheck.length >= 8 && $('#passwordReg').val().trim() >= 8) {
		if ($('#passwordRegExam').val() == $('#passwordReg').val()) {
			$('#passwordRegExam').css('border', succsesColor)
			$('#passwordReg').css('border', succsesColor)
		} else {
			$('#passwordRegExam').css('border', errorColor)
			$('#passwordReg').css('border', errorColor)
		}
	} else {
		$('#passwordRegExam').css('border', errorColor)
		$('#passwordReg').css('border', errorColor)
	}
})

$('.btn-reg').on('click', function () {
	if ($('#passwordReg').val() == $('#passwordRegExam').val()) {
		$.ajax({
			url: path + 'php/auth/reg.standard.php',
			method: 'POST',
			dataType: 'json',
			data: {
				email: $('#emailReg').val(),
				password: $('#passwordReg').val(),
			},
			success: function (data) {
				console.log(data)
				if (data.type == 'ok') {
					window.location.href = 'shop.php'
				} else {
					$('.push-window-msg').css('margin-top', '11vh')
					$('.push-window-msg').html(`<p>${data.message}</p>`)
					setTimeout(() => {
						$('.push-window-msg').css('margin-top', '-24vh')
					}, 5000)
				}
			},
		})
	} else {
		$('.push-window-msg').css('margin-top', '11vh')
		$('.push-window-msg').html(
			`<p>Проверьте правильность заполненной формы.</br>Длина пароля от 8-ми символов.</br>Пароли должны совпадать.</p>`
		)
		setTimeout(() => {
			$('.push-window-msg').css('margin-top', '-24vh')
		}, 5000)
	}
})
