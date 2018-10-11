<?php                
    $link = mysqli_connect('localhost', 'root', '', 'doingsdone');
    mysqli_set_charset($link, 'utf8');
    require_once('functions.php');
    
    if (!$link) {
        $page_content = include_template('error.php', ['error' => mysqli_connect_error(), 'title' => 'Регистрация']);
    } else {
        $page_content = include_template('reg.php', ['errors' => [], 'title' => 'Регистрация']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];

            if (empty($_POST['email'])) {
                $errors['email'] = 'извините, это поле нужно заполнить';
            } else {
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                if (!$email) {
                    $errors['email'] = 'извините, адрес некорректен';
                } else {
                    $email = mysqli_real_escape_string($link, $email);
                    $sql = "SELECT id FROM users WHERE email = '$email'";
                    $res = mysqli_query($link, $sql);
                    if (mysqli_num_rows($res) > 0) {
                        $errors['email'] = 'извините, пользователь с этим email уже зарегистрирован';
                    }
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
                $sql = 'INSERT INTO users (email, name, password, registration) VALUES (?, ?, ?, NOW())';
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, 'sss', $email, $user['name'], $user['password']);
                $res = mysqli_stmt_execute($stmt);   
            
                if ($res) {
                    header('Location: /index.php');
                    exit();
                }
                else {
                    $page_content = include_template('error.php', ['error' => 'неудалось выполнить запрос', 'title' => 'Регистрация']);
                }
            }
            $page_content = include_template('reg.php', ['errors' => $errors, 'title' => 'Регистрация']);
        }       
    }
    print($page_content);
?>