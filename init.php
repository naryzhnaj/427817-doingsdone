<?php
    require_once('functions.php');                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
        
    if (!$link) {
        $layout_content = include_template('error.php', ['error' => mysqli_connect_error()]);
        print($layout_content);
        exit();
    }
?>