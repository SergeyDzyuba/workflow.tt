<?php

$dictionary['AOW_WorkFlow']['fields']['subprocess_sequence_number'] = array(
    'name' => 'subprocess_sequence_number',
    'vname' => 'LBL_SUBPROCESS_SEQUENCE_NUMBER',
    'type' => 'enum',
    'options' => 'subprocess_sequence_number_list',
    'required' => false,
);

$dictionary['AOW_WorkFlow']['fields']['process_id'] = array(
    'required' => false,
    'name' => 'process_id',
    'vname' => '',
    'type' => 'id',
    'massupdate' => 0,
    'importable' => 'true',
    'audited' => 0,
    'len' => 36,
);

$dictionary['AOW_WorkFlow']['fields']['process_name'] = array(
    'required' => false,
    'source' => 'non-db',
    'name' => 'process_name',
    'vname' => 'LBL_PROCESS_NAME',
    'type' => 'relate',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'audited' => 1,
    'len' => '100',
    'id_name' => 'process_id',
    'ext2' => 'AOW_WorkFlow', 
    'module' => 'AOW_WorkFlow',
    'rname' => 'name',
    'studio' => 'visible',
);

$dictionary["AOW_WorkFlow"]["fields"]["processes"] = 
  array (
    'name' => 'processes',
    'type' => 'link',
    'relationship' => 'process_processes',
    'module'=>'AOW_WorkFlow',
    'bean_name'=>'AOW_WorkFlow',
    'source'=>'non-db',
    'vname'=>'LBL_PROCESSES',
  );
 
 
$dictionary['AOW_WorkFlow']['relationships']['process_processes'] =
  array (
  	'lhs_module'=> 'AOW_WorkFlow', 
  	'lhs_table'=> 'aow_workflow', 
  	'lhs_key' => 'id',
  	'rhs_module'=> 'AOW_WorkFlow', 
  	'rhs_table'=> 'aow_workflow', 
  	'rhs_key' => 'process_id',
  	'relationship_type'=>'one-to-many'
  );