let wrapper1 = document.querySelectorAll('.content-rating__wrapper')[0]
let wrapper2 = document.querySelectorAll('.content-rating__wrapper')[1]

let star = document.querySelectorAll('.star')
let star2 = document.querySelectorAll('.star2')
let indexPrev = 0
let indexPrev2 = 0

let answer1 = document.querySelectorAll('.content-rating__answer')[0]
let answer2 = document.querySelectorAll('.content-rating__answer')[1]

let btnSend = document.querySelector('.content-rating__btn')

let valueStar1 = 0
let valueStar2 = 0

star.forEach((item, index) => {
	item.addEventListener('click', e => {
		if (indexPrev > index) {
			for (let i = 0; i <= 4; i++) {
				star[i].classList.remove('star-activ')
			}
		}
		indexPrev = index
		for (let i = 0; i <= index; i++) {
			star[i].classList.add('star-activ')
		}
		valueStar1 = index + 1

		switch (valueStar1) {
			case 0:
				answer1.innerHTML = ''
				break
			case 1:
				answer1.innerHTML = '&#128542'
				break
			case 2:
				answer1.innerHTML = '&#128580'
				break
			case 3:
				answer1.innerHTML = '&#128528'
				break
			case 4:
				answer1.innerHTML = '&#128578'
				break
			case 5:
				answer1.innerHTML = '&#128525'
				break

			default:
				break
		}
		if (valueStar1 > 0 && valueStar2 > 0) {
			btnSend.style.display = 'block'
		} else {
			btnSend.style.display = 'none'
		}
	})
})

star2.forEach((item, index) => {
	item.addEventListener('click', () => {
		if (indexPrev2 > index) {
			for (let i = 0; i <= 4; i++) {
				star2[i].classList.remove('star-activ')
			}
		}
		indexPrev2 = index
		for (let i = 0; i <= index; i++) {
			star2[i].classList.add('star-activ')
		}
		valueStar2 = index + 1

		switch (valueStar2) {
			case 0:
				answer2.innerHTML = ''
				break
			case 1:
				answer2.innerHTML = '&#128542'
				break
			case 2:
				answer2.innerHTML = '&#128580'
				break
			case 3:
				answer2.innerHTML = '&#128528'
				break
			case 4:
				answer2.innerHTML = '&#128578'
				break
			case 5:
				answer2.innerHTML = '&#128525'
				break

			default:
				break
		}

		if (valueStar1 > 0 && valueStar2 > 0) {
			btnSend.style.display = 'block'
		} else {
			btnSend.style.display = 'none'
		}
	})
})

btnSend.onclick = function () {
	let grade_ = [
		{ text: 'Оцените качество работы оператора', grade: valueStar1 },
		{ text: 'Оцените игру', grade: valueStar2 },
	]
	grade_ = JSON.stringify(grade_)
	$.ajax({
		url: 'php/grade/graid.raiting.php',
		method: 'post',
		dataType: 'json',
		data: { grade: grade_ },
		success: function (data) {
			console.log(data)
			if (data.type == 'ok') {
				// redirect('shop.php')
				modalWarning('Спасибо за оценку', 'Перейдите к товарам', 'Перейти', false)
			} else {
				console.log(data)
			}
		},
	})
}
