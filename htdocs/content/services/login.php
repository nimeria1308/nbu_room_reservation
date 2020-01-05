<?php

function backend_log_in_ok($username, $password) {
    # TODO:
    return true;
}

$username = $_GET['username'];
$password = $_GET['password'];
$logged_in = backend_log_in_ok($username, $password);

if ($logged_in) {
    setcookie('username', $username, 0, '/');
    setcookie('is_admin', 'true', 0, '/');
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($logged_in ? 'ok' : 'fail')]);
