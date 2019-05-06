<?php
    require_once('functions.php');                
    $link = new mysqli('localhost', 'root', '', 'doingsdone');
    $link->set_charset('utf8');
        
    if ($link->connect_errno) {
        $layout_content = include_template('error.php', [error => $link->connect_error]);
        print($layout_content);
        exit();
    }
?>