<?php
require_once('libraries/forceadmin.php');
require_once('model/room.php');

$room_id = $_GET['room_id'];

// TODO: edit room from backend
// the following stuff from $_POST:
// name
// img_url
// color

header("Location: /rooms/$room_id");
