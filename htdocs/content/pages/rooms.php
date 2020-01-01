<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');

$t = new MyView('header.phtml');
$t->title = "Резервиране на зали към НБУ Библиотека";

# Add extra stuff in head
$t->head_elements = array(
    '<link rel="stylesheet" type="text/css" href="/resources/css/rooms.css" />',
    '<script type="text/javascript" src="/resources/javascipt/css_grid_annotator.js"></script>',
);
$t->render();

# TODO: Read the rooms from the backend
$rooms = array(
    array(
        "room_id" => 0,
        "title" => "Семинарна зала",
        "img_url" => "seminars-hall.jpg",
    ),
    array(
        "room_id" => 1,
        "title" => "Зала за колективна работа",
        "img_url" => "collective-work-hall.jpg",
    ),
);

if (is_admin()) {
    $rooms[] = array(
        "room_id" => "new",
        "title" => "Добави нова зала",
        "img_url" => "plus-light.png",
        "img_class" => "shadowed",
    );
}

$t = new MyView("rooms.phtml");
$t->rooms = $rooms;
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
