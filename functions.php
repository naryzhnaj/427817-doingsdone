<?php
    function get_name($user_id, $conn) {
        $sql = 'SELECT name FROM users WHERE id = ' . $user_id;
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $user = mysqli_fetch_assoc($result);
        }
        else {
            throw new Exception("Не удалось получить данные");
        }
        return $user;
    }

    function get_projects($user_id, $conn) {
        $sql = "SELECT id, title,
              (SELECT COUNT(*) from tasks WHERE task_status = '0' AND projects.id = tasks.project_id) AS task_amount
                FROM projects WHERE author_id = " . $user_id;
        
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            throw new Exception("Не удалось получить данные");
        }
        return $projects;
    }

    function get_tasks($user_id, $conn, $project) {
        $sql = 'SELECT tasks.title, project_id AS category, term AS date, task_status AS done, projects.title AS project_name
                FROM tasks INNER JOIN projects ON projects.id = tasks.project_id
                WHERE tasks.author_id = ' . $user_id;
        
        if ($project) {
          $sql .= ' AND tasks.project_id = ' . $project;
        }

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            throw new Exception("Не удалось получить данные");
        }
        return $tasks;
    }

    function insert_task($conn, $task, $user_id) {
        $sql = 'INSERT INTO tasks (title, term, task_file, project_id, author_id) VALUES (?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssii', $task['title'], $task['date'], $task['file'], $task['project_id'], $user_id);
        $res = mysqli_stmt_execute($stmt);
        return $res;
    }

    function insert_user($conn, $user) {
        $sql = 'INSERT INTO users (email, name, password, registration) VALUES (?, ?, ?, NOW())';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $user['email'], $user['name'], $user['password']);
        $res = mysqli_stmt_execute($stmt);   
        return $res;
    }
   
    function check_email($conn, $email) {    
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        return (mysqli_num_rows($res) > 0);
    }

    function include_template($name, $data) {
        $name = 'templates/' . $name;
        $result = '';

        if (!file_exists($name)) {
            return $result;
        }

        ob_start();
        extract($data);
        require_once $name;

        $result = ob_get_clean();

    return $result;
    }

    function set_task_class($current_task) {
        $task_status = '';
        if ( $current_task['done'] ) {
            $task_status = 'task--completed';
        }
        elseif ( $current_task['date'] && floor( (strtotime($current_task['date'] ) - time() ) / 3600) <= 24 ) {
            $task_status = 'task--important';
        }
        return $task_status;
    }
?>