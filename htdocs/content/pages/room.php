<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');
require_once('model/rooms.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

$t = new MyView('header.phtml');
$t->title = $room['title'];

# Add extra stuff in head
$t->head_elements = [
    "<script>var room_id=$room_id; var room_color='$room[color]';</script>"
];

$t->stylesheets = [
    'calendar.css',
];

$t->scripts = [
    'calendar.js',
    'external/calendar.js',
];

$t->render();

# Room view
$t = new MyView('room.phtml');
$t->id = $_GET['room_id'];
$t->render();

# Footer
$t = new MyView('footer.phtml');
$t->render();
