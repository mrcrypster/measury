<?php

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;
$last_update = metrics::last_update($metric['metric']);

phpy::pub('redata', [
  'metric' => $metric['metric'],
  'last_update' => $last_update
]);

if ( $last_update <= $_GET['last_update'] ) {
  return [];
}

return [
  '#day-val' => v(metrics::val($metric['metric'])),
  '#data' => phpy('/m/chart', ['metric' => $metric]),
  '#days' => phpy('/m/chart', ['metric' => $metric, 'period' => '24d']),
  
  # '#months' => phpy('/m/chart', ['metric' => $metric, 'period' => '12m']),
];