<?php
return [
  [
    'module'  => 'dialfire',
    'name'    => 'dialfire_response_status',
    'entity'  => 'OptionGroup',
    'cleanup' => 'never',
    'params'  => [
      'version'   => 3,
      'name'      => 'dialfire_response_status',
      'title'     => 'Dialfire Response Status',
      'data_type' => 'Integer',
      'is_active' => 1,
    ],
  ],
  [
    'module'  => 'dialfire',
    'name'    => 'dialfire_response_status_pending',
    'entity'  => 'OptionValue',
    'cleanup' => 'never',
    'params'  => [
      'version'         => 3,
      'option_group_id' => 'dialfire_response_status',
      'value'           => 1,
      'name'            => 'pending',
      'label'           => 'Pending',
      'is_active'       => 1,
    ],
  ],
  [
    'module'  => 'dialfire',
    'name'    => 'dialfire_response_status_in_progress',
    'entity'  => 'OptionValue',
    'cleanup' => 'never',
    'params'  => [
      'version'         => 3,
      'option_group_id' => 'dialfire_response_status',
      'value'           => 2,
      'name'            => 'in_progress',
      'label'           => 'In Progress',
      'is_active'       => 1,
    ],
  ],
  [
    'module'  => 'dialfire',
    'name'    => 'dialfire_response_status_completed',
    'entity'  => 'OptionValue',
    'cleanup' => 'never',
    'params'  => [
      'version'         => 3,
      'option_group_id' => 'dialfire_response_status',
      'value'           => 3,
      'name'            => 'completed',
      'label'           => 'Completed',
      'is_active'       => 1,
    ],
  ],
  [
    'module'  => 'dialfire',
    'name'    => 'dialfire_response_status_failed',
    'entity'  => 'OptionValue',
    'cleanup' => 'never',
    'params'  => [
      'version'         => 3,
      'option_group_id' => 'dialfire_response_status',
      'value'           => 4,
      'name'            => 'failed',
      'label'           => 'Failed',
      'is_active'       => 1,
    ],
  ],
];
