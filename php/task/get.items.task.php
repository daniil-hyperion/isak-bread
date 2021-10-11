<?php
include_once '../function.php';
include_once '../../config/config.php';

$jsonItems = json_decode(file_get_contents("../../config/items.task.json"), true);

if(isset($_SESSION['data'])){
    $id_user = $_SESSION['data']['id'];
    
    $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $user = $user->fetch(PDO::FETCH_LAZY);

    if($user['id_cell']){
        $jsonUsedItem = json_decode($user['ex_diamond'], true);

        for($i=0;$i< count($jsonUsedItem); $i++){
            for($j=0;$j<count($jsonItems); $j++){
                if($jsonUsedItem[$jsonItems[$j]['itemName']] AND $jsonUsedItem[$jsonItems[$j]['itemName']]['used'] == true){
                    $jsonItems[$j]['used'] = true;
                }
            }
        }

        exit(json_encode($jsonItems, JSON_UNESCAPED_UNICODE));
    }else{
        exit(json_encode($jsonItems, JSON_UNESCAPED_UNICODE));
    }
}else{
    exit(json_encode($jsonItems, JSON_UNESCAPED_UNICODE));
}