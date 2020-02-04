<?php
require_once('libraries/isadmin.php');
require_once('model/room.php');

// go back to home if not admin
if (!is_admin()) {
    header("Location: /");
    return;
}

$room_id = $_GET['room_id'];

// TODO: edit room from backend
// the following stuff from $_POST:
// name
// img_url
// color

header("Location: /rooms/$room_id");
