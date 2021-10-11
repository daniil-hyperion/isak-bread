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
    $user = $pdo->prepare('SELECT * FROM `users` WHERE email = :email');
    $user->execute(array('email' => $email));
    $user = $user->fetch(PDO::FETCH_LAZY);
    if($user['id']){
        exit(json_encode(array('type' => "error", 'message' => "Пользователь уже зарегистрирован."),JSON_UNESCAPED_UNICODE));
    }else{
        $email_domen = explode("@", $email);
        $name = $email_domen[0];
        $email_domen = $email_domen[1];
        $name = $name[0]. $name[1]. $name[2]. $name[3]. "****" . $email_domen;
        $data_reg = date("Y-m-d H:i:s");
        $reg = $pdo->prepare('INSERT INTO `users`(`id`, `name`, `date_reg`, `vk_id`, `tg_id`, `google_id`, `coin`, `avatar`, `email`, `password`) VALUES (NULL, :username, :data_reg, "", "", "", 0, "avatars/5.png", :email, :pass)');
        $reg->execute(array('username' => $name, 'data_reg' => $data_reg, 'email' => $email, 'pass' => $password));

        $user = $pdo->prepare('SELECT * FROM `users` WHERE email = :email AND password = :pass');
        $user->execute(array('email' => $email, 'pass' => $password));
        $user = $user->fetch(PDO::FETCH_LAZY);
        if($user['id']){
            $id_user = $user['id'];
            $game_played = $pdo->prepare("SELECT * FROM `games_played` WHERE `id_user` = :id_user AND `id_game` = :id_game AND `id_company` = :id_company");
            $game_played->execute(['id_user' => $id_user, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID]);
            $game_played = $game_played->fetch(PDO::FETCH_LAZY);
            if(!$game_played['id_cell']){
                $game_played_insert = $pdo->prepare("INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, '', :tokens, 0, '')");
                $game_played_insert->execute(['id_company' => COMPANY_ID, 'id_game' => GAME_ID, 'game_name' => GAME_NAME, 'id_user' => $id_user, 'tokens' => $tokens]);
            }else{
                if($game_played['tokens'] < $tokens){
                    $token_update = $pdo->prepare('UPDATE `games_played` SET `tokens`= :token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                    $token_update->execute(array('token' => $tokens, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
                }
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
            $_SESSION['data'] = array('id' => $user['id'], 'name' => $user['name']);

            if(isset($_SESSION['ref'])){
                $ref_sys = $pdo->prepare("SELECT * FROM `games_played` WHERE `id_user` = :id_user AND `id_game` = :id_game AND `id_company` = :id_company");
                $ref_sys->execute(['id_user' => $_SESSION['ref'], 'id_game' => GAME_ID, 'id_company' => COMPANY_ID]);
                $ref_sys = $ref_sys->fetch(PDO::FETCH_LAZY);
                if($ref_sys['id_cell']){
                    $json_task = json_decode(file_get_contents("../../config/items.task.json"),true);
                    $coin_json = 0;
                    for($i= 0; $i < count($json_task); $i++){
                        if($json_task[$i]['itemType'] == 'addUser'){
                            $coin_json = $json_task[$i]['itemPrize'];
                            break;
                        }
                    }
                    $coin_ref = $ref_sys['coin'] + $coin_json;
                    $token_update = $pdo->prepare('UPDATE `games_played` SET `coin`= :coin WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                    $token_update->execute(array('coin' => $coin_ref, 'id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $_SESSION['ref']));
                }
            }
            
        }
        exit(json_encode(array('type' => "ok", 'message' => "Пользователь зарегистрирован."),JSON_UNESCAPED_UNICODE));
    }
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
                $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' =>$id_user, 'date' => date("Y-m-d"), 'token' => $tokens));
            }
        }
        exit(json_encode(array('type' => "ok", 'message' => "Пользователь уже зарегистрирован."),JSON_UNESCAPED_UNICODE));
    }else{
        exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."),JSON_UNESCAPED_UNICODE));
    }
}