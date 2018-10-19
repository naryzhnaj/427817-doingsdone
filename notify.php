<?php                
    require_once('functions.php');
    require_once('init.php');
    require_once('vendor/autoload.php');

    $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");
    $mailer = new Swift_Mailer($transport);
    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
 
    $tasks = get_tasks_for_mail($link);
    if (!$tasks) {
        print("нет данных для рассылки");
        exit();
    }

    $users = [];
    foreach ($tasks as $task) {
        $users[$task['email']][] = ['title' => $task['title'], 'time' => $task['time'], 'name' => $task['name']];
    }

    foreach ($users as $email => $user_tasks) {
        $message = new Swift_Message();
        $message->setSubject("Уведомление от сервиса «Дела в порядке»");
        $message->setFrom(['keks@phpdemo.ru' => 'Дела в порядке']);
        $message->setBcc([$email => $user_tasks[0]['name']]);
    
        $msg_content = include_template('hour_email.php', ['user_name' => $user_tasks[0]['name'], 'tasks' => $user_tasks]);
        $message->setBody($msg_content, 'text/html');
        $result = $mailer->send($message);
    }
?>
