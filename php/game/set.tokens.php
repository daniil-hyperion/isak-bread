<?php
include_once '../function.php';
include_once '../../config/config.php';

if(isset($_POST['tokens']) AND isset($_SESSION['data'])){
    $token = $_POST['tokens'];
    $id_user = $_SESSION['data']['id'];

    $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $user = $user->fetch(PDO::FETCH_LAZY);
    if($user['id_cell']){
        if($user['tokens'] < $token){
            $token_update = $pdo->prepare('UPDATE `games_played` SET `tokens`= :token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
            $token_update->execute(array('token' => $token, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
        }
    }else{
        $token_update = $pdo->prepare('INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, "", :token, 0, "")');
        $token_update->execute(array('token' => $token, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'game_name' => GAME_NAME, ));
    }

    $week_raiting = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $week_raiting->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $week_raiting = $week_raiting->fetch(PDO::FETCH_LAZY);

    if($week_raiting['id_cell']){
        if($week_raiting['tokens'] < $token){
            $week_update = $pdo->prepare('UPDATE `weekly_rating` SET `data`=:date,`tokens`=:token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
            $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'date' => date("Y-m-d"), 'token' => $token));
        }
    }else{
        $week_update = $pdo->prepare('INSERT INTO `weekly_rating`(`id_cell`, `id_game`, `id_company`, `id_user`, `data`, `tokens`) VALUES (NULL,:id_game, :id_company, :id_user, :date, :token)');
        $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'date' => date("Y-m-d"), 'token' => $token));
    }
    exit(json_encode(array('type' => "ok", 'message' => "Данные сохранены."),JSON_UNESCAPED_UNICODE));
}else{
    if(isset($_POST['tokens']) AND !isset($_SESSION['data'])){
        $token = $_POST['tokens'];
        if(isset($_SESSION['tokens'])){
            if($token > $_SESSION['tokens']){
                $_SESSION['tokens'] = $token;
            }
        }else{
            $_SESSION['tokens'] = $token;
        }
    }else{
        exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."),JSON_UNESCAPED_UNICODE));
    }
}