<?php
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);
$event_id = $_GET['id'];

// backend
$event = get_event_by_id($event_id,$room);
if($event['multimedia']=="+техника"){
	$event['option']="Да";
}else{
	$event['option']="Не";
}

$time = strtotime($event['start_date']);
$event['start_date'] = date('Y-m-d',$time);

# show event view
$t = new MyView('show_event.phtml');
$t->room = $room;
$t->room_id = $room_id;
$t->event = $event;
$t->render();
