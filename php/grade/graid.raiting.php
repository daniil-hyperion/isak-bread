<?php
include_once '../function.php';
include_once '../../config/config.php';

if(isset($_SESSION['data']) AND isset($_POST['grade'])){
    $id_user = $_SESSION['data']['id'];
    $grade = $_POST['grade'];

    $user = $pdo->prepare('INSERT INTO `grade_company`(`id_cell`, `id_company`, `grade`, `id_user`) VALUES (NULL, :id_company, :grade, :id_user)');
    $user->execute(array('id_company' => COMPANY_ID, 'id_user' => $id_user, 'grade' => $grade));

    addLog("id user : [". $id_user. "] (оценил обслуживание в организации) : [". COMPANY_ID ."] оценка : [". $grade . "] time : [". date('Y-m-d H:i:s') ."]");

    exit(json_encode(array('type' => "ok", 'message' => ""),JSON_UNESCAPED_UNICODE));
}else{
    exit(json_encode(array('type' => "error", 'message' => "Несанкционированный доступ к файлу."),JSON_UNESCAPED_UNICODE));
}

function addLog($text){
    $fd = fopen("../../log/product.raiting.txt", 'a+');
    fputs($fd, $text. PHP_EOL);
    fclose($fd);
}