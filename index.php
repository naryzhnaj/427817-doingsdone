<?php
    $show_complete_tasks = rand(0, 1);
    $projects = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
    $tasks = [['name' => "Собеседование в IT компании", 'date' => "01.12.2018",  'category' => "Работа", 'done' => false],
            ['name' => "Выполнить тестовое задание", 'date' => "25.12.2018",  'category' => "Работа", 'done' => false],
            ['name' => "Сделать задание первого раздела", 'date' => "21.12.2018",  'category' =>"Учеба", 'done' => true],
            ['name' => "Встреча с другом", 'date' => "22.12.2018",  'category' => "Входящие", 'done' => false],
            ['name' => "Купить корм для кота", 'date' => "Нет", 'category' => "Домашние дела", 'done' => false],
            ['name' => "Заказать пиццу", 'date' => "Нет", 'category' => "Домашние дела", 'done' => false]];
    $user_name = 'you';

    require_once('functions.php');
    $page_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks,'tasks' => $tasks, 'projects' => $projects]);
    $layout_content = include_template('layout.php', ['content' => $page_content, 'projects' => $projects, 'tasks' => $tasks, 'user_name' => $user_name,'title' => 'Дела в порядке']);
    print($layout_content);
?>