<?php 
	include_once 'php/function.php';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?= getContent("getprize_title"); ?></title>

	<?php include_once getUrl('inc/link.php'); ?>

	<link rel="stylesheet" href="<?= getUrl('css/globalAuth.css'); ?>">
	<script src="<?= getUrl('js/task.update.data.js'); ?>"></script>

	<script>
	$(document).ready(function() {
		$('.content-rating').css('visibility', 'visible');

	});
	</script>
</head>

<body>
	<div class="main-wrapper">
		<?php include_once getUrl('inc/header.php'); ?>

		<div class="content">
			<div class="content-rating">
				<div class="rating-wrapper">
					<p class="content-rating__msg">Оцените качество работы оператора</p>
					<div class="content-rating__wrapper">
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
					</div>
					<p class="content-rating__answer"></p>
				</div>
				<div class="rating-wrapper">
					<p class="content-rating__msg">Оцените игру</p>
					<div class="content-rating__wrapper">
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star2" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star2" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star2" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star2" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
						<svg class="star-rating__item" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
							<path class="star2" fill="currentColor"
								d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z">
							</path>
						</svg>
					</div>
					<p class="content-rating__answer"></p>
				</div>

				<button class="btn content-rating__btn" id="rating" type="submit">ОЦЕНИТЬ</button>
			</div>

		</div>

		<?php include_once getUrl('inc/menu.php'); ?>
	</div>

	<script src="<?=getUrl('js/rating.js');?>"></script>
</body>

</html>