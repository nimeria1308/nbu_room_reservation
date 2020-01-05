<?php
require_once('model/event.php');
require_once('model/room.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

# TODO
$status = false;

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($status ? 'ok' : 'fail')]);
