<?php
require_once('libraries/forceadmin.php');
require_once('libraries/myview.php');
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

# default values for sorting
$sortby = array_key_exists('sortby', $_GET) ? trim($_GET['sortby']) : "id";
$order = array_key_exists('order', $_GET) ? trim($_GET['order']) : "asc";

if ($search) {
    $events = search($search);
}else{
    # FIXME: it needs dummy dates to list all events
    $min_date = date('Y', 0);
    $max_date = new DateTime("01/01/30");
    $events = requests($room_id, $min_date, $max_date, $sortby, $order);
}

function get_sort_link($id) {
    global $sortby;
    global $order;

    $neworder = "asc";
    if ($id == $sortby) {
        // toggle the order for this one
        $neworder = ($order == 'asc' ? 'desc' : 'asc');
    }
    return "?sortby=$id&order=$neworder";
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
