<?php
require_once('libraries/myview.php');

$t = new MyView();
$t->title = "За нас";
$t->render('header.phtml');
?>

ABOUT US CONTENTS

<?php
$t = new MyView();
$t->render('footer.phtml');
?>
