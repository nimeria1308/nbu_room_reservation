<?php
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

echo $room['title'];

if (isset($_GET['start']) and isset ($_GET['end'])) {
    $event_start = DateTime::createFromFormat(DateTime::ISO8601, $_GET['start']);
    $event_end = DateTime::createFromFormat(DateTime::ISO8601, $_GET['end']);
} else {
    $event_start = new DateTime();
    $event_end = clone $event_start;
    $event_end->modify('+1 hour');
}

# Room view
$t = new MyView('event.phtml');
$t->room = $room;
$t->event_start = $event_start;
$t->event_end = $event_end;
$t->render();
