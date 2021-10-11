<?php 
	include_once 'php/function.php';
?>
<!DOCTYPE html>

<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?= getContent("task_title"); ?></title>

	<?php include_once getUrl('inc/link.php'); ?>
	<script src="<?= getUrl('js/task.update.data.js'); ?>"></script>
</head>

<body>
	<?php include_once getUrl('inc/header.php'); ?>

	<div class="container">

	</div>

	<?php include_once getUrl('inc/modal.window.ref.system.php'); ?>

	<?php include_once getUrl('inc/menu.php'); ?>

</body>