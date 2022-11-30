<?php

if ( $_POST['enabled'] && $_POST['query'] && $_POST['value'] ) {
  mysqly::insert_update('rules', [
    'user_id' => user::id(),
    'query' => $_POST['query'],
    'aggregation' => $_POST['aggregation'],
    'period' => $_POST['period'],
    'period_type' => $_POST['period_type'],
    'operation' => $_POST['operation'],
    'value' => $_POST['value'],
  ]);
}
else {
  mysqly::remove('rules', [
    'user_id' => user::id(),
    'query' => $_POST['query'],
  ]);
}

phpy::pub('rule-aded');