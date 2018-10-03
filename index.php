<?php
    $show_complete_tasks = rand(0, 1);
    
	$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    if (!$link) {
        $error = mysqli_connect_error();
        $layout_content = include_template('error.php', ['error' => $error]);
    }
    else {
        $user = 3;
        try {
            $tasks = get_tasks($user, $link);
            $projects = get_projects($user, $link);
            $user_name = get_name($user, $link);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
        $page_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks,'tasks' => $tasks]);
        $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'user_name' => $user_name['name'],'title' => 'Дела в порядке']);
    }
    print($layout_content);
?>