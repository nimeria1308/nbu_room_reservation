<?php
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

echo $room['title'];

$now = new DateTime();

if (isset($_GET['start']) and isset ($_GET['end'])) {
    $event_start = DateTime::createFromFormat(DateTime::ISO8601, $_GET['start']);
    $event_end = DateTime::createFromFormat(DateTime::ISO8601, $_GET['end']);
} else {
    $event_start = $now;
    $event_end = clone $event_start;
    $event_end->modify('+1 hour');
}

# new event view
$t = new MyView('event.phtml');
$t->room = $room;
$t->room_id = $room_id;
$t->min_date = $now->format("Y-m-d");
$t->date = $event_start->format("Y-m-d");
$t->start_time = $event_start->format("H:i");
$t->end_time = $event_end->format("H:i");
$t->render();
