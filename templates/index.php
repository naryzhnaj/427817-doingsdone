<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/?type=today" class="tasks-switch__item">Повестка дня</a>
        <a href="/?type=next" class="tasks-switch__item">Завтра</a>
        <a href="/?type=late" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed"
            <?php if ($show_complete_tasks): ?>checked<?php endif; ?>
             type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $task): ?>
        <?php if ( $show_complete_tasks || !$task['done'] ): ?> 
            <tr class="tasks__item task <?= set_task_class($task); ?>">
                <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden" type="checkbox"
                    <?php if ( $task['done'] ): ?>checked<?php endif; ?>
                    value=<?= $task['id']; ?>>
                    <span class="checkbox__text"><?= htmlspecialchars($task['title']); ?></span>
                </label>
                </td>
                <td class="task__date"><?= htmlspecialchars($task['date']); ?></td>
                <td class="task__controls">
                    <?= htmlspecialchars( $task['project_name'] ); ?>
                </td>
            </tr>  
        <?php endif; ?>
    <?php endforeach; ?>
</table>