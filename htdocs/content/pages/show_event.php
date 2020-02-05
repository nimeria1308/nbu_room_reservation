<?php
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);
$event_id = $_GET['id'];

// TODO: Get event from backend
$event = [];

# show event view
$t = new MyView('show_event.phtml');
$t->room = $room;
$t->room_id = $room_id;
$t->event = $event;
$t->render();
