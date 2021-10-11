<?php 
	include_once 'php/function.php';
	
?>

<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>
        <?= getContent("index_title"); ?>
    </title>

    <?php include_once getUrl('inc/link.php'); ?>
</head>

<body>
    <?php include_once getUrl('inc/header.php'); ?>

    <div class="container-games center-collumn">
        <img class="img-logo" src="<?= getUrl(getContent("index_image")); ?>" alt="logo">

        <div class="text text__mod">

            <?= getContent("index_description"); ?>

            <button class="btn-start" onclick="redirect('manual.php')">Играть</button>
        </div>
    </div>

    <?php include_once getUrl('inc/menu.php'); ?>

</body>