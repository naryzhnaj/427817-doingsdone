<?php
    $show_complete_tasks = rand(0, 1);
    
    // новый пользователь для примера
    $user_id = 3;
	$link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        $error = mysqli_connect_error();
        print("Не удалось подключиться к MySQL");
    }
    else {
        $sql = 'SELECT name FROM users WHERE id = ' . $user_id;
        $result = mysqli_query($link, $sql);
        if ($result) {
            $user = mysqli_fetch_assoc($result);
        }
        else {
            $error = mysqli_error($link);
            print("Не удалось получить данные");
        }

        $sql = 'SELECT id, title FROM projects WHERE author_id = ' . $user_id;
        $result = mysqli_query($link, $sql);
        if ($result) {
            $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            $error = mysqli_error($link);
            print("Не удалось получить данные");
        }
        
        $sql = 'SELECT tasks.title, project_id AS category, term AS date, task_status AS done, projects.title AS project_name
                        FROM tasks INNER JOIN projects ON projects.id = tasks.project_id
                        WHERE tasks.author_id = ' . $user_id;

        $result = mysqli_query($link, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            $error = mysqli_error($link);
            print("Не удалось получить данные");
        }
    }
    require_once('functions.php');
    $page_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks,'tasks' => $tasks]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'tasks' => $tasks, 'user_name' => $user['name'],'title' => 'Дела в порядке']);
    print($layout_content);
?>