<?php

include_once '../function.php';
include_once '../../config/config.php';

$paramsGoogle = array(
    'client_id'     => GOOGLE_CLIENT_ID,
    'redirect_uri'  => GOOGLE_REDIRECT,
    'response_type' => 'code',
    'scope'         => GOOGLE_SCOPE,
    'state'         => '123'
);
    
$urlGoogle = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($paramsGoogle));

header('Location: ' . $urlGoogle . '');