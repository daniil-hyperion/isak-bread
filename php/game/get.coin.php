<?php
include_once '../function.php';
include_once '../../config/config.php';

if(isset($_SESSION['data'])){
    $id_user = $_SESSION['data']['id'];
    
    $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $user = $user->fetch(PDO::FETCH_LAZY);

    if($user['id_cell']){
        $tokens = $user['tokens'];
        $diamond = $user['coin'];
    }else{
        $tokens = 0;
        $diamond = 0;
    }
    
    exit(json_encode(array('type' => "ok", 'attr' => ['tokens' => $tokens, 'diamond' => $diamond])));

}else{
    if(isset($_SESSION['tokens'])){
        $tokens = intval($_SESSION['tokens']);
    }else{
        $tokens = 0;
    }
    exit(json_encode(array('type' => "warning", 'attr' => ['tokens' => $tokens, 'diamond' => 0])));
}