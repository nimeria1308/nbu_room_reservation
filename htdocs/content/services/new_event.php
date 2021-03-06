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

$arr=new_event($_POST,$room);

$result = ['status' => ($arr['status'] ? 'ok' : 'fail')];
if (!$arr['status']) {
    $result['error'] = $arr['error'];
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($result);
