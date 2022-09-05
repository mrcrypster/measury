<?php return [
  array_map(function($row) {
    
    $is_error = mysqly::fetch('alerts', ['rule_id' => $row['id']]);
    if ( !$is_error ) return;
    
    return [
      'tr#r-' . $row['id'] => [
        
        ['td.status' . ($is_error ? '.error' : '') => []],
        ['td' => ['span' => 'if']],
        ['td.v.m' => [
          'span' => [$row['aggregation'], ' ( ', 'a' => [e($row['query']), ':href' => '/m?m=' . urlencode($row['query'])], ' )']
        ]],
        ['td' => ['span' => 'for the last']],
        ['td.v' => ['span' => [ $row['period'], ' ', $row['period_type']]]],
        ['td' => ['span' => 'is']],
        ['td.v' => ['span' => $row['operation']]],
        ['td' => ['span' => 'than']],
        ['td.v' => ['span' => $row['value']]],
        
        ['td.d' => ['button:/m/alerts/drop' => ['', ':data' => ['id' => $row['id']]]]],
      ]
    ];
  }, $rules)
];