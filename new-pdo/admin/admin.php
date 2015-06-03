<?php

file_exists( "../../config" . ".php" ) ? include_once "../../config".".php" : print 'Sorry';
$load_pages = new Controller();
$load_pages->index();