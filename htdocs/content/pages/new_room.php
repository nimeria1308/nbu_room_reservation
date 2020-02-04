<?php
require_once('libraries/myview.php');

# New room view
$t = new MyView('new_room.phtml');
$t->render();
