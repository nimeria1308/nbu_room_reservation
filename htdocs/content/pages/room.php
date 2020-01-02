<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');

#TODO: Read room details from backend

$t = new MyView('header.phtml');
$t->title = "Резервиране на зали към НБУ Библиотека";

# Add extra stuff in head
$t->stylesheets = [
    '/external/node_modules/@fullcalendar/core/main.css',
];

$t->scripts = [
    '/external/node_modules/@fullcalendar/core/main.js',
    '/resources/javascipt/calendar.js',
];

$t->render();

# Room view
$t = new MyView('room.phtml');
$t->id = $_GET['room_id'];
$t->render();

# Footer
$t = new MyView('footer.phtml');
$t->render();
