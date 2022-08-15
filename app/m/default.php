<?php

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;

return [
  '#nav' => [
    'a.logo:/m' => '',
    
    'button.search' => '',
    'button.add-folder:new_folder()' => '',
    
    '#folders' => phpy('/m/folders'),
    
    'a.exit:/auth/exit' => 'Exit'
  ],
  
  phpy('m/metrics'),
  
  $metric ? [
    'div#charts' => [
      'h1' => e($metric['metric']),
      
      'div#day' => [
        'h2' => ['24 hours', 'b' => v(metrics::val($metric['metric']))],
        'ul.timeseries#data' => phpy('/m/chart', ['metric' => $metric])
      ],
      
      'div#history' => [
        ['h2#d14' => ['14 days']],
        'ul.timeseries#days' => phpy('/m/chart', ['metric' => $metric, 'period' => '14d']),
        
        ['h2#m12' => ['12 months']],
        'ul.timeseries#months' => phpy('/m/chart', ['metric' => $metric, 'period' => '12m']),
      ]
    ]
  ] : [
    'div#help' => [
      'p' => 'To track event, send HTTP request to:',
      'code' => 'https://measury/' . user::data()['pk'] . '/<b>[event_name]</b>'
    ]
  ],
];