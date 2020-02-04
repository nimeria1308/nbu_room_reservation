<?php
require_once('libraries/isadmin.php');
require_once('model/room.php');

// go back to home if not admin
if (!is_admin()) {
    header("Location: /");
    return;
}

// TODO: Create room from backend and return new room ID using
// the following stuff from $_POST:
// name
// image_path
// color
$room_id = "";

header("Location: /rooms/$room_id");
