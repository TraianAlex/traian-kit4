<?php

    file_exists('../config'.'.php') ? require_once '../config'.'.php' : print 'Sorry';
    $controller = new Controller();
    $controller->index();