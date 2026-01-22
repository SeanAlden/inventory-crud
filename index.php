<?php

require_once 'config/database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AsetController.php';

$auth = new AuthController($conn);
$auth->checkAuth(); 

$data = new AsetController($conn);
$data->index();