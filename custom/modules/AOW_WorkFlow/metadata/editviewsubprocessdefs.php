<?php

$viewdefs ['AOW_WorkFlow'] =
    array (
        'EditView' =>
        array (
            'templateMeta' =>
            array (
                'maxColumns' => '2',
                'widths' =>
                array (
                    0 =>
                    array (
                        'label' => '10',
                        'field' => '30',
                    ),
                    1 =>
                    array (
                        'label' => '10',
                        'field' => '30',
                    ),
                ),
                'useTabs' => false,
                'tabDefs' =>
                array (
                    'DEFAULT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'CONDITIONS' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'ACTIONS' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
                'syncDetailEditViews' => false,
            ),
            'panels' =>
            array (
                'default' =>
                array (
                    0 =>
                    array (
                        0 => 'name',
                        1 => 'assigned_user_name',
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'flow_module',
                            'studio' => 'visible',
                            'label' => 'LBL_FLOW_MODULE',
                        ),
                        1 =>
                        array (
                            'name' => 'status',
                            'studio' => 'visible',
                            'label' => 'LBL_STATUS',
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'run_when',
                            'label' => 'LBL_RUN_WHEN',
                        ),
                        1 =>
                            array (
                                'name' => 'flow_run_on',
                                'studio' => 'visible',
                                'label' => 'LBL_FLOW_RUN_ON',
                            ),
                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'multiple_runs',
                            'label' => 'LBL_MULTIPLE_RUNS',
                        ),
                    ),
                    4 =>
                    array (
                        0 => 'process_name',
                        1 => 'subprocess_sequence_number',
                    ),
                    5 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_CONDITION_LINES' =>
                array (
                    0 =>
                    array (
                        0 => 'condition_lines',
                    ),
                ),
                'LBL_ACTION_LINES' =>
                array (
                    0 =>
                    array (
                        0 => 'action_lines',
                    ),
                ),
            ),
        ),
    );
