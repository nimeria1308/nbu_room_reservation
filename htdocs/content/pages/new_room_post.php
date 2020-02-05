<?php
require_once('libraries/forceadmin.php');
require_once('model/room.php');

// TODO: Create room from backend and return new room ID using
// the following stuff from $_POST:
// name
// img_url
// color
$room_id = "";

header("Location: /rooms/$room_id");
