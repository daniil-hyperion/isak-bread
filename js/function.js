const path = '/u/template/'

// код подтверждения
const keyUseProduct = 286

const checkKey = (userKey, keyUseProduct) => {
	userKey = Number(userKey.trim())

	if (userKey == keyUseProduct) {
		return true
	}
	return false
}

const redirect = url => {
	document.location.href = path + url
}

const modalWarning = (title, description, name_btn, close = true) => {
	let modalwHTML = `
		<div class="background background-mod warning">
			<div class="container-elements">
				<div class="container-elements__hint">
					<b class="warning-title">${title}</b>
					<p>${description}</p>
					<div class="container-elements__dialog__btns__got">
					${
						close
							? `<div class="use_promo auth-btn">${name_btn}</div><div class="btn-use-present">Закрыть</div>`
							: `<div class="use_promo shop-btn">${name_btn}</div>`
					}
					</div>
				</div>
			</div>
		</div>
	`

	$(modalwHTML).appendTo('body')
	// $(body).append(modalwHTML)
	$('.warning').css('display', 'block')

	$('.auth-btn').on('click', function () {
		redirect('auth.php')
	})

	$('.shop-btn').on('click', function () {
		redirect('shop.php')
	})

	$('.btn-use-present').on('click', function () {
		$('.warning').css('display', 'none')
	})
}
