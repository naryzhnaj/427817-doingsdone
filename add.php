<?php                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    
    if (!$link) {
        $error = mysqli_connect_error();
        $layout_content = include_template('error.php', ['error' => $error]);
    } else {
        $user = 2;
        try {
            $user_name = get_name($user, $link);
            $projects = get_projects($user, $link);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $page_content = include_template('add_task.php', ['projects' => $projects]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $sql = 'INSERT INTO tasks (title, term, task_file, project_id, author_id) VALUES (?, ?, ?, ?, ?)';
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = 'извините, название нужно заполнить';
            } else {
                $task['title'] = htmlspecialchars($_POST['name']);        
            }

            $task['project_id'] = htmlspecialchars($_POST['project']);
            
            if (isset($_POST['date'])) {
                $task['date'] = date('Y-m-d', strtotime( htmlspecialchars($_POST['date'])));
                if (!$task['date']) {
					$errors['date'] = 'извините, некорректная дата';
				}
            } else {
                $task['date'] = NULL;        
            }         

            if (isset($_FILES['preview']['name'])) {
                move_uploaded_file($_FILES['preview']['tmp_name'], $_FILES['preview']['name']);
                $task['file'] = $_FILES['preview']['name'];
            } else {
                $task['file'] = NULL;        
            }

            $page_content = include_template('add_task.php', ['projects' => $projects, 'errors' => $errors]);
            
            if (!$errors) {          
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, 'sssii', $task['title'], $task['date'], $task['file'], $task['project_id'], $user);
                $res = mysqli_stmt_execute($stmt);   
            
                if ($res) {
                    header('Location: /index.php');
                }
                else {
                    $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
                }
            }
        }        
        $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'user_name' => $user_name['name'],'title' => 'Добавить задачу']);
    }
    print($layout_content);
?>