<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');

#TODO: Read room details from backend

$t = new MyView('header.phtml');
$t->title = "Резервиране на зали към НБУ Библиотека";

# Add extra stuff in head
$t->stylesheets = [
    'calendar.css',
];

$t->scripts = [
    'jquery-3.4.1.min.js',
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
