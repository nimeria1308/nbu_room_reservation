<?php
require_once('model/room.php');

# TODO
$status = false;

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($status ? 'ok' : 'fail')]);
