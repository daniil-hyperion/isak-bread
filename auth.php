<?php 
	include_once 'php/function.php';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>
        <?= getContent("auth_title");?>
    </title>

    <?php include_once getUrl('inc/link.php'); ?>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="<?=getUrl('css/globalAuth.css');?>">

</head>

<body>
    <div class="main-wrapper">
        <?php include_once getUrl('inc/header.php'); ?>
        <div class="push-window-msg">

        </div>
        <div class="content">
            <p class="content-info__reg"><?= getContent("auth_info"); ?></p>

            <?php include_once getUrl('inc/auth/standard.auth.php'); ?>

            <p class="content-or">или</p>

            <?php include_once getUrl('inc/auth/social.network.php'); ?>
        </div>
    </div>

    <script src="<?=getUrl('js/standartAuth.js')?>"></script>
</body>

</html>