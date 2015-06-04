<?php

$redirect_page = 'http://www.traian4.embassy-pub.ro/new-pdo';
$redirect = true;

if($redirect === true){
    header('Location: '.$redirect_page);
}