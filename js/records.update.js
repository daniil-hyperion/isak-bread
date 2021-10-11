$(document).ready(function () {
	// начальная страца пагинации
	let numPaginaionPage = 1
	// последняя страницу пагинации
	let lastNumPafinationPage = null

	// общий топ
	const updateTop10 = () => {
		// скрытие панели пагинации
		$('.pagination-wrapper').css('display', 'none')
		$.ajax({
			type: 'POST',
			url: 'php/records/get.records.top.php',
			dataType: 'json',
			success: function (data) {
				// console.log(data)

				if (data.type == 'ok') {
					if (data.records.length == 0) {
						let recordsHTML = `
								<div class="records-table">
									<div class="records-table__item client-mod">В данный момент рекордсменов нет</div>
								</div>
							`
						$('.table-rec').html(recordsHTML)
					} else {
						// удаление старой верстки
						$('.table-rec').empty()
						// генерация и внедрение новой верстки
						for (let i = 0; i < data.records.length; i++) {
							let recordsHTML = `
								<div class="records-table">
									<div class="records-table__item client">${i + 1 + '.'}</div>
									<div class="records-table__item client">${data.records[i].name}</div>
									<div class="records-table__item client">${'(' + data.records[i].tokens + ')'}</div>
	
									<img class="award" src="${
										i + 1 == 1
											? path + 'img/content/first.svg'
											: i + 1 == 2
											? path + 'img/content/second.svg'
											: i + 1 == 3
											? path + 'img/content/third.svg'
											: ''
									}">
									
								</div>
							`
							$('.table-rec').append(recordsHTML)
						}

						// вывод личного рекорда под общим рейтингом
						if (data.myRecords.length == 0) {
							$('.table-ich').css('display', 'none')
						} else {
							$('.table-ich').css('display', 'block')
							let myRecordHTML = `
								<div class="records-table">
									<div class="records-table__item client">${data.myRecords.position + '.'}</div>
									<div class="records-table__item client">${data.myRecords.name}</div>
									<div class="records-table__item client">${'(' + data.myRecords.tokens + ')'}</div>
	
									<img class="award" src="${
										data.myRecords.position == 1
											? path + 'img/content/first.svg'
											: data.myRecords.position == 2
											? path + 'img/content/second.svg'
											: data.myRecords.position == 3
											? path + 'img/content/third.svg'
											: ''
									}">
								</div>
							`
							$('.table-ich').html(myRecordHTML)
						}
					}
				} else {
					console.log(data)
				}
			},
		})
	}

	// топ недели
	const updateTopWeek = page => {
		// показываем панель ппагинации
		$('.pagination-wrapper').css('display', 'flex')

		$.ajax({
			type: 'GET',
			url: 'php/records/get.records.week.php',
			dataType: 'json',
			data: {
				page: page,
			},
			success: function (data) {
				// console.log(data)

				if (data.type == 'ok') {
					// удаление старой верстки
					$('.table-rec').empty()
					// генерация и внедрение новой верстки

					if (data.pages == 0) {
						$('.table-ich').css('display', 'none')
						$('.pagination-wrapper').css('display', 'none')

						let recordsHTML = `
								<div class="records-table">
									<div class="records-table__item client-mod">В данный момент недельных рекордсменов нет</div>
								</div>
							`
						$('.table-rec').html(recordsHTML)
					} else {
						lastNumPafinationPage = data.pages
						for (let i = 0; i < data.records.length; i++) {
							let recordsHTML = `
								<div class="records-table">
									<div class="records-table__item client">${data.records[i].position + '.'}</div>
									<div class="records-table__item client">${data.records[i].name}</div>
									<div class="records-table__item client">${'(' + data.records[i].tokens + ')'}</div>
	
									<img class="award" src="${
										data.records[i].position == 1
											? path + 'img/content/first.svg'
											: data.records[i].position == 2
											? path + 'img/content/second.svg'
											: data.records[i].position == 3
											? path + 'img/content/third.svg'
											: ''
									}">
									
								</div>
							`
							$('.table-rec').append(recordsHTML)
						}

						// вывод личного рекорда под общим рейтингом
						if (data.myRecords.length == 0) {
							$('.table-ich').css('display', 'none')
						} else {
							$('.table-ich').css('display', 'block')
							let myRecordHTML = `
								<div class="records-table">
									<div class="records-table__item client">${data.myRecords.position + '.'}</div>
									<div class="records-table__item client">${data.myRecords.name}</div>
									<div class="records-table__item client">${'(' + data.myRecords.tokens + ')'}</div>
	
									<img class="award" src="${
										data.myRecords.position == 1
											? path + 'img/content/first.svg'
											: data.myRecords.position == 2
											? path + 'img/content/second.svg'
											: data.myRecords.position == 3
											? path + 'img/content/third.svg'
											: ''
									}">
									
								</div>
							`
							$('.table-ich').html(myRecordHTML)
						}
					}
				} else {
					console.log(data)
				}
			},
		})
	}

	// нажатие на кнопку топ недели
	$('.top-week').on('click', function () {
		$('.top10').removeClass('active_block')
		$('.top-week').addClass('active_block')

		updateTopWeek(numPaginaionPage)
	})
	// нажатие на кнопку топ 10
	$('.top10').on('click', function () {
		$('.top-week').removeClass('active_block')
		$('.top10').addClass('active_block')

		updateTop10()
	})

	// пагинация
	$('.pagination-wrapper__next').on('click', function () {
		if (numPaginaionPage == lastNumPafinationPage) {
			$('.pagination-wrapper__next').css('pointer-events', 'none')
		} else {
			$('.pagination-wrapper__prev').css('pointer-events', 'auto')
			// изменяем страницу пагинации
			numPaginaionPage += 1
			// отправляем запрос
			updateTopWeek(numPaginaionPage)

			// обновляем номер страницы
			$('.pagination-wrapper__page').html(numPaginaionPage)
		}
	})

	$('.pagination-wrapper__prev').on('click', function () {
		if (numPaginaionPage == 1) {
			$('.pagination-wrapper__prev').css('pointer-events', 'none')
		} else {
			$('.pagination-wrapper__next').css('pointer-events', 'auto')

			// изменяем страницу пагинации
			numPaginaionPage -= 1
			// отправляем запрос
			updateTopWeek(numPaginaionPage)
			// обновляем номер страницы
			$('.pagination-wrapper__page').html(numPaginaionPage)
		}
	})

	updateTopWeek()
})
