<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h3>Уважаемый, <?=htmlspecialchars($user_name);?>!</h3>
    <?php foreach ($tasks as $task): ?>
        <p>У вас запланирована задача <?=htmlspecialchars($task['title']);?> на <?=htmlspecialchars($task['time']);?>.</p>
    <?php endforeach; ?>
</body>
</html>
