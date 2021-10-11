<?php
include_once '../config/config.php';

session_start();

function getContent($key){
    $content = file_get_contents($path . "config/content.json");
    $json = json_decode($content, true);
    if(isset($json[$key])){
        return $json[$key];
    }else{
        return null;
    }
}

function getUrl($fileName = null){
    if($fileName == null){
        return $path;
    }else{
        if(mb_substr($fileName, 0, 1) != "/"){
            return $path. "". $fileName;
        }else{
            return $path. "". substr($fileName, 1);
        }
    }
}