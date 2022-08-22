<?php return [
  'form:/m/alerts/add' => [
    # json_encode($check),
    'hidden:query' => $metric['metric'],
    'hidden:aggregation' => 'sum',
    
    'if',
    ' ',
    '.query' => ['sum(',e($metric['metric']),')'],
    
    ' ',
    'during the last',
    ' ',
    
    '.period' => [
      'input:period' => $check['period'] ?: 1,
      'select:period_type:' . ($check['period_type'] ?: 'days') => [
        'seconds' => 'seconds',
        'minutes' => 'minutes',
        'hours' => 'hours',
        'days' => 'days',
        'months' => 'months'
      ]
    ],
    
    'is',
    
    '.is' => [
      'select:operation:' . ($check['operation'] ?: 'less') => ['less' => 'less than', 'more' => 'more than', 'equal' => 'equal to'],
      'input:value:Value' => $check['value'] ?: 1
    ],
    
    '.then' => 'then',
    'submit' => 'email me',
    'button.gray:cancel_rule()' => 'cancel'
  ]
];