<?php

function backend_log_in_ok($username, $password)
{
    return $username == "admin" and $password == "admin";
}

$username = "";
$logged_in = false;

if (array_key_exists('username', $_POST) and array_key_exists('password', $_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $logged_in = backend_log_in_ok($username, $password);
}

$expire = $logged_in ? 0 : time() - 3600;

setcookie('username', $username, $expire, '/');
setcookie('is_admin', 'true', $expire, '/');

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($logged_in ? 'ok' : 'fail')]);
