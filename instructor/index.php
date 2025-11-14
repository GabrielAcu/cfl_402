<?php
require_once '../include/header.php'; 
require_once '../auth/check.php';

requireLogin();

if (!isInstructor()) {
    header('Location: /gb/cfl_402/index.php');
    exit();
}

echo "Dashboard del INSTRUCTOR";