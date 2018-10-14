<?php
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    
    if (!$link) {
        $page_content = include_template('error.php', ['error' => mysqli_connect_error(), 'title' => 'Вход на сайт']);
        print($page_content);
        exit();
    }
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['email'])) {
            $errors['email'] = 'извините, это поле нужно заполнить';
        } else {
            $user = get_user(htmlspecialchars($_POST['email']), $link);

            if (!$user) {
                $errors['email'] = 'извините, адрес не найден';
            } elseif (!password_verify( htmlspecialchars($_POST['password']), $user['password'])) {
                    $errors['password'] = 'Неверный пароль';
            }
        }

        if (!$errors) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: /index.php');
            exit();
        }
    }
    $page_content = include_template('auth.php', ['errors' => $errors]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'Вход на сайт']);
    print($layout_content);
?>