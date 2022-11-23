<?php

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;

if ( $metric['folder_id'] && ($metric['folder_id'] != $_GET['f']) )  {
  redirect('/m?m=' . $metric['metric'] . '&f=' . $metric['folder_id']);
}

if ( $metric && $_GET['drop'] ) {
  $k = user::data()['pk'];
  redis("DEL {$k}/{$metric['metric']}");
  mysqly::remove('metrics', [
    'user_id' => user::id(),
    'metric' => $metric['metric']
  ]);
  redirect('/m');
}

phpy::pub('redata', [
  'metric' => $metric['metric'],
  'last_update' => metrics::last_update($metric['metric'])
]);

$metrics = metrics::find(null, $_GET);

if ( $metric ) {
  $check = mysqly::fetch('rules', ['user_id' => user::id(), 'query' => $metric['metric']])[0];
  if ( $check ) {
    $alert = mysqly::fetch('alerts', ['rule_id' => $check['id'], 'metric' => $metric['metric']])[0];
  }
}


return [
  '#nav' => phpy('m/nav'),
  
  $metric ? ['#metric' => [
    '#path' => [
      $metric['folder_id'] ? ['a:/m?f=' . $metric['folder_id'] => mysqly::folders_title($metric['folder_id'])] : ['a:/m' => 'Unlabeled'],
      ' / ',
      'b' => e($metric['metric']),
      
      '#tools' => [
        'a.remove:' . url(['drop' => 1]) => 'drop',
        'button.alerts' . ($check ? '.checked' : '') . ($alert ? '.raised' : '') . ':setup_check()' => 'alerts'
      ],
    ],
    
    [
      'h2' => '24 hours',
      'canvas.chrt' => [
        ':data-data' => json_encode(metrics::data($metric['metric'], null, ['period' => '1 day'])),
        ':data-color' => '#2ECC40'
      ],
    ],
    
    [
      'h2' => '30 days',
      'canvas.chrt' => [':data-data' => json_encode(metrics::data($metric['metric'], null, ['period' => '30d']))],
    ],
    
    /*'div#charts' => [
      'div#day' => [
        'h2' => ['24 hours', 'b#day-val' => v(metrics::val($metric['metric']))],
        'ul.timeseries#data' => phpy('/m/chart', ['metric' => $metric])
      ],
      
      'div#history' => [
        ['h2#d24' => ['24 days']],
        'ul.timeseries#days' => phpy('/m/chart', ['metric' => $metric, 'period' => '24d']),
        
        //['h2#m12' => ['12 months']],
        //'ul.timeseries#months' => phpy('/m/chart', ['metric' => $metric, 'period' => '12m']),
      ]
    ],*/
    
    '#check' => phpy('/m/alerts/form', ['metric' => $metric, 'check' => $check])
  ]] : [
    phpy('m/metrics', ['metrics' => $metrics]),
    
    
    # [ 'table#rules' => phpy('/m/alerts/list', ['rules' => $rules]) ?: ['tr' => ['td.ok' => 'All metrics are OK']] ],
    
    
    /*[
      'table#uncovered' => [
        'tr' => ['td' => ['h3' => 'Metrics not yet covered with checks']],
        array_map(function($m) {
          return ['tr' => ['td' => [
            'a' => [e($m['metric']), ':href' => '/m?m=' . urlencode($m['metric'])]
          ]]];
        }, metrics::uncovered())
      ],
    ],*/
    
    # ['table#pull' => phpy('/m/pulls'),]
  ],
];