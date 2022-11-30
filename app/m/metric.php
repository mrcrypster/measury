<?php

if ( !$_GET['m'] ) {
  return ['#metric' => ''];
}

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;
if ( !$metric ) {
  header('HTTP/1.0 404 Not Found');
  exit;
}


$check = mysqly::fetch('rules', ['user_id' => user::id(), 'query' => $metric['metric']])[0];
if ( $check ) {
  $alert = mysqly::fetch('alerts', ['rule_id' => $check['id'], 'metric' => $metric['metric']])[0];
}

pub('metric');

return ['#metric' => [
  #'#path' => [
    #$metric['folder_id'] ? ['a:/m?f=' . $metric['folder_id'] => mysqly::folders_title($metric['folder_id'])] : ['a:/m' => 'Unlabeled'],
    #' / ',
    #'b' => e($metric['metric']),
    
    /*'#tools' => [
      
      'button.alerts' . ($check ? '.checked' : '') . ($alert ? '.raised' : '') . ':setup_check()' => 'alerts'
    ],*/
  #],
  
  '#charts' => [
    ['.chart' => [
      'h2' => ['24 hours', 'b' => v(metrics::val($metric['metric'], null, ['period' => '1 day']))],
      'canvas.chrt' => [
        ':data-data' => json_encode(metrics::data($metric['metric'], null, ['period' => '1 day'])),
        ':data-color' => '#2ECC40',
        # ':height' => '100%'
      ],
    ]],
    
    '.chart' => [
      'h2' => ['30 days',  'b' => v(metrics::val($metric['metric'], null, ['period' => '30d']))],
      'canvas.chrt' => [
        ':data-data' => json_encode(metrics::data($metric['metric'], null, ['period' => '30d'])),
        # ':height' => '100%'
      ],
    ],
  ],
  
  '#check' . ($check ? '.enabled' : '') => phpy('/m/alerts/form', ['metric' => $metric, 'check' => $check]),
  
  '#tools' => [
    'a.remove:remove(\'' . $metric['metric'] . '\')' => 'Drop this metric',
  ],
]];