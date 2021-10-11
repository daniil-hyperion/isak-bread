<?php

include_once '../function.php';
include_once '../../config/config.php';

$jsonItems = json_decode(file_get_contents("../../config/items.task.json"), true);

if(isset($_SESSION['data']) AND isset($_POST['task'])){

    $id_user = $_SESSION['data']['id'];
    $task = $_POST['task'];

    $typeTask = null;
    $objectTask = null;

    for($i = 0; $i <count($jsonItems); $i++){
        if($jsonItems[$i]['itemName'] == $task){
            $typeTask = $jsonItems[$i]['itemType'];
            $objectTask = $jsonItems[$i];
            break;
        }
    }

    if($typeTask == null){
        exit(json_encode(array('type' => "error", 'message' => "Предмет отсутствует в базе данных."), JSON_UNESCAPED_UNICODE));
    }
    if(isset($_POST['edit']) AND $_POST['edit'] == 'use'){
        
        $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
        $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
        $user = $user->fetch(PDO::FETCH_LAZY);

        $taskObjectUser = json_decode($user['ex_diamond'], true);
        $taskObjectUser[$task] = array(
            'used' => true,
            'dataUsed' => date("Y-m-d H:i:s")
        );
        $coin = $objectTask['itemPrize'] + $user['coin'];
        $updateExDiamond = $pdo->prepare('UPDATE `games_played` SET `coin`= :coin,`ex_diamond`= :objectUpdate WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
        $updateExDiamond->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'coin' => $coin, 'objectUpdate' => json_encode($taskObjectUser)));
        
        addLog("user id : [". $id_user ."], type : [Выполнил задание], task item : [". $task ."], time : [". date('Y-m-d H:i:s') ."]");
        
        exit(json_encode(array('type' => "ok", 'message' => "Задание выполнено"), JSON_UNESCAPED_UNICODE));
    }else{
        $resultMessage = null;
        switch ($typeTask) {
            case 'openLink':
                $resultMessage = openLink($objectTask);
                break;
            case 'addUser':
                $resultMessage = addUser($objectTask, $id_user);
                break;
        }
        exit(json_encode(array('type' => "ok", 'message' => "", 'attr' => $resultMessage), JSON_UNESCAPED_UNICODE));
    }


}else{
    exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."), JSON_UNESCAPED_UNICODE));
}

function addLog($text){
    $fd = fopen("../../log/task.txt", 'a+');
    fputs($fd, $text. PHP_EOL);
    fclose($fd);
}

function openLink($object){
    return array('type' => $object['itemType'], 'link' => $object['link']);
}
function openModal($title, $description, $attr = null){
    return array('title' => $title, 'description' => $description, 'attr' => $attr);
}
function addUser($object, $id){
    return array('type' => $object['itemType'], 'modal' => openModal($object['itemTitle'], URL. "ref.php?ref=". $id));
}