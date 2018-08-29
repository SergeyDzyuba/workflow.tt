<?php
$parent_process_id = $_REQUEST['process_id'];
$html = getCustomSelectOptions($parent_process_id);
echo $html; //ответ в аякс

function getCustomSelectOptions($workflow_id)
{
    global $app_list_strings, $db;
    if (!empty($workflow_id)) {
        $modules = $app_list_strings['moduleList'];
        $process_hierarchy = array();
//        $query1 = 'SELECT id,name,flow_module FROM aow_workflow
//                  WHERE status = \'Active\'
//                        AND deleted = 0
//                        AND process_id=\'' . $workflow_id . '\';';
//        $row1 = $db->fetchByAssoc($db->query($query1));
//        if (isset($row1['process_id']) && !empty($row1['process_id'])) {
//            $process_hierarchy[] = array(
//                'id' => $workflow_id,
//                'flow_module' => $row1['flow_module'],
//                'name' => $row1['name'],
//                'selected' => '',
//            );
//        }
        while ($response = getParentWorkflowId($workflow_id)) {
            $process_hierarchy[] = array(
                'id' => $workflow_id,
                'flow_module' => $response['flow_module'],
                'name' => $response['name'],
                'selected' => '',
            );
            $workflow_id = $response['process_id'];
        }
//        $process_hierarchy = array_reverse($process_hierarchy);
        $html = '<option value="default" selected>Текущий модуль</option>';
        foreach ($process_hierarchy as $item) {
            $html .= '<option value="' . $item['id'] . '"><b>' . $item['name'] . '</b>: ' . $modules[$item['flow_module']] . '</option>';
        }
        return $html;
    }
    else {
        return '<option value="default">Текущий модуль</option>';
    }
}

function getParentWorkflowId($workflow_id)
{
    global $db;

    $query = 'SELECT name,flow_module,process_id FROM aow_workflow
                  WHERE status = \'Active\' 
                        AND deleted = 0
                        AND id=\'' . $workflow_id . '\';';
    $row = $db->fetchByAssoc($db->query($query));
    if (isset($row['name']) && !empty($row['name'])){
        return $row;
    }
    else {
        return false;
    }
}