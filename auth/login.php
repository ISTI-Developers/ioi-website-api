<?php

require_once __DIR__ . '/../config/cors.php';  
require_once __DIR__ . '/../controller/authcontroller.php';

setCorsHeaders(); 

$auth = new AuthController();
$auth->login(); 