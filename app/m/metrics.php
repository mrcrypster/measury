<?php

if ( !$_REQUEST['f'] ) $_REQUEST['f'] = 0;
$metrics = metrics::find(null, $_REQUEST);
pub('metrics');

return ['#metrics' => [
  '#folders' => phpy('/m/folders'),
  
  
  ['ol#metrics-list' => [
    'li.h' => [
      ['b' => 'Metric'],
      ['b' => '24h'], 
    ],
    
    $metrics ? array_map(function($m) {
      return [
        'li' . ($_REQUEST['m'] == $m ? '.on' : '') => [
          $m,
          ':data' => ['metric' => $m],
          ':onclick' => "metric('{$m}')",
          'small' => $note = mysqly::metrics_note(['metric' => $m]),
          'input::Add metric description...' => $note,
          'b' => v(metrics::val($m))
        ]
      ];
    }, $metrics) : [
      'p.no' => ['Read ', ['button:help(true)' => 'docs below'], ' to find out how to track new metrics']
    ]
  ]]
]];