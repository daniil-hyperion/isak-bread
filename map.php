<?php 
	include_once 'php/function.php';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<title>
		<?= getContent("map_title"); ?>
	</title>

	<?php include_once getUrl('inc/link.php'); ?>

	<link rel="stylesheet" href="<?=getUrl('css/map.css');?>">
</head>

<body>
	<?php include_once getUrl('inc/header.php'); ?>

	<div class="wrapper-map">
		<div class="game-msg">
			Завершите прохождения предыдущего уровня
		</div>
		<a href="freemode/" class="freemode">
			<img src="<?= getUrl('img/game/'. getContent('freemode_icon')); ?>" alt="">
			<p class="title-fr-mode">Свободный режим</p>
		</a>
		<div class="messagePrize">
			<p>Вам доступен бонус</p>
			<div class="btn-redirect">Бонусы</div>
		</div>

		<div class="wrapper-map__map-way">
			<div class="wrapper-map__map-way__level" id="lvl500" data-value='{"lvl": 9,"maxScore": 500}'></div>
			<div class="wrapper-map__map-way__level" id="lvl450" data-value='{"lvl": 8,"maxScore": 450}'></div>
			<div class="wrapper-map__map-way__level" id="lvl400" data-value='{"lvl": 7,"maxScore": 400}'></div>
			<div class="wrapper-map__map-way__level" id="lvl350" data-value='{"lvl": 6,"maxScore": 350}'></div>
			<div class="wrapper-map__map-way__level" id="lvl300" data-value='{"lvl": 5,"maxScore": 300}'></div>
			<div class="wrapper-map__map-way__level" id="lvl250" data-value='{"lvl": 4,"maxScore": 250}'></div>
			<div class="wrapper-map__map-way__level" id="lvl200" data-value='{"lvl": 3,"maxScore": 200}'></div>
			<div class="wrapper-map__map-way__level" id="lvl150" data-value='{"lvl": 2,"maxScore": 150}'></div>
			<div class="wrapper-map__map-way__level" id="lvl100" data-value='{"lvl": 1,"maxScore": 100}'></div>
		</div>

		<?php include_once getUrl('inc/menu.php'); ?>
	</div>

	<script src="<?=getUrl('js/gameMap.js')?>"></script>

</body>

</html>