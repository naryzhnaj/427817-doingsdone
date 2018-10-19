<?php
    /**
    * поиск пользователя по email
    *
    * @param string $email искомый email
    * @param mysqli $conn подключение к серверу MySQL
    * @return array|null $res результат выполнения запроса
    */
    function get_user($email, $conn) {
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        return $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
    }

    /**
    * поиск проектов данного пользователя
    *
    * @param int $user_id id пользователя
    * @param mysqli $conn подключение к серверу MySQL
    * @throws Exception если запрос не выполнился
    * @return array $res список проектов
    */
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

    /**
    * изменить статус задачи
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param int $task_id id задачи
    * @param string $status новый статус
    * @return boolean $res результат выполнения запроса
    */
    function change_status($conn, $task_id, $status) {
        $sql = "UPDATE tasks SET task_status = '$status' WHERE id = '$task_id'";
        $res = mysqli_query($conn, $sql);
        return $res;
    }

    /**
    * получить список задач для данного пользователя в разрезе проекта или периода
    *
    * @param int $user_id id пользователя
    * @param mysqli $conn подключение к серверу MySQL
    * @param int $project id проекта
    * @param string $period значение фильтра задач по дням
    * @throws Exception если запрос не выполнился
    * @return array $tasks список задач
    */
    function get_tasks($user_id, $conn, $project, $period) {
        $sql = "SELECT id, title, project_id AS category, DATE_FORMAT(term, '%d.%m.%Y') AS date, 
                    task_status AS done, task_file FROM tasks WHERE author_id = " . $user_id;
        
        if ($project) {
          $sql .= " AND project_id = " . $project;
        }

        $periods = ['today' => '= CURDATE()', 'next' => '= DATE_SUB(CURDATE(), INTERVAL -1 DAY)', 'late' => "> '1970-01-01' AND term < CURDATE()"];
        if ($period) {
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

    /**
    * добавление в БД новой задачи
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param array $task атрибуты задачи
    * @param int $user_id id пользователя
    * @return boolean $res результат выполнения запроса
    */
    function insert_task($conn, $task, $user_id) {
        $sql = 'INSERT INTO tasks (title, term, task_file, project_id, author_id) VALUES (?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssii', $task['title'], $task['date'], $task['file'], $task['project_id'], $user_id);
        $res = mysqli_stmt_execute($stmt);
        return $res;
    }

    /**
    * добавление в БД нового пользователя
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param array $user массив с атрибутами
    * @return boolean $res результат выполнения запроса
    */
    function insert_user($conn, $user) {
        $sql = 'INSERT INTO users (email, name, password) VALUES (?, ?, ?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $user['email'], $user['name'], $user['password']);
        $res = mysqli_stmt_execute($stmt);   
        return $res;
    }

    /**
    * добавление в БД нового проекта
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param string $name название проекта
    * @param int $user_id id пользователя
    * @return boolean $res результат выполнения запроса
    */
    function insert_project($conn, $name, $user_id) {
        $sql = 'INSERT INTO projects (title, author_id) VALUES (?, ?)';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $name, $user_id);
        $res = mysqli_stmt_execute($stmt);
        return $res;
    }

    /**
    * проверка нового проекта на уникальность
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param string $name название проекта
    * @param int $user_id id пользователя
    * @return boolean есть уже у пользователя проект с таким названием
    */
    function check_project($conn, $name, $user_id) {
        $sql = "SELECT id FROM projects WHERE title = '$name' AND author_id = '$user_id'";
        $res = mysqli_query($conn, $sql);
        return (mysqli_num_rows($res) > 0);
    }

    /**
    * проверка email нового пользователя на уникальность
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param string $email пользовательский email
    * @return boolean есть ли уже в базе такой адрес
    */
    function check_email($conn, $email) {    
        $email = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        return (mysqli_num_rows($res) > 0);
    }

    /**
    * проверка принадлежности проекта данному пользователю
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param int $id id проекта
    * @param int $user id пользователя
    * @return boolean совпадают ли значения полей id пользователя
    */
    function check_author($conn, $id, $user) {
        $sql = 'SELECT author_id FROM projects WHERE id = ' . $id;
        $res = mysqli_query($conn, $sql);
        return (mysqli_fetch_array($res, MYSQLI_ASSOC)['author_id'] === $user);
    }

    /**
    * шаблонизатор
    *
    * @param string $name имя файла-шаблона
    * @param array $data массив с атрибутами страницы
    * @return string $result контент страницы
    */
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

    /**
    * установка класса строке таблицы в зависимости от срока и статуса
    *
    * @param array $current_task массив с атрибутами задачи
    * @return string $task_status имя класса
    */
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

    /**
    * сравнение параметров ссылок в блоке фильтров
    *
    * @param string $item значение параметра ссылки
    * @return boolean сравнение переданного методу параметра и соответствующего параметра из GET
    */
    function set_item_class($item) {
        return ( (!isset($_GET['type']) && $item) || (isset($_GET['type']) && ($_GET['type'] !== $item) ) ) ? '' : 'tasks-switch__item--active';
    }

    /**
    * поиск задач по названию
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @param string $name искомое название
    * @param int $user id пользователя
    * @return array|null $res результат выполнения запроса
    */
    function search_task($conn, $name, $user) {
        $name .= '*';
        $name = mysqli_real_escape_string($conn, $name);
        $sql = "SELECT id, title, project_id AS category, DATE_FORMAT(term, '%d.%m.%Y') AS date, 
                    task_status AS done, task_file FROM tasks 
                    WHERE author_id = '$user' AND MATCH(title) AGAINST('$name' IN BOOLEAN MODE)";
        $res = mysqli_query($conn, $sql);
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : null;
    }

    /**
    * поиск задач для рассылки. Требуется найти невыполненные задачи, у которых срок выполнения через час
    *
    * @param mysqli $conn подключение к серверу MySQL
    * @return array|null $res результат выполнения запроса
    */
    function get_tasks_for_mail($conn) {
        $sql = "SELECT u.email AS email, u.name AS name, title, DATE_FORMAT(term, '%d.%m.%Y %H:%i:%s') AS time
                FROM tasks JOIN users u ON tasks.author_id = u.id
                WHERE task_status = '0' AND 
                term > NOW() AND term <= ADDDATE(NOW(), INTERVAL 1 HOUR) 
                ORDER BY author_id";
        $res = mysqli_query($conn, $sql);
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : null;
    }
?>
