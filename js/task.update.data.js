$(document).ready(function() {
    // модпльное окно реферальной системы
    const addUser = data => {
        // открытие и заполнение модалки контентом
        $('.background-mod').css('display', 'block')
        $('.container-elements__hint b').html(data.title)
        $('.reflink').html("Ссылка другу")
        $('.reflink_hide').html(data.description)

        // закрытие модалки
        $('.btn-use-present').on('click', function() {
            $('.background-mod').css('display', 'none')
        })

        // копирование ссылки в буфер обмена при нажатии на кнопку "копировать"
        $('.use_promo').click(function() {
            var $temp = $('<input>')
            $('body').append($temp)
            $temp.val($('.reflink_hide').text()).select()
            document.execCommand('copy')
            $temp.remove()

            $('.container-elements__hint p').html('Ссылка скопирована!').css('color', '#0faa44')
        })
    }

    // переход на социальную сеть
    const openLink = data => {
        window.open(data)
    }

    // получение данных о товарах и бонусах
    const updateStateApp = () => {
        $.ajax({
            type: 'POST',
            url: 'php/task/get.items.task.php',
            dataType: 'json',
            success: function(data) {
                if (data.length == 0) {
                    let productHTML = `
						<div class="list-el list-el_row">
							<div class="list-el__text">В данный момент список заданий пуст</div>
						</div>
						`
                    $('.container').html(productHTML)
                } else {
                    // удаление старой верстки
                    $('.container').empty()
                        // генерация и внедрение новой верстки
                    for (let i = 0; i < data.length; i++) {
                        let productHTML = `
						<div class="list-el list-el_row" data-item="${data[i].itemName}">
							<img class="diamond"  src="${path + 'img/content/new-diamond.svg'}"><b>${data[i].itemPrize}</b>
							<div class="list-el__text">${data[i].itemTitle}</div>
						</div>
						`

                        if (!data[i].used) {
                            $('.container').append(productHTML)
                        }
                    }

                    // нажатие на конкретное задание
                    $('.list-el_row').on('click', function() {
                        let keyTask = $(this).attr('data-item')

                        $.ajax({
                            type: 'POST',
                            url: 'php/task/get.task.info.php',
                            data: {
                                task: keyTask,
                            },
                            dataType: 'json',
                            success: function(data) {
                                // console.log(data)
                                if (data.type == 'ok') {
                                    switch (data.attr.type) {
                                        case 'addUser':
                                            // открытие модального окна
                                            addUser(data.attr.modal)
                                            break
                                        case 'openLink':
                                            // переход по ссылке
                                            openLink(data.attr.link)

                                            $.ajax({
                                                type: 'POST',
                                                url: 'php/task/get.task.info.php',
                                                data: {
                                                    task: keyTask,
                                                    edit: 'use',
                                                },
                                                dataType: 'json',
                                                success: function(data) {
                                                    // console.log(data)
                                                    if (data.type == 'ok') {
                                                        console.log(data)
                                                    } else {
                                                        console.log('error get diamond')
                                                    }
                                                },
                                            })

                                            break
                                        default:
                                            break
                                    }
                                } else {
                                    modalWarning(
                                        'Внимание',
                                        'Чтобы выполнить задание, необходимо войти в аккаунт. <span>Перейти к авторизации?</span>',
                                        'Войти'
                                    )
                                }
                            },
                        })
                    })
                }
            },
        })
    }

    updateStateApp()
    let updateApp = setInterval(updateStateApp, 1000)
})