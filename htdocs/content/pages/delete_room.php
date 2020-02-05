<?php
require_once('libraries/forceadmin.php');
require_once('model/room.php');

$room_id = $_GET['room_id'];

delete_room($room_id);

header("Location: /");
