<?php

class metrics {
  public static function find($user_id = null, $options = []) {
    
    $folder_id = (int)$options['f'];
    
    return mysqly::array('SELECT metric FROM metrics WHERE user_id = :id AND folder_id = :f', [
      ':id' => $user_id ?: user::id(),
      ':f' => $folder_id
    ]) ?: [];
  }
  
  public static function data($metric, $user_id = null, $options = []) {
    $pk = user::data($user_id)['pk'];
    $key = $pk . '/' . $metric;
  
    $period_delta = 60*60*24;
    $bucket = $options['bucket'] ?: 60 * 60 * 1000;
    
    if ( $options['period'] == '14d' ) {
      $period_delta = 60*60*24*14;
      $bucket = $options['bucket'] ?: 60 * 60 * 24 * 1000;
    }
    else if ( $options['period'] == '12m' ) {
      $period_delta = 60*60*24*365;
      $bucket = $options['bucket'] ?: 60 * 60 * 24 * 30 * 1000;
    }
    
    $end = ceil(time()/($bucket / 1000)) * $bucket;
    $start = $end - $period_delta * 1000;
    
    $agg = 'sum';
    
    $cmd = "TS.RANGE {$key} {$start} {$end} AGGREGATION {$agg} {$bucket}";
    $res = redis($cmd, function($num, $lines) {
      $array = [];
      for ( $i = 0; $i < $num; $i++ ) {
        array_shift($lines);
        $k = intval(trim(array_shift($lines), ':'));
        $v = intval(array_shift($lines));
        $array[$k] = $v;
      }
      return $array;
    });
    
    $ts = [];
    foreach ( $res as $mts => $value ) {
      $ts[floor($mts / 1000)] = $value;
    }
    
    return self::to_timeline($ts, $start, $end, $bucket);
  }
  
  public static function val($metric, $user_id = null, $options = []) {
    $data = self::data($metric, $user_id, $options);
    return array_sum($data);
  }
  
  private static function to_timeline($data, $start, $end, $bucket) {
    $timeseries = [];
    
    for ( $ts = $end - $bucket; $ts >= $start ; $ts -= $bucket ) {
      $t = floor($ts / 1000);
      $timeseries[$t] = $data[ floor($t) ] ?: 0;
    }
    
    return $timeseries;
  }
}