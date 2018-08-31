<?php
class ChangeStatusText{
    function changeStatus(SugarBean $bean, $event, $arguments){
        global $app_list_strings;
        $processed = new AOW_Processed();
        $processed->retrieve($bean->id);
        if (!empty($processed->condition_number) && $processed->condition_number!==0 && $processed->condition_number!=='NULL' && $processed->status === 'Uncomplete'){
            $bean->status = '<div style="color: #ff0000">'.$app_list_strings['aow_process_status_list'][$bean->status].', не выполнено условие '.$processed->condition_number.'</div>';
        }
    }
}