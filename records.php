<?php 
	include_once 'php/function.php';
?>
<!DOCTYPE html>

<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?= getContent("records_title"); ?></title>

	<?php include_once getUrl('inc/link.php'); ?>

	<script src="<?= getUrl('js/records.update.js'); ?>"></script>
</head>

<body>
	<?php include_once getUrl('inc/header.php'); ?>

	<div class="container">
		<div class="top">
			<button class="top-week active_block" type="submit">Топ-недели</button>
			<button class="top10" type="submit">Топ-10</button>
		</div>

		<div class="table table-rec">

		</div>

		<div class="table table-ich">

		</div>

		<div class="pagination-wrapper">
			<div class="pagination-wrapper__prev">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M16.2426 6.34317L14.8284 4.92896L7.75739 12L14.8285 19.0711L16.2427 17.6569L10.5858 12L16.2426 6.34317Z"
						fill="currentColor" />
				</svg>
			</div>

			<div class="pagination-wrapper__more">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M8 12C8 13.1046 7.10457 14 6 14C4.89543 14 4 13.1046 4 12C4 10.8954 4.89543 10 6 10C7.10457 10 8 10.8954 8 12Z"
						fill="currentColor" />
					<path
						d="M14 12C14 13.1046 13.1046 14 12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12Z"
						fill="currentColor" />
					<path
						d="M18 14C19.1046 14 20 13.1046 20 12C20 10.8954 19.1046 10 18 10C16.8954 10 16 10.8954 16 12C16 13.1046 16.8954 14 18 14Z"
						fill="currentColor" />
				</svg>
			</div>

			<div class="pagination-wrapper__page">1</div>

			<div class="pagination-wrapper__more">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M8 12C8 13.1046 7.10457 14 6 14C4.89543 14 4 13.1046 4 12C4 10.8954 4.89543 10 6 10C7.10457 10 8 10.8954 8 12Z"
						fill="currentColor" />
					<path
						d="M14 12C14 13.1046 13.1046 14 12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12Z"
						fill="currentColor" />
					<path
						d="M18 14C19.1046 14 20 13.1046 20 12C20 10.8954 19.1046 10 18 10C16.8954 10 16 10.8954 16 12C16 13.1046 16.8954 14 18 14Z"
						fill="currentColor" />
				</svg>
			</div>

			<div class="pagination-wrapper__next">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.5858 6.34317L12 4.92896L19.0711 12L12 19.0711L10.5858 17.6569L16.2427 12L10.5858 6.34317Z"
						fill="currentColor" />
				</svg>
			</div>
		</div>
	</div>

	<?php include_once getUrl('inc/menu.php'); ?>

</body>