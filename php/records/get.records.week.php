<?php
include_once '../function.php';
include_once '../../config/config.php';

$page = 1;

if(isset($_GET['page'])){
    if($_GET['page'] > $page){
        $page = $_GET['page'];
    }
}

$records = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company ORDER BY `tokens` DESC LIMIT :idStart, :idOut');
$records->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'idStart' => (($page-1) * COUNTRECORDSWEEK ), 'idOut' => COUNTRECORDSWEEK));

$result = [];
$my_result = [];
$j =0;
while ($user = $records->fetch(PDO::FETCH_LAZY)) {
    if($user['tokens'] > 0){
        $userInfo = $pdo->prepare('SELECT * FROM `users` WHERE `id`=:id');
        $userInfo->execute(array('id' => $user['id_user']));
        $userInfo = $userInfo->fetch(PDO::FETCH_LAZY);
        
        array_push($result, array('name' => $userInfo['name'], 'tokens' => $user['tokens'], 'position' => ((($page-1) * COUNTRECORDSWEEK) + ($j+1))));
        
        $j++;
    }
        
}

$records = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company ORDER BY `tokens` DESC');
$records->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID));

$i = 0;
while ($user = $records->fetch(PDO::FETCH_LAZY)) {
    if(isset($_SESSION['data'])){
    $id_user = $_SESSION['data']['id'];
        if($user['id_user'] == $id_user){
            $my_result = array('name' => $_SESSION['data']['name'], 'position' => ($i+1), 'tokens' => $user['tokens']);
        }
    }
    if($user['tokens'] > 0){
        $i++;
    }
}

exit(json_encode(array('type' => "ok", 'records' => $result, 'myRecords' => $my_result, 'pages' => ceil($i/COUNTRECORDSWEEK)), JSON_UNESCAPED_UNICODE));