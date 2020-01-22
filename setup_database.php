<?php

$config = require('htdocs/content/config.php');
$db_name = $config['db_name'];

$sql = file_get_contents($config['initial_db_file']);

$db = new mysqli($config['db_host'], $config['db_user'], $config['db_password']);
$db->set_charset("utf8");
try {
    # uncomment to clear the DB
    # $db->query("DROP DATABASE IF EXISTS $db_name");

    # Create the database
    $db->query("CREATE DATABASE $db_name");
    $db->select_db($db_name);

    if ($db->multi_query($sql)) {
        echo "successfully created database\n";
    } else {
       echo "error: $db->error\n";
    }
} finally {
    $db->close();
}
