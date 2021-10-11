<?php

include_once 'php/function.php';
include_once 'config/config.php';

if(!isset($_SESSION['data']) AND isset($_GET['ref'])){
    $_SESSION['ref'] = intval($_GET['ref']);

    exit(header('Location: index.php'));
}else{
    exit(header('Location: index.php'));
}