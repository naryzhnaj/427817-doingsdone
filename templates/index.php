<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item <?=set_item_class('');?>">Все задачи</a>
        <a href="/?type=today" class="tasks-switch__item <?=set_item_class('today');?>">Повестка дня</a>
        <a href="/?type=next" class="tasks-switch__item <?=set_item_class('next');?>">Завтра</a>
        <a href="/?type=late" class="tasks-switch__item <?=set_item_class('late');?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed"
            <?php if ($_SESSION['show_complete_tasks']): ?>checked<?php endif; ?>
             type="checkbox">
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>
<table class="tasks">
    <?php foreach ($tasks as $task): ?>
        <?php if ( $_SESSION['show_complete_tasks'] || !$task['done'] ): ?> 
            <tr class="tasks__item task <?= set_task_class($task); ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input task__checkbox visually-hidden" type="checkbox"
                        <?php if ( $task['done'] ): ?>checked<?php endif; ?>
                        value=<?= $task['id']; ?>>
                        <span class="checkbox__text"><?= htmlspecialchars($task['title']); ?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?php if ($task['task_file']): ?>                
                        <a class='download-link' href="/<?=htmlspecialchars($task['task_file']);?>">
                            <?=htmlspecialchars($task['task_file']);?>
                        </a>
                    <?php endif;?>
                </td>
                <td class="task__date"><?php if (strtotime($task['date']) > 0) print(htmlspecialchars($task['date']));?></td>
            </tr>  
        <?php endif; ?>
    <?php endforeach; ?>
</table>