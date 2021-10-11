let updateData = null
let updateApp = null
$(document).ready(function() {
            // кол-во монет у авторизованного пользователя
            let countTokensUser = 0

            // нажатие на кнопку авторизоваться
            $('.btn-way-auth').on('click', function() {
                redirect('auth.php')
            })

            // нажатие на кнопку играть еще
            $('.btn-play').on('click', function() {
                redirect('map.php')
            })

            // обновление экрана магазина каждую секунду
            const updateCoin = () => {
                $.ajax({
                    type: 'POST',
                    url: path + 'php/game/get.coin.php',
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        // console.log(data)
                        if (data.type == 'warning') {
                            if ($('.btn-way-auth').css('visibility') == 'collapse') {
                                $('.btn-way-auth').css('visibility', 'visible')
                            }
                            countTokensUser = data.attr.tokens
                        } else {
                            // выводим кол-во монет и алмазов
                            // $('.list-el__txt-allcoin').html(data.attr.tokens)
                            $('.list-el__txt-alldiamond').html(data.attr.diamond)

                            countTokensUser = data.attr.tokens
                        }
                    },
                })
            }

            // получение данных о товарах и бонусах
            const updateStateApp = () => {
                    $.ajax({
                                type: 'POST',
                                url: 'php/shop/get.items.shop.php',
                                dataType: 'json',
                                success: function(data) {
                                        // console.log(data)
                                        // удаление старой верстки
                                        $('.contaner-game__items').empty()
                                            // генерация и внедрение новой верстки
                                        for (let i = 0; i < data.length; i++) {
                                            let productHTML = `
						<div class="list-el">
							<div class="list-el__wrapper" data-item="${data[i].itemName}">
								<img class="icons" src="${
									data[i].buy || data[i].used
										? data[i].itemImage == ''
											? 'img/content/bonus.svg'
											: data[i].itemImage
										: 'img/content/bonus.svg'
								}">
								<div class="list-el__wrapper-text">
								${
									data[i].buy
										? data[i].itemTitle
										: data[i].used ? data[i].itemTitle : data[i].itemType == 'tokens'
										? '<p class="shop-item-name">Приз</p>' +
										  `<p class="price">${countTokensUser + ` / ` + data[i].itemPrize}</p>`
										: '<p class="shop-item-name">Приз</p>' + `<p class="price">${data[i].itemPrize}</p>`
								}
								${data[i].itemType == 'tokens' ? '' : '<img class="icons3" src="img/content/new-diamond.svg">'}
								</div>
								${
									data[i].used
										? '<button class="get-bonus disable-elem">Получен</button>'
										: data[i].buy
										? '<button class="get-bonus">Применить</button>'
										: data[i].activ
										? '<button class="get-bonus">Получить</button>'
										: ''
								}
								
							</div>
						</div>
						`

					$('.contaner-game__items').append(productHTML)
				}

				// открытие главного модального окна
				$('.get-bonus').on('click', function () {
					let keyItem = $(this).parent().attr('data-item')
					// console.log(keyItem)

					// передаем атрибут получаемого бонуса
					$.ajax({
						type: 'POST',
						url: 'php/shop/set.items.click.php',
						data: {
							item: keyItem,
						},
						dataType: 'json',
						async: false,
						success: function (data) {
							// console.log(data)

							if (data.type == 'ok') {
								if (data.attr.buyProduct && !data.attr.useProduct) {
									// открытие главного окна с описанием
									$('.background').css('display', 'block')

									// выводим в модалку заголовок, описание, промокод
									$('.container-elements__hint b').html(data.attr.title)
									$('.container-elements__hint p').html(data.attr.description)
									$('.promo').html(data.promo)

									// открываем окно подтверждения
									$('.use_promo').on('click', function () {
										$('.container_modal_background').css('display', 'block')

										// отправка кода подтверждения
										$('#active_modal_window_block').on('click', function () {
											if (checkKey($('#input_modal_window_block').val(), keyUseProduct)) {
												// $('#input_modal_window_block').val('')
												// статус что товар получен
												$.ajax({
													type: 'POST',
													url: 'php/shop/set.items.click.php',
													data: {
														item: keyItem,
														edit: 'use',
													},
													dataType: 'json',
													success: function (res) {
														// console.log(res)

														if (res.type == 'ok') {
															keyItem = null
															$('#active_modal_window_block').unbind()

															$('.use_promo').unbind()

															$('.get-bonus').unbind()

															$('.container_modal_background').css('display', 'none')
															$('.background').css('display', 'none')
															// отчищаем поле
															$('#input_modal_window_block').val('')
															// закрываем модалку

															redirect('getprize.php')
														} else {
															console.log('err')
														}
													},
												})
											} else {
												console.log('Код не совпадает')
											}
										})

										// закрываем окно подтверждения
										$('#close_modal_window_block').on('click', function () {
											$('.container_modal_background').css('display', 'none')
											keyItem = null
											$('#active_modal_window_block').unbind()

											$('.use_promo').unbind()

											$('.get-bonus').unbind()
										})
									})

									// закрытие главного окна с описанием
									$('.btn-use-present').on('click', function () {
										$('.background').css('display', 'none')
										keyItem = null
										$('#active_modal_window_block').unbind()

										$('.use_promo').unbind()

										$('.get-bonus').unbind()
									})
								}
							}
						},
					})
				})
			},
		})
	}

	updateStateApp()
	updateCoin()
	updateData = setInterval(updateCoin, 1000)
	updateApp = setInterval(updateStateApp, 1000)
})