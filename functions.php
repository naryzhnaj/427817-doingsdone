<?php
    function group_tasks($project_name, $task_list) {
        $sum = 0;
        foreach( $task_list as $task ) {    
            if ( $task['category'] == $project_name ) {$sum++;}
        }
        return $sum;
    }

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

    function set_task_class($current_task) {
        if ( $current_task['done'] ) {
            return 'task--completed';
        }
        if ( $current_task['date'] != 'Нет' && floor( (strtotime($current_task['date'] ) - time() ) / 3600) <= 24 ) {
            return 'task--important';
        }
        return '';
    }
?>