<?php

function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_name('jawwal_go_sid');
        session_start();
    }
}

function is_authenticated() {
    global $mysqli; // Import variable from global scope.

    start_session();

    if (! isset($_SESSION['user'])) {
        $token = $_COOKIE['remember_token'] ?? 0;
        if ($token) {
            $query = 'SELECT * FROM users WHERE remember_token = ?';
            $stmt = $mysqli->prepare($query);
            $stmt->execute([$token]);
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
                login($row);
                return true;
            }
        }
        return false;
    }

    return true;
}

// $row is an array contain the user's data from the database
function login($row) {
    start_session();
    session_regenerate_id();

    $_SESSION['logged_in'] = true;
    $_SESSION['logged_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_name'] = $row['name'];
    
    $_SESSION['user'] = $row; // array: $_SESSION['user']['name'], $_SESSION['user']['id'], ...
}

function redirect($url) {
    header("Location: $url");
    exit;
}