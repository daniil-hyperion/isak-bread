<?
	$file = file_get_contents('freemode_lvl.json');
	$data = json_decode($file, true);

	$data = json_encode($data);
?>

<!DOCTYPE html>
<html lang="ru">

<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>Свободный режим</title>

	<link href="../css/style.css" rel="stylesheet">
	<link href="../css/style_game.css" rel="stylesheet">
</head>

<body>
	<ul id="planet">
		<li
			style="background-image:radial-gradient(circle at 50% 100%, rgb(242, 242, 242), rgb(242, 242, 242) 30%, rgb(242, 242, 242) 40%, rgb(242, 242, 242) 40%)">
		</li>
	</ul>
	<canvas id="game" width="192" height="192"></canvas>

	<div class="header">
		<img class='header__name-logo mode' src='../img/header/logo-horyzontal.png'>
		<div id="counter"></div>
	</div>
	<div id="hud">
		<div id="quest">
			<h4 class="title"></h4>
			<h4></h4>
			<h4></h4>
			<table>
				<tr>
					<th>Distance travelled</th>
					<td></td>
				</tr>
				<tr>
					<th>Tokens collected</th>
					<td></td>
				</tr>
				<tr>
					<th>Big tokens collected</th>
					<td></td>
				</tr>
				<tr>
					<th>Asteroids destroyed</th>
					<td></td>
				</tr>
				<tr>
					<th>Places visited</th>
					<td></td>
				</tr>
				<tr>
					<th>Mission completed</th>
					<td></td>
				</tr>
				<tr>
					<th class="total">TOTAL</th>
					<td class="total"></td>
				</tr>
			</table>
		</div>
		<div><a id="ok"></a></div>

	</div>
	<div id="ctrl">
		<h3></h3>
		<div>
			<h3></h3><a id="prev">&lt;</a><a id="play"></a><a id="next">&gt;</a>
		</div>
	</div>
	<div>
		<i id="fs" title="Fullscreen">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-fullscreen"
				viewBox="0 0 16 16">
				<path
					d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z" />
			</svg>
		</i>
		<i id="sfx" title="Audio"></i>
	</div>
	<div id="load">
		<div>
			<h1></h1>
		</div>
		<div>

		</div>
		<div><a id="start"></a></div>
	</div>

	<div id="left-cover" class="lf"></div>
	<div id="right-cover" class="rt"></div>
	<div class="alternativa-preloader">
		<img id="alternativa" src="../img/game/load.png" alt="">
	</div>


	<script>
	// переменные для заставки
	let left = document.getElementById('left-cover')
	let right = document.getElementById('right-cover')
	let alternativaIMG = document.querySelector('#alternativa')
	let alternativaPreloader = document.querySelector('.alternativa-preloader')


	// получение параметров уровней из файла JSON
	let mainObjLevels = <?php echo $data; ?>;
	let dataLevel = 'freemode'

	let maxScore = mainObjLevels[dataLevel].maxScore;
	let complexity = mainObjLevels[dataLevel].complexity;
	let field_color = mainObjLevels[dataLevel].field_color;

	let backgroundGameField = document.querySelector('#game')


	window.addEventListener("load", () => {
		alternativaIMG.classList.add('alternativa-load')

		setTimeout(() => {
			alternativaPreloader.style.display = 'none'
			left.classList.add('left-hide')
			right.classList.add('right-hide')
		}, 2100);
	})
	</script>

	<script src="script_game.js"></script>
</body>

</html>