<?php
require_once('libraries/forceadmin.php');
require_once('model/event.php');
require_once('model/room.php');

$room_id = $_POST['room_id'];
$room = get_room(intval($room_id));

# TODO: validate data and edit event in backend
# input: the data is received in $_POST
# output: set $status to true if OK
#         set $status to false if failed, and fill in $error

$status = true;
$error = "message explaining error";

$result = ['status' => ($status ? 'ok' : 'fail')];
if (!$status) {
    $result['error'] = $error;
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);
