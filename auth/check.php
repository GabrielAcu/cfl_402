<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLogin() {
    return isset($_SESSION['user']);
}

function requireLogin() {
    if (!isLogin()) {
        header('Location: /cfl_402/index.php');
        exit();
    }
}

function isSuperAdmin() {
    return isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] == 2;
}

function isAdmin() {
    return isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] == 0;
}

function isInstructor() {
    return isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] == 1;
}

function idAdminOrInstructor() {
    if (isAdmin() || isSuperAdmin()) {
        header('Location: /cfl_402/admin');
        exit();
    } elseif (isInstructor()) {
        header('Location: /cfl_402/instructor');
        exit();
    }
}

