<?php

# Index metrics for users

require_once __DIR__ . '/../lib.php';

while ( true ) {
  // index key names
  $keys = redis('KEYS ??????/*', function($count, $records) {
    $keys = [];
    for ( $i = 0; $i < $count; $i++ ) {
      array_pop($records);
      $key = array_pop($records);
      list($k, $m) = explode('/', $key);
      $keys[$k][] = $m;
    }
    
    return $keys;
  });
  
  foreach ( $keys as $k => $m ) {
    $u = mysqly::users_(['pk' => $k]);
    if ( !$u ) {
      continue;
    }
    
    $vals = [];
    $bind = [];
    foreach ( $m as $i => $t ) {
      $vals[] = "({$u['id']}, :t{$i})";
      $bind[':t' . $i] = $t;
    }
    mysqly::exec('INSERT IGNORE INTO metrics(user_id, metric) VALUES' . implode(',', $vals), $bind);
  }
  
  
  // update pulls times
  foreach ( mysqly::fetch('pulls') as $p ) {
    $updated = metrics::last_update($p['metric'], $p['user_id']);
    if ( $updated ) {
      mysqly::update('pulls', $p['id'], ['last_fetch' => date('Y-m-d H:i:s', intval($updated/1000))]);
    }
  }
  
  
  sleep(1);
}