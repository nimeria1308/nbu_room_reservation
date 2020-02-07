<?php
require_once('libraries/forceadmin.php');
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');
require_once('model/term.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);
$event_id = $_GET['id'];
$terms = get_terms($room_id);

// TODO: Get event from backend
$event = get_event_by_id($event_id,$room);
if($event['multimedia']=="+техника"){
	$event['option']="Да";
}else{
	$event['option']="Не";
}

# show event view
$t = new MyView('edit_event.phtml');
$t->room = $room;
$t->room_id = $room_id;
$t->event = $event;
$t->terms=$terms;
$t->render();
