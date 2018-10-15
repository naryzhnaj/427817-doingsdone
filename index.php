<?php
    $show_complete_tasks = rand(0, 1);
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    session_start();
    
    if (!isset($_SESSION['user'])) {
        $page_content = include_template('guest.php', []);
        $layout_content = include_template('layout.php', ['content' => $page_content,'title' => 'Дела в порядке']);
        print($layout_content);
        exit();
    }

    if (!$link) {
        $layout_content = include_template('error.php', ['error' => mysqli_connect_error()]);
    }
    else {
        try {
            $user_id = $_SESSION['user']['id'];
            $projects = get_projects($user_id, $link);
            
            $project_id = ( isset($_GET['id']) ) ? intval($_GET['id']) : 0;
            $day = ( isset($_GET['type']) ) ? htmlspecialchars($_GET['type']) : '';
            $tasks = get_tasks($user_id, $link, $project_id, $day);
            
            $id = ( isset($_GET['task_id']) ) ? htmlspecialchars($_GET['task_id']) : '';
            if ($id) {
                change_status($link, $id, htmlspecialchars($_GET['check']));
                header('Location: /');    
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
        if (empty($tasks)) { 
            header("HTTP/1.1 404 Not Found");
            header('Location: /404.html');
            exit();
        } 
        $page_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks,'tasks' => $tasks]);
        $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'title' => 'Дела в порядке']);
    }
    print($layout_content);
?>