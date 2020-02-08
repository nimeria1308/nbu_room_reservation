<?php
require_once('libraries/forceadmin.php');
require_once('libraries/myview.php');
require_once('model/room.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

# New room view
$t = new MyView('edit_room.phtml');
$t->room = $room;
$t->room_id = $room_id;
$t->render();
