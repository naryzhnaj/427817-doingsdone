INSERT INTO users (email, name, password) VALUES
    ("masha@mail.ru", "masha", "1234"),
    ("dasha@mail.ru", "dasha", "0123");

INSERT INTO projects (title, author_id) VALUES
    ("Входящие", 1), ("Учеба", 1), ("Работа", 2), ("Домашние дела", 2), ("Авто", 2);

INSERT INTO tasks (title, author_id, project_id, term, task_status) VALUES 
    ("Собеседование в IT компании", 1, 3, "2018-09-28", "0"),
    ("Выполнить тестовое задание", 1, 3, "2018-09-30", "0"),
    ("Сделать задание первого раздела", 1, 3, "2018-09-29", "0"),
    ("Встреча с другом", 2, 3, "2018-09-29", "0"),
    ("Купить корм для кота", 2, 4, null, "0"),
    ("Заказать пиццу", 2, 4, "2018-09-28", "0");

/*    получить список из всех проектов для одного пользователя;*/
SELECT title FROM projects WHERE author_id = 2;

 /*  получить список из всех задач для одного проекта;*/
SELECT title FROM tasks WHERE project_id = 4;

/*пометить задачу как выполненную;*/
UPDATE tasks SET task_status = "1" WHERE id = 5;

/*получить все задачи для завтрашнего дня;*/
SELECT title FROM tasks WHERE term = ADDDATE( CURDATE(), INTERVAL 1 DAY );

/*обновить название задачи по её идентификатору.*/
UPDATE tasks SET title = "Сходить в магазин" WHERE id = 4;