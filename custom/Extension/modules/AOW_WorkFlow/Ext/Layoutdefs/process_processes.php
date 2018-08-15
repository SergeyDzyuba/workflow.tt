<?php
$layout_defs["AOW_WorkFlow"]["subpanel_setup"]["process_processes"] = array (
  'order' => 2,
  'module' => 'AOW_WorkFlow',
  'subpanel_name' => 'subprocesses',
  'sort_order' => 'desc',
  'sort_by' => 'subprocess_sequence_number',
  'title_key' => 'LBL_SUBPANEL_PROCESS_PROCESSES_TITLE',
  'get_subpanel_data' => 'processes',
  'top_buttons' => 
  array (
    0 => 
    array (
      	'widget_class' => 'SubPanelTopCreateSubprocessButton',
    ),
/*    1 => 
    array (
		'widget_class' => 'SubPanelTopSelectButton',
		'mode' => 'MultiSelect',
    ),*/
  ),
);
?>