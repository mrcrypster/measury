<?php

# Index metrics for users

require_once __DIR__ . '/../lib.php';

while ( true ) {
  foreach ( mysqly::fetch('SELECT * FROM rules WHERE is_enabled = 1') as $r ) {
    
    $period = $r['period'] . ' ' . $r['period_type'];
    $bucket = strtotime(date('Y-m-d H:i:s', 0) . ' +' . $period);
    $metric = $r['query'];
    
    $val = metrics::val($metric, $r['user_id'], ['bucket' => $bucket * 1000, 'period' => $period]);
    
    switch ( $r['operation'] ) {
      case 'less': $bad = $val < $r['value']; break;
      case 'more': $bad = $val < $r['value']; break;
      case 'equal': $bad = $val == $r['value']; break;
    }
    
    $alert = [ 'rule_id' => $r['id'], 'metric' => $metric ];
    
    if ( $bad ) {
      if ( !mysqly::fetch('alerts', $alert) ) {
        echo 'New alert -> ' . $metric . ': ' . $val . "\n";
        
        $body = $r['aggregation'] . '(' . $metric . ') ' . ' for ' .
                $r['period'] . ' ' . $r['period_type'] . ' is ' .
                $r['operation'] . ' than ' . $r['value'] . "\n" .
                'https://measury.io/m?m=' . $metric;
                
        email(mysqly::users_email($r['user_id']), 'Alert for [' . $metric . '] metric', $body);
        mysqly::insert('alerts', $alert);
      }
    }
    else {
      if ( mysqly::fetch('alerts', $alert) ) {
        mysqly::remove('alerts', $alert);
      }
    }
  }
  
  sleep(1);
}