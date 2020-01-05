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
$t->title = "Заявки";

$t->render();

$t = new MyView("events.phtml");
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
