<?php 
	include_once 'php/function.php';
?>
<!DOCTYPE html>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title>
		<?= getContent("manual_title"); ?>
	</title>


	<?php include_once getUrl('inc/link.php'); ?>


</head>

<body>
	<?php include_once getUrl('inc/header.php'); ?>

	<div class="container">
		<div class="game"></div>
		<div class="text">
			<b>Управление:</b><br><br>
			Свайпы &#8592 / &#8594 <br>

			Прыжок — свайп вверх<br>
			Уменьшение — свайп вниз
		</div>
		<button class="btn-play" onclick="redirect('map.php')">Играть</button>
	</div>

	<?php include_once getUrl('inc/menu.php'); ?>

</body>

</html>