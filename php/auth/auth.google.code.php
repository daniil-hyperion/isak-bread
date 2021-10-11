<?php

include_once '../function.php';
include_once '../../config/config.php';

if(isset($_SESSION['tokens'])){
    $tokens = intval($_SESSION['tokens']);
}else{
    $tokens = 0;
}

if(!isset($_SESSION['data'])){

    if (isset($_GET['code'])) {
        $paramsGoogle = array(
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT,
            'grant_type'    => 'authorization_code',
            'code'          => $_GET['code']
        );	
                
        $ch = curl_init('https://accounts.google.com/o/oauth2/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsGoogle); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $data = curl_exec($ch);
        curl_close($ch);	
    
        $data = json_decode($data, true);
        if (!empty($data['access_token'])) { // Токен получили, получаем данные пользователя.
            $paramsGoogle = array(
                'access_token' => $data['access_token'],
                'id_token'     => $data['id_token'],
                'token_type'   => 'Bearer',
                'expires_in'   => 3599
            );
    
            $userInfo = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($paramsGoogle)));
            $userInfo = json_decode($userInfo, true);
        }

        $google_id = $userInfo['id'];
        $google_name = $userInfo['name']. " " . $userInfo['given_name'];

        $user = $pdo->prepare('SELECT * FROM `users` WHERE `google_id` = :google_id');
        $user->execute(array('google_id' => $google_id));
        $user = $user->fetch(PDO::FETCH_LAZY);

        if($user['id']){
            $gamePlayed = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
            $gamePlayed->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id']));
            $gamePlayed = $gamePlayed->fetch(PDO::FETCH_LAZY);

            if(!$gamePlayed['id_cell']){
                //Пользователь еще не играл не разу в этом приложении
                $game_played_insert = $pdo->prepare("INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, '', :tokens, 0, '')");
                $game_played_insert->execute(['id_company' => COMPANY_ID, 'id_game' => GAME_ID, 'game_name' => GAME_NAME, 'id_user' => $user['id'], 'tokens' => $tokens]);

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

            $week_raiting = $pdo->prepare('SELECT * FROM `weekly_rating` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
            $week_raiting->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id']));
            $week_raiting = $week_raiting->fetch(PDO::FETCH_LAZY);

            if(!$week_raiting['id_cell']){
                //Пользователь еще не играл не разу в этом приложении
                $week_update = $pdo->prepare('INSERT INTO `weekly_rating`(`id_cell`, `id_game`, `id_company`, `id_user`, `data`, `tokens`) VALUES (NULL,:id_game, :id_company, :id_user, :date, :token)');
                $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id'], 'date' => date("Y-m-d"), 'token' => $tokens));
            }else{
                if($week_raiting['tokens'] < $tokens){
                    $week_update = $pdo->prepare('UPDATE `weekly_rating` SET `data`=:date,`tokens`=:token WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
                    $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id'], 'date' => date("Y-m-d"), 'token' => $tokens));
                }
            }

            $_SESSION['data'] = array(
                'id' => $user['id'],
                'name' => $user['name']
            );

            exit(header('Location: '. URL . 'shop.php'));
        }else{
            //Создаем нового пользователя
            $reg = $pdo->prepare('INSERT INTO `users`(`id`, `name`, `date_reg`, `vk_id`, `tg_id`, `google_id`, `coin`, `avatar`, `email`, `password`) VALUES (NULL, :username, :data_reg, "", "", :google_id, 0, "avatars/5.png", "", "")');
            $reg->execute(array('username' => $google_name, 'data_reg' => date("Y-m-d H:i:s"), 'google_id' => $google_id));

            $user = $pdo->prepare('SELECT * FROM `users` WHERE `google_id` =:google_id');
            $user->execute(array('google_id' => $google_id));
            $user = $user->fetch(PDO::FETCH_LAZY);

            if($user['id']){
                $game_played_insert = $pdo->prepare("INSERT INTO `games_played`(`id_cell`, `id_company`, `id_game`, `game_name`, `id_user`, `price`, `tokens`, `coin`, `ex_diamond`) VALUES (NULL, :id_company, :id_game, :game_name, :id_user, '', :tokens, 0, '')");
                $game_played_insert->execute(['id_company' => COMPANY_ID, 'id_game' => GAME_ID, 'game_name' => GAME_NAME, 'id_user' => $user['id'], 'tokens' => $tokens]);

                $week_update = $pdo->prepare('INSERT INTO `weekly_rating`(`id_cell`, `id_game`, `id_company`, `id_user`, `data`, `tokens`) VALUES (NULL,:id_game, :id_company, :id_user, :date, :token)');
                $week_update->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $user['id'], 'date' => date("Y-m-d"), 'token' => $tokens));

                $_SESSION['data'] = array(
                    'id' => $user['id'],
                    'name' => $user['name']
                );

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
                exit(header('Location: '. URL . 'shop.php'));
            }else{
                exit(json_encode(array('type' =>"error", 'message' => "Произошла ошибка авторизации через GOOGLE.")));
            }
        }
    }
}else{
    exit(header('Location: '. URL . 'shop.php'));
}