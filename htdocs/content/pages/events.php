<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');
require_once('model/room.php');
require_once('model/event.php');

$room_id = $_GET['room_id'];
$room = get_room($room_id);

$t = new MyView('header.phtml');
$t->menu = [
    [
        'url' => "/rooms/$room_id",
        'title' => $room['title'],
    ]
];
$t->title = "Заявки за ползване на $room[title]";

$t->render();

# FIXME
$min_date = date('Y', 0);
$max_date = new DateTime("01/01/30");
$events = requests($room_id, $min_date, $max_date);

$t = new MyView("events.phtml");
$t->room = $room;
$t->events = $events;
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
