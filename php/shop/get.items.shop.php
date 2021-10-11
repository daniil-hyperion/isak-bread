<?php
include_once '../function.php';
include_once '../../config/config.php';

$jsonItems = json_decode(file_get_contents("../../config/items.shop.json"), true);

if(isset($_SESSION['data'])){
    $id_user = $_SESSION['data']['id'];
    
    $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $user = $user->fetch(PDO::FETCH_LAZY);

    if($user['id_cell']){
        $jsonUsedItem = json_decode($user['price'], true);

        for($i=0;$i< count($jsonUsedItem); $i++){
            for($j=0;$j<count($jsonItems); $j++){
                if($jsonUsedItem[$jsonItems[$j]['itemName']] AND $jsonUsedItem[$jsonItems[$j]['itemName']]['buy'] == false){
                    // $jsonItems[$j]['used'] = true;
                    $jsonItems[$j]['activ'] = true;

                }else if($jsonUsedItem[$jsonItems[$j]['itemName']] AND $jsonUsedItem[$jsonItems[$j]['itemName']]['buy'] == true AND $jsonUsedItem[$jsonItems[$j]['itemName']]['used'] == false){
                    $jsonItems[$j]['buy'] = true;
                    $jsonItems[$j]['activ'] = true;
                    $jsonItems[$j]['used'] = false;
                }
                else if($jsonUsedItem[$jsonItems[$j]['itemName']] AND $jsonUsedItem[$jsonItems[$j]['itemName']]['used'] == true){
                    $jsonItems[$j]['used'] = true;
                    $jsonItems[$j]['activ'] = true;
                }
            }
        }

        for($j=0;$j< count($jsonItems); $j++){
            if($jsonItems[$j]['itemType'] == "tokens"){
                if($user['tokens'] >= $jsonItems[$j]['itemPrize']){
                    $jsonItems[$j]['activ'] = true;
                }
            }else if($jsonItems[$j]['itemType'] == "diamond"){
                if($user['coin'] >= $jsonItems[$j]['itemPrize']){
                    $jsonItems[$j]['activ'] = true;
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