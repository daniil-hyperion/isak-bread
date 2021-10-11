let msg = document.querySelector('.game-msg') //окно с сообщение
let point = null //последний пройденный уровень
let finishLevel = [] //пройденные уровни

let btnForBonus = document.querySelector('.btn-redirect')
let msgForUser = document.querySelector('.messagePrize')

let startLevel = 100 //начальный уровень

let rangeScore = 50

// звук при нажатии на уровень
let succses = new Audio('sound/click.mp3')
let notSuccses = new Audio('sound/notSuccses.mp3')

//получение пройденных уровней из localStorage
let arr = localStorage.getItem('finishedLevel_template')
if (arr !== null) {
	finishLevel = arr.split(',')
	point = 'lvl' + String(finishLevel[finishLevel.length - 1])

	// if (finishLevel[finishLevel.length - 1] == 1400 ||
	// 	finishLevel[finishLevel.length - 1] == 2600 ||
	// 	finishLevel[finishLevel.length - 1] == 3800 ||
	// 	finishLevel[finishLevel.length - 1] == 5000) {

	// 	msgForUser.style.marginTop = "10vh"

	// 	setTimeout(() => {
	// 		msgForUser.style.marginTop = "-20vh"
	// 	}, 5000);
	// }
}

// родитель, содержащий дивы уровней
let pointWay = document.querySelectorAll('.wrapper-map__map-way')[0]

// данные о пройденных уровнях
// для синхронизации карты в различных браузерах
let syncDataUser = null

$.ajax({
	type: 'POST',
	url: 'php/game/get.coin.php',
	dataType: 'json',
	async: false,
	success: function (data) {
		syncDataUser = Number(data.attr.tokens)
	},
})

// заполнение массива пройденных уровней
if (syncDataUser > 0) {
	let maxScoreFirstLevel = 100
	let countLevel = (syncDataUser - 100) / 50
	let arr_1 = []

	for (let i = 0; i <= countLevel; i++) {
		finishLevel.push(maxScoreFirstLevel)
		// удаление дублей в массиве
		arr_1 = Array.from(new Set(finishLevel))
		// запись в локал
		localStorage.setItem('finishedLevel_template', arr_1)
		maxScoreFirstLevel += 50
	}

	// получение места к скролу
	finishLevel = String(arr_1).split(',')

	point = 'lvl' + String(finishLevel[finishLevel.length - 1])
}

btnForBonus.addEventListener('click', () => {
	window.location = 'cab.php'
})

window.addEventListener('load', () => {
	// скрол к последнему пройденному уровню
	let level = null
	if (point == null || point == '') {
		level = document.getElementById('lvl100')
	} else {
		level = document.getElementById(point)
	}

	setTimeout(() => {
		level.scrollIntoView({
			block: 'center',
			behavior: 'smooth',
			inline: 'center',
		})
	}, 500)

	// отметка пройденных уровней
	let arrChildren = pointWay.children

	$.ajax({
		url: 'map.php',
		method: 'post',
		dataType: 'html',
		data: { tokens: true },
		success: function (data) {
			data = Number(data)
			let arrNew = []
			for (let i in arrChildren) {
				let dataValueElement = JSON.parse(arrChildren[i].getAttribute('data-value'))
				// пройденные уровни
				if (dataValueElement.maxScore <= data) {
					arrChildren[i].style.backgroundImage = "url('img/game/mufComplete.png')"
					arrNew.push(dataValueElement.maxScore)
					// запись в локал
					localStorage.setItem('finishedLevel_template', Array.from(new Set(arrNew)).reverse())
				}
			}
		},
	})

	if (localStorage.getItem('finishedLevel_template') == null) {
		for (let i in arrChildren) {
			if (i == arrChildren.length - 1) {
				// доступные
				arrChildren[i].style.backgroundImage = "url('img/game/mufNow.png')"
				arrChildren[i].classList.add('pulse')
			} else {
				// не пройденные
				arrChildren[i].style.backgroundImage = "url('img/game/mufDef.png')"
			}
		}
		arrChildren[arrChildren.length - 1].style.backgroundImage = "url('img/game/mufStart.png')"
	} else {
		for (let i in arrChildren) {
			for (let j in finishLevel) {
				// пройденные уровни
				let dataValueElement = JSON.parse(arrChildren[i].getAttribute('data-value'))
				if (dataValueElement.maxScore == finishLevel[j]) {
					arrChildren[i].style.backgroundImage = "url('img/game/mufComplete.png')"
				}
				// не пройденные уровни
				if (Number(dataValueElement.maxScore) - finishLevel[finishLevel.length - 1] > rangeScore) {
					arrChildren[i].style.backgroundImage = "url('img/game/mufDef.png')"
				}
				if (Number(dataValueElement.maxScore) - finishLevel[finishLevel.length - 1] == rangeScore) {
					arrChildren[i].style.backgroundImage = "url('img/game/mufNow.png')"
					arrChildren[i].classList.add('pulse')
				}
			}
		}
	}
})

// клик по уровню и передача необходиного минимума баллов для его прохождения
pointWay.addEventListener('click', function (e) {
	//индекс элемента категории при нажатии
	let indexChildElem = [].indexOf.call(this.children, e.target)

	//id уровня
	let idElem = JSON.parse(this.children[indexChildElem].getAttribute('data-value'))

	if (localStorage.getItem('finishedLevel_template') == null) {
		if (Number(idElem.maxScore) == startLevel) {
			succses.play()
			setTimeout(() => {
				window.location = 'game.php'
			}, 200)
		} else {
			notSuccses.play()
			msg.style.display = 'block'
			setTimeout(() => {
				msg.style.display = 'none'
			}, 2000)
		}
	} else {
		// предупрежение что, уровень не пройден
		if (Number(idElem.maxScore) - finishLevel[finishLevel.length - 1] > rangeScore) {
			notSuccses.play()
			msg.style.display = 'block'
			setTimeout(() => {
				msg.style.display = 'none'
			}, 2000)
		} else {
			succses.play()
			setTimeout(() => {
				window.location = 'game.php'
			}, 200)
		}
	}

	// запись кол-во балов для прохождения выбранного уровня
	localStorage.setItem('maxScore', idElem.maxScore)
	localStorage.setItem('thisLevel', idElem.lvl)
})

document.querySelector('.freemode').addEventListener('click', () => {
	window.location = 'freemode/freemode.php'
})
