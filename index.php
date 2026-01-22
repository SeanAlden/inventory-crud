<?php
session_start(); 

include 'config/database.php';

include 'controllers/AsetController.php';

$controller = new AsetController($conn);

$controller->index();
?>