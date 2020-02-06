<?php
require_once('model/event.php');
require_once('model/room.php');

$room_id = $_POST['room_id'];
$room = get_room(intval($room_id));

$_POST['phone']=(int)$_POST['phone'];
$_POST['weekly_repeat']=(int)$_POST['weekly_repeat'];
$_POST['repeat_end_count']=(int)$_POST['repeat_end_count'];

if($_POST['multimedia']=='yes'){
	$_POST['multimedia']=true;
}else{
	$_POST['multimedia']=false;
}

$arr=new_event($_POST);
# TODO: validate data and create new event in backend + send an email from backend
# input: the data is received in $_POST
# output: set $status to true if OK
#         set $status to false if failed, and fill in $error



$result = ['status' => ($arr[0] ? 'ok' : 'fail')];
if (!$arr[0]) {
    $result['error'] = $arr[1];
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);
