<?php
include_once '../function.php';
include_once '../../config/config.php';

if(isset($_SESSION['tokens'])){
    $tokens = intval($_SESSION['tokens']);
}else{
    $tokens = 0;
}

if(isset($_POST['email']) AND isset($_POST['password']) AND !isset($_SESSION['data'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password . " |");
    $user = $pdo->prepare('SELECT * FROM `users` WHERE email = :email AND password = :pass');
    $user->execute(array('email' => $email, 'pass' => $password));
    $user = $user->fetch(PDO::FETCH_LAZY);
    if($user['id']){
        $_SESSION['data'] = array('id' => $user['id'], 'name' => $user['name']);

        $game_played = $pdo->prepare("SELECT * FROM `games_played` WHERE `id_user` = :id_user AND `id_game` = :id_game AND `id_company` = :id_company");
        $game_played->execute(['id_user' => $user['id'], 'id_game' => GAME_ID, 'id_company' => COMPANY_ID]);
        $game_played = $game_played->fetch(PDO::FETCH_LAZY);

        if(!$game_played['id_cell']){
            $game_played_insert = $pdo->prepare("INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, '', :tokens, 0, '')");
            $game_played_insert->execute(['id_company' => COMPANY_ID, 'id_game' => GAME_ID, 'game_name' => GAME_NAME, 'id_user' => $user['id'], 'tokens' => $tokens]);
        }else{
            if($game_played['tokens'] < $tokens){
                $token_update = $pdo->prepare('UPDATE `games_played` SET `tokens`= :token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                $token_update->execute(array('token' => $tokens, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id']));
            }
        }

        $week_raiting = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
        $week_raiting->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id']));
        $week_raiting = $week_raiting->fetch(PDO::FETCH_LAZY);

        if(!$week_raiting['id_cell']){
            $week_update = $pdo->prepare('INSERT INTO `weekly_rating`(`id_cell`, `id_game`, `id_company`, `id_user`, `data`, `tokens`) VALUES (NULL,:id_game, :id_company, :id_user, :date, :token)');
            $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id'], 'date' => date("Y-m-d"), 'token' => $tokens));
        }else{
            if($week_raiting['tokens'] < $tokens){
                $week_update = $pdo->prepare('UPDATE `weekly_rating` SET `data`=:date,`tokens`=:token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id'], 'date' => date("Y-m-d"), 'token' => $tokens));
            }
        }

        exit(json_encode(array('type' => "ok", 'message' => "Авторизация успешна."),JSON_UNESCAPED_UNICODE));
    }else{
        exit(json_encode(array('type' => "error", 'message' => "Неверно указанный E-mail или Пароль."),JSON_UNESCAPED_UNICODE));
    }
    exit(json_encode(array('type' => "ok", 'message' => "Авторизация успешна."),JSON_UNESCAPED_UNICODE));

    
}else{
    if(isset($_SESSION['data'])){
        $id_user = $_SESSION['data']['id'];

        $game_played = $pdo->prepare("SELECT * FROM `games_played` WHERE `id_user` = :id_user AND `id_game` = :id_game AND `id_company` = :id_company");
        $game_played->execute(['id_user' => $id_user, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID]);
        $game_played = $game_played->fetch(PDO::FETCH_LAZY);
        if(!$game_played['id_cell']){
            $game_played_insert = $pdo->prepare("INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, '', :tokens, 0, '')");
            $game_played_insert->execute(['id_company' => COMPANY_ID, 'id_game' => GAME_ID, 'game_name' => GAME_NAME, 'id_user' => $id_user, 'tokens' => $tokens]);
        }

        $week_raiting = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
        $week_raiting->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
        $week_raiting = $week_raiting->fetch(PDO::FETCH_LAZY);

        if(!$week_raiting['id_cell']){
                //Пользователь еще не играл не разу в этом приложении
            $week_update = $pdo->prepare('INSERT INTO `weekly_rating`(`id_cell`, `id_game`, `id_company`, `id_user`, `data`, `tokens`) VALUES (NULL,:id_game, :id_company, :id_user, :date, :token)');
            $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'date' => date("Y-m-d"), 'token' => $tokens));
        }else{
            if($week_raiting['tokens'] < $tokens){
                $week_update = $pdo->prepare('UPDATE `weekly_rating` SET `data`=:date,`tokens`=:token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'date' => date("Y-m-d"), 'token' => $tokens));
            }
        }
        exit(json_encode(array('type' => "ok", 'message' => "Пользователь уже авторизован."),JSON_UNESCAPED_UNICODE));
    }else{
        exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."),JSON_UNESCAPED_UNICODE));
    }
}