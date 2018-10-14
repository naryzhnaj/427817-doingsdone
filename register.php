<?php                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    
    if (!$link) {
        $page_content = include_template('error.php', ['error' => mysqli_connect_error(), 'title' => 'Регистрация']);
        print($page_content);
        exit();
    }
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (empty($_POST['email'])) {
            $errors['email'] = 'извините, это поле нужно заполнить';
        } else {
            $user['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            if (!$user['email']) {
                $errors['email'] = 'извините, адрес некорректен';
            } elseif (check_email($link, $user['email'])) {
                $errors['email'] = 'извините, пользователь с этим email уже зарегистрирован';
            }    
        } 
        
        if (empty($_POST['name'])) {
            $errors['name'] = 'извините, это поле нужно заполнить';
        } else {
            $user['name'] = htmlspecialchars($_POST['name']);
        }
            
        if (empty($_POST['password'])) {
            $errors['password'] = 'извините, это поле нужно заполнить';
        } else {
            $user['password'] = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
        }
           
        if (!$errors) {
            if (insert_user($link, $user)) {
                header('Location: /index.php');
                exit();
            }
            $page_content = include_template('error.php', ['error' => 'неудалось выполнить запрос', 'title' => 'Регистрация']);
            print($page_content);
            exit();
        }    
    }
    $page_content = include_template('reg.php', ['errors' => $errors]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'Регистрация']);
    print($layout_content);
?>