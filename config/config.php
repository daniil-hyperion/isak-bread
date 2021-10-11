<?php
define('URL', "*****");

define('COMPANY_ID', "6");
define('GAME_ID', "1");
define('GAME_NAME', "Шаблон");

define("COUNTRECORDSWEEK", "10");

define("DB_HOST", "*****");
define("DB_NAME", "*****");
define("DB_USERNAME", "*****");
define("DB_PASSWORD", "*****");
define("CHARSET", "utf8");

//vk key
define("VK_CLIENT_ID", "*****");
define("VK_CLIENT_SECRET", "*****");
define("VK_REDIRECT", "*****");

//google key
define("GOOGLE_CLIENT_ID", "*****");
define("GOOGLE_CLIENT_SECRET", "*****");
define("GOOGLE_REDIRECT", "*****");
define("GOOGLE_SCOPE", "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile");

$path = "*****";
$url = "*****";


$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET;
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $opt);