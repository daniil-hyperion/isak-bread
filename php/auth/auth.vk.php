<?php

include_once '../function.php';
include_once '../../config/config.php';

$url = "http://oauth.vk.com/authorize";

$params = [ 'client_id' => VK_CLIENT_ID, 'redirect_uri'  => VK_REDIRECT, 'response_type' => 'code'];

header('Location:  ' . $url . '?' . urldecode(http_build_query($params)) . '');