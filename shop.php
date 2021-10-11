<?php 
	include_once 'php/function.php';
?>

<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>
        <?= getContent("shop_title"); ?>
    </title>

    <?php include_once getUrl('inc/link.php'); ?>
    <script src="<?= getUrl('js/shop.update.data.js'); ?>"></script>
</head>

<body>
    <?php include_once getUrl('inc/header.php'); ?>

    <div class="container-games">
        <div class="list-el list-el__alldiamod">
            <div class="btn-way-auth">Авторизоваться</div>
            <div class="list-el__wrapper-right">
                <div class="list-el__txt-alldiamond">0</div>
                <img class="icons2" src="<?= getUrl('img/content/'. getContent('diamond_icon')); ?>">
            </div>
        </div>

        <div class="contaner-game__items">

        </div>

        <div class="contaner-game__btn">
            <button class="btn-play">Играть ещё</button>
        </div>


        <?php include_once getUrl('inc/modal.window.php'); ?>
    </div>

    <?php include_once getUrl('inc/menu.php'); ?>


</body>