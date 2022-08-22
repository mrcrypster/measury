<?php

if ( $_POST['query'] && $_POST['value'] ) {
  mysqly::insert('rules', [
    'user_id' => user::id(),
    'query' => $_POST['query'],
    'aggregation' => $_POST['aggregation'],
    'period' => $_POST['period'],
    'period_type' => $_POST['period_type'],
    'operation' => $_POST['operation'],
    'value' => $_POST['value'],
  ]);
}

return ['#rules' => phpy('/m/alerts/list')];