<?php
require_once('libraries/myview.php');
require_once('libraries/isadmin.php');

$t = new MyView('header.phtml');
$t->title = "Заявки";

$t->render();

$t = new MyView("events.phtml");
$t->render();

$t = new MyView('footer.phtml');
$t->render();
?>
