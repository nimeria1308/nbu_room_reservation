<?php
require_once('libraries/forceadmin.php');
require_once('libraries/myview.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_REQUEST['room_id'];
$event_id = $_GET['event_id'];

if (isset($_POST['event'])) {
    // called for deleting multiple events
    foreach ($_POST['event'] as $event_id) {
        echo "Deleting multiple events $event_id from $room_id";
        delete_reservation($event_id, $room_id);
    }
} else if (isset($_GET['event'])) {
    // called for deleting a single event
    $event_id = $_GET['event'];
    echo "Deleting single event $event_id from $room_id";
    
}
delete_reservation($event_id, $room_id);
header("Location: /rooms/$room_id/events");

?>
