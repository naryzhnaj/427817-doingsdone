<?php                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    session_start();
        
    if (!$link) {
        $layout_content = include_template('error.php', ['error' => mysqli_connect_error()]);
        print($layout_content);
        exit();
    }

    try {
        $user = $_SESSION['user']['id'];
        $projects = get_projects($user, $link);
    }
    catch (Exception $ex) {
        echo $ex->getMessage();
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (empty($_POST['name'])) {
            $errors['name'] = 'извините, название нужно заполнить';
        } elseif (check_project($link, htmlspecialchars($_POST['name']), $user)) {
            $errors['name'] = 'извините, такое имя уже есть в базе';    
        } else {
            $project = htmlspecialchars($_POST['name']);        
        }         
            
        if (!$errors) {
            if (insert_project($link, $project, $user)) {
                header('Location: /index.php');
                exit();
            }

            $layout_content = include_template('error.php', ['error' => 'при выполнении запроса произошла ошибка']);
            print($layout_content);
            exit();
        }
    }
    $page_content = include_template('add_project.php', ['projects' => $projects, 'errors' => $errors]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'title' => 'Добавить проект']);
    print($layout_content);
?>