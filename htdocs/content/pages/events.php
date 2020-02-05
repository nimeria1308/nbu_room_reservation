<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');
require_once('model/room.php');
require_once('model/event.php');

// go back to home if not admin
if (!is_admin()) {
    header("Location: /");
    return;
}

$room_id = $_GET['room_id'];
$room = get_room($room_id);

$t = new MyView('header.phtml');
$t->menu = [
    [
        'url' => "/rooms/$room_id",
        'title' => $room['title'],
    ]
];

$t->scripts = [
    'event.js',
];

$t->stylesheets = [
    'events.css',
];

$t->title = "Заявки за ползване на $room[title]";

$t->render();

$events = [];
$search = array_key_exists('search', $_GET) ? trim($_GET['search']) : "";

if ($search) {
    // if search provided, search in the backend for those events
    // TODO: pass events from backend
    $events = [];
} else {
    # FIXME: it needs dummy dates to list all events
    $min_date = date('Y', 0);
    $max_date = new DateTime("01/01/30");
    $events = requests($room_id, $min_date, $max_date);
}

$t = new MyView("events.phtml");
$t->room = $room;
$t->room_id = $room_id;
$t->search = $search;
$t->events = $events;
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
