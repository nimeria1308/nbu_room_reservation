<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');
require_once('model/room.php');

$t = new MyView('header.phtml');
$t->title = "Резервиране на зали към НБУ Библиотека";

# Add extra stuff in head
$t->stylesheets = [
    'rooms.css',
];

$t->scripts = [
    'css_grid_annotator.js',
];

$t->render();

$rooms = get_rooms();

if (is_admin()) {
    $rooms['new'] = [
        "title" => "Добави нова зала",
        "img_url" => "plus-light.png",
        "img_class" => "shadowed",
    ];
}

$t = new MyView("rooms.phtml");
$t->rooms = $rooms;
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
