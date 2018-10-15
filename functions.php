<?php
    function get_user($email, $conn) {
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        return $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
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

    function change_status($conn, $task_id, $status) {
        $sql = "UPDATE tasks SET task_status = '$status' WHERE id = '$task_id'";
        $res = mysqli_query($conn, $sql);
        return $res;
    }

    function get_tasks($user_id, $conn, $project, $period) {
        $sql = "SELECT id, title, project_id AS category, DATE_FORMAT(term, '%d.%m.%Y') AS date, 
                    task_status AS done, task_file FROM tasks WHERE author_id = " . $user_id;
        
        if ($project) {
          $sql .= " AND project_id = " . $project;
        }

        $periods = ['today' => '= CURDATE()', 'next' => '= DATE_SUB(CURDATE(), INTERVAL -1 DAY)', 'late' => '< CURDATE()'];
        if (($period) && ($period !== 'all')) {
            $sql .= " AND term " . $periods[$period];
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

    function insert_project($conn, $name, $user_id) {
        $sql = 'INSERT INTO projects (title, author_id) VALUES (?, ?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $name, $user_id);
        $res = mysqli_stmt_execute($stmt);
        return $res;
    }

    function check_project($conn, $name, $user_id) {
        $sql = "SELECT id FROM projects WHERE title = '$name' AND author_id = '$user_id'";
        $res = mysqli_query($conn, $sql);
        return (mysqli_num_rows($res) > 0);
    }

    function check_email($conn, $email) {    
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        return (mysqli_num_rows($res) > 0);
    }

    function check_author($conn, $id, $user) {
        $sql = 'SELECT author_id FROM projects WHERE id = ' . $id;
        $res = mysqli_query($conn, $sql);
        return (mysqli_fetch_array($res, MYSQLI_ASSOC)['author_id'] == $user);
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
        elseif ( (strtotime($current_task['date']) > 0) && floor( (strtotime($current_task['date'] ) - time() ) / 3600) <= 24 ) {
            $task_status = 'task--important';
        }
        return $task_status;
    }

    function set_item_class($item) {
        return ((!$_GET['type'] && !$item) || ($_GET['type'] === $item)) ? 'tasks-switch__item--active' : '';
    }
?>