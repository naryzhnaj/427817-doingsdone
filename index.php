<?php
    require_once('functions.php');  
    require_once('init.php');
    session_start();
    
    if (!isset($_SESSION['user'])) {
        $page_content = include_template('guest.php', []);
        $layout_content = include_template('layout.php', ['content' => $page_content,'title' => 'Дела в порядке']);
        print($layout_content);
        exit();
    }
    
    try {
        $user_id = $_SESSION['user']['id'];
        $projects = get_projects($user_id, $link);
        $project_id = ( isset($_GET['id']) ) ? intval($_GET['id']) : 0;
        
        if ($project_id && !check_author($link, $project_id, $user_id)) { 
            header("HTTP/1.1 404 Not Found");
            header('Location: /404.html');
            exit();
        }
        $day = ( isset($_GET['type']) ) ? htmlspecialchars($_GET['type']) : '';
        $tasks = get_tasks($user_id, $link, $project_id, $day);
        
        $id = ( isset($_GET['task_id']) ) ? htmlspecialchars($_GET['task_id']) : '';
        if ($id) {
            change_status($link, $id, htmlspecialchars($_GET['check']));
            header('Location: /');
        }

        if (isset($_GET['show_completed'])) {
            $_SESSION['show_complete_tasks'] = $_GET['show_completed'];
        } elseif (!isset($_SESSION['show_complete_tasks'])) {
            $_SESSION['show_complete_tasks'] = 0;
        }
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }
    
    $page_content = include_template('index.php', ['tasks' => $tasks]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'title' => 'Дела в порядке']);
    print($layout_content);
?>