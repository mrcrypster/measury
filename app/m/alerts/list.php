<?php return [
  array_map(function($row) {
    return [
      'tr#r-' . $row['id'] => [
        ['td' => ['button:/m/alerts/drop' => ['', ':data' => ['id' => $row['id']]]]],
        ['td' => ['span' => 'if']],
        ['td.v' => [
          'span' => [$row['aggregation'], ' ( ', e($row['query']), ' )']
          #'ul' => array_map(function($a) {
          #  return ['li' => ['a:/m?m=' . $a['metric'] => e($a['metric'])]];
          #}, mysqly::fetch('alerts', ['rule_id' => $row['id']]))
        ]],
        ['td' => ['span' => 'for the last']],
        ['td.v' => ['span' => [ $row['period'], ' ', $row['period_type']]]],
        ['td' => ['span' => 'is']],
        ['td.v' => ['span' => $row['operation']]],
        ['td' => ['span' => 'than']],
        ['td.v' => ['span' => $row['value']]]
      ]
    ];
  }, $rules)
];