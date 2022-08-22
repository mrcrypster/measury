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
$rules = mysqly::fetch('rules', ['user_id' => user::id()]);
if ( $metric ) {
  $check = mysqly::fetch('rules', ['user_id' => user::id(), 'query' => $metric['metric']])[0];
  if ( $check ) {
    $alert = mysqly::fetch('alerts', ['rule_id' => $check['id'], 'metric' => $metric['metric']])[0];
  }
}

return [
  '#nav' => phpy('m/nav'),
  $metrics ? phpy('m/metrics', ['metrics' => $metrics]) : [],
  
  $metric ? [
    'div#charts' => [
      '#tools' => [
        'a.remove:' . url(['drop' => 1]) => 'drop',
        'button.alerts' . ($check ? '.checked' : '') . ($alert ? '.raised' : '') . ':setup_check()' => 'alerts'
      ],

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
    ],
    
    '#check' => phpy('/m/alerts/form', ['metric' => $metric, 'check' => $check])
  ] : [
    [
      'input#search::Search metrics' => [':list' => 'my-metrics'],
      'datalist' => [array_map(function($m) {
        return ['option' => $m];
      }, metrics::all()), ':id' => 'my-metrics']
    ],
    
    [
      'table#rules' => phpy('/m/alerts/list', ['rules' => $rules]),
    ],
    
    'p#coverage' => [
      ['b' => count($rules)],
      'out of',
      ['b' => metrics::total()],
      'metrics are covered with automated checks',
      '<br>',
      '.covered' => ['.done' => [':style' => 'width:' . round(100 * count($rules)/metrics::total()) . '%']]
    ],

    [
      'div#help' => [
        'p' => 'To collect timeseries event, send HTTP request to:',
        'code' => 'https://measury.io/' . user::data()['pk'] . '/<b>[event_name]</b>',
        ['p' => 'You can send custom value:'],
        ['code' => 'https://measury.io/' . user::data()['pk'] . '/[event_name]/<b>[value]</b>']
      ]
    ]
  ],
];