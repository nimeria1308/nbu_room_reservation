<?php
require_once('libraries/myview.php');

# event repeat view
$t = new MyView('event_repeat.phtml');
$t->repeat_start = $_GET['repeat_start'];
$t->min_date = $_GET['min_date'];
$t->render();
