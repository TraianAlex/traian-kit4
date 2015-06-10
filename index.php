<?php

if ($_SERVER['HTTP_HOST'] == 'www.traian4.embassy-pub.ro'){
	$redirect_page = 'http://www.traian4.embassy-pub.ro/new-pdo';
}else{
	$redirect_page = 'http://localhost/traian-kit4/new-pdo';
}
$redirect = true;

if($redirect === true){
    header('Location: '.$redirect_page);
}