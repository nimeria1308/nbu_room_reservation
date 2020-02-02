<?php

$config = require('htdocs/content/config.php');
$db_name = $config['db_name'];

$sql = file_get_contents($config['initial_db_file']);

# re-create the db if clear requested
$clear_database = in_array('--clear', $argv);

# make sure all exceptions are thrown so that will bail out
mysqli_report(MYSQLI_REPORT_ALL);

$db = new mysqli($config['db_host'], $config['db_user'], $config['db_password']);
$db->set_charset("utf8");
try {
    if ($clear_database) {
        echo "clearing database\n";
        $db->query("DROP DATABASE IF EXISTS $db_name");
    }

    # Create the database
    $db->query("CREATE DATABASE IF NOT EXISTS $db_name");
    $db->select_db($db_name);
    $db->multi_query($sql);
    echo "successfully created database\n";
} finally {
    $db->close();
}
