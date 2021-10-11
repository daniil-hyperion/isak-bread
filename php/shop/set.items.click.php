<?php
include_once '../function.php';
include_once '../../config/config.php';

$jsonItems = json_decode(file_get_contents("../../config/items.shop.json"), true);

function getPromo($item_name){
    $pathFile = "../../config/promo/" . $item_name . ".txt";
    $resultPromo = null;
    if(file_exists($pathFile)){
        $id--;
        $file=file($pathFile); 

        for($i=0;$i<sizeof($file);$i++)
            if($i==$id){
                $resultPromo = substr($file[$i],0,-4);
                unset($file[$i]); 
            } 

        $fp=fopen($pathFile,"w"); 
        fputs($fp,implode("",$file)); 
        fclose($fp);
        return $resultPromo;
    }else{
        exit(json_encode(array('type' => "error", 'message' => "Отсутствует файл промо-кодов."),JSON_UNESCAPED_UNICODE));
    }
}

function logSave($text){

    $fd = fopen("../../log/product.txt", 'a+');
    fputs($fd, $text. PHP_EOL);
    fclose($fd);
}

if(isset($_SESSION['data']) AND isset($_POST['item'])){

    $id_user = $_SESSION['data']['id'];
    $item = $_POST['item'];

    $prize = null;
    $typeCoin = null;
    $buyProduct = false;
    $useProduct = false;

    $title = null;
    $description = null;

    $promo = null;

    for($i = 0; $i < count($jsonItems); $i++){
        if($jsonItems[$i]["itemName"] == $item){
            $prize = $jsonItems[$i]["itemPrize"];
            $typeCoin = $jsonItems[$i]["itemType"];
            $title = $jsonItems[$i]["itemTitle"];
            $description = $jsonItems[$i]["itemDescription"];
            break;
        }
    }
    if($prize == null){
        exit(json_encode(array('type' => "error", 'message' => "Предмет отсутствует в базе данных."), JSON_UNESCAPED_UNICODE));
    }

    $user = $pdo->prepare('SELECT * FROM `games_played` WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    $user->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user));
    $user = $user->fetch(PDO::FETCH_LAZY);

    $product = json_decode($user['price'], true);
    if($product[$item]){
        // if($product[$item]['buy'] != true){
        //     $product[$item]['buy'] = true;
        //     $product[$item]['dateBuy'] = date("Y-m-d H:i:s");
        //     $buyProduct = true;
        //     if($product[$item]['promo'] == "" OR $product[$item]['promo'] == null){
        //         $product[$item]['promo'] = getPromo($item);
        //     }else{
        //         $promo = $product[$item]['promo'];
        //     }
        // }else{
            if(isset($_POST['edit']) AND $_POST['edit'] == "use"){
                
                $product[$item]['used'] = true;
                $product[$item]['dateUse'] = date("Y-m-d H:i:s");
                $prize = 0;
                $useProduct = true;
                $promo = $product[$item]['promo'];
                if($product[$item]['itemType'] == "diamond"){
                    unset($product[$item]);
                }
                $datalog = "user id : [". $id_user ."], type : [Использовал товар], product item : [". $item ."], time : [". date('Y-m-d H:i:s') ."]";
                logSave($datalog);

                
            }else{
                $prize = 0;
                $buyProduct = true;
                $promo = $product[$item]['promo'];
                if($product[$item]['used'] == true){
                    if($product[$item]['itemType'] == "diamond"){
                        unset($product[$item]);
                    }
                    $useProduct = true;
                }
                
            }
            
        // }
    }else{
        $promo = getPromo($item);
        $product[$item] = array(
            'buy' => true,
            'dateBuy' => date("Y-m-d H:i:s"),
            'promo' => $promo,
            'itemType' => $typeCoin,
            'used' => false,
            'activ' => true
        );
        $buyProduct = true;

        $datalog = "user id : [". $id_user ."], type : [Приобрел товар], product item : [". $item ."], time : [". date('Y-m-d H:i:s') ."]";
        logSave($datalog);
        
    }

    $newProduct = json_encode($product, JSON_UNESCAPED_UNICODE);
    if($typeCoin == "tokens"){
        $prize = 0;
        $tokens = $user['tokens'] - $prize;
        $updateTokens = $pdo->prepare('UPDATE `games_played` SET `price`= :newPrizeList, `tokens`= :coin WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    }else{
        if($prize > 0){
            $tokens = $user['coin'] - $prize;
        }else{
            $tokens = $user['coin'];
        }
        
        $updateTokens = $pdo->prepare('UPDATE `games_played` SET `price`= :newPrizeList, `coin`= :coin WHERE `id_game` = :id_game AND `id_company` = :id_company AND `id_user` = :id_user');
    }
           
    $updateTokens->execute(array('id_game' => GAME_ID, 'id_company' => COMPANY_ID, 'id_user' => $id_user, 'coin' => $tokens, 'newPrizeList' => $newProduct));

    exit(json_encode(array('type' => "ok", 'message' => "", 'attr' => ['buyProduct' => $buyProduct, 'useProduct' => $useProduct, 'title' => $title, 'description' => $description], 'promo' => $promo, 'p' => $prize), JSON_UNESCAPED_UNICODE));
}else{
    exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."), JSON_UNESCAPED_UNICODE));
}