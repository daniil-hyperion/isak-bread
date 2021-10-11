<?php
include_once '../function.php';
include_once '../../config/config.php';

$records = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company ORDER BY `tokens` DESC');
$records->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID));

$i = 0;
$result = [];
$my_result = [];

while ($user = $records->fetch(PDO::FETCH_LAZY)) {
    if($i < 10 AND $user['tokens'] > 0){
        $userInfo = $pdo->prepare('SELECT * FROM `users` WHERE `id`=:id');
        $userInfo->execute(array('id' => $user['id_user']));
        $userInfo = $userInfo->fetch(PDO::FETCH_LAZY);
        
        array_push($result, array('name' => $userInfo['name'], 'tokens' => $user['tokens']));
        $i++;
        
        if(isset($_SESSION['data'])){
        $id_user = $_SESSION['data']['id'];
        if($user['id_user'] == $id_user){
            $my_result = array('name' => $_SESSION['data']['name'], 'position' => $i, 'tokens' => $user['tokens']);
        }
    }
    }
    
}
exit(json_encode(array('type' => "ok", 'records' => $result, 'myRecords' => $my_result)));