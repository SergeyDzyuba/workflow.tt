<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['process_record'] = Array();

$hook_array['process_record'][] = Array(
    1,
    'Change status text and color',
    'custom/modules/AOW_Processed/Hooks.php',
    'ChangeStatusText',
    'changeStatus'
);
