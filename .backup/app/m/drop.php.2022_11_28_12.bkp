<?php

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;

if ( $metric ) {
  $k = user::data()['pk'];
  redis("DEL {$k}/{$metric['metric']}");
  mysqly::remove('metrics', [
    'user_id' => user::id(),
    'metric' => $metric['metric']
  ]);
  mysqly::remove('rules', [
    'user_id' => user::id(),
    'query' => $metric['metric']
  ]);
  redirect('/m');
}