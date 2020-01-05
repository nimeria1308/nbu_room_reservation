<?php
function is_admin() {
    return array_key_exists('is_admin', $_COOKIE) && $_COOKIE['is_admin'] == 'true';
}
?>
