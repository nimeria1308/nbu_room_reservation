<?php
require_once('libraries/isadmin.php');

// go back to home if not admin
if (!is_admin()) {
    header("Location: /");
    exit();
}
