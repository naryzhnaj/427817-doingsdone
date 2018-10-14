<?php                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    
    if (!$link) {
        $layout_content = include_template('error.php', ['error' => mysqli_connect_error()]);
        print($layout_content);
        exit();
    }

    try {
        $projects = get_projects($_SESSION['user']['id'], $link);
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
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
                    
        if (!$errors) {              
            if (insert_task($link, $task, $user)) {
                header('Location: /index.php');
                exit();
            }

            $layout_content = include_template('error.php', ['error' => 'при выполнении запроса произошла ошибка']);
            print($layout_content);
            exit();
        }
    }
    $page_content = include_template('add_task.php', ['projects' => $projects, 'errors' => $errors]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'title' => 'Добавить задачу']);
    print($layout_content);
?>