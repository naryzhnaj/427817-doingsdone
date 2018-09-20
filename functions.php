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
?>