<?php
include __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/functions.php';

if (is_authenticated()) {
    // session_destroy();
    $_SESSION['logged_in'] = false;
    unset($_SESSION['user']);

    session_regenerate_id();

    // Delete the cookie "remember_token"
    setcookie('remember_token', '', time() - 60 * 60, '/');
}

redirect('login.php');