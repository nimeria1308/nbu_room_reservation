<?php

$config = require('config.php');
$db = new mysqli($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
$db->set_charset("utf8");

?>