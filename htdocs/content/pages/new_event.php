<?php
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

$event_start = DateTime::createFromFormat(DateTime::ISO8601, $_GET['start']);
$event_end = DateTime::createFromFormat(DateTime::ISO8601, $_GET['end']);
error_log($event_start->format(DateTime::ISO8601));
error_log($event_end->format(DateTime::ISO8601));

# Room view
$t = new MyView('event.phtml');
$t->render();
