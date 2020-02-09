<?php
require_once('libraries/myview.php');
require_once('model/room.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

$t = new MyView('header.phtml');
$t->title = $room['title'];

# Add extra stuff in head
$t->head_elements = [
    "<script>var room_id=$room_id; var room_color='$room[color]';</script>"
];

$t->stylesheets = [
    'fullcalendar/core/main.min.css',
    'fullcalendar/daygrid/main.min.css',
    'fullcalendar/list/main.min.css',
    'fullcalendar/timegrid/main.min.css',
    'calendar.css',
];

$t->scripts = [
    'fullcalendar/core/main.min.js',
    'fullcalendar/core/locales/bg.js',
    'fullcalendar/daygrid/main.min.js',
    'fullcalendar/interaction/main.min.js',
    'fullcalendar/list/main.min.js',
    'fullcalendar/timegrid/main.min.js',
    'event.js',
    'calendar.js',
];

$t->render();

# Room view
$t = new MyView('room.phtml');
$t->render();

# Footer
$t = new MyView('footer.phtml');
$t->render();
