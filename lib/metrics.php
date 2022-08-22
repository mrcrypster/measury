<?php

class metrics {
  public static function find($user_id = null, $options = []) {
    
    $folder_id = (int)$options['f'];
    
    return mysqly::array('SELECT metric FROM metrics WHERE user_id = :id AND folder_id = :f', [
      ':id' => $user_id ?: user::id(),
      ':f' => $folder_id
    ]) ?: [];
  }
  
  public static function total($user_id = null) {
    return mysqly::fetch('SELECT count(*) total FROM metrics WHERE user_id = :id', [
      ':id' => $user_id ?: user::id()
    ])[0]['total'];
  }
  
  public static function last_update($metric, $user_id = null) {
    $pk = user::data($user_id)['pk'];
    $key = $pk . '/' . $metric;
    
    $res = redis('TS.INFO ' . $key, function($num, $lines) {
      return trim($lines[7], ':');
    });
    
    return $res;
  }
  
  public static function data($metric, $user_id = null, $options = []) {
    $pk = user::data($user_id)['pk'];
    $key = $pk . '/' . $metric;
  
    $period_delta = 60*60*24;
    $bucket = isset($options['bucket']) ? $options['bucket'] : 60 * 60 * 1000;
    $now = ceil(time()/($bucket/1000)) * ($bucket / 1000);
    $delta = '1 hour';

    if ( $options['period'] == '24d' ) {
      $period_delta = 60*60*24*24;
      $bucket = $options['bucket'] ?: 60 * 60 * 24 * 1000;
      $now = strtotime(date('Y-m-d 00:00:00') . ' + 1 day');
      $delta = '1 day';
    }
    else if ( $options['period'] == '12m' ) {
      $period_delta = 60*60*24*365;
      $bucket = $options['bucket'] ?: 60 * 60 * 24 * 1000;
      $now = strtotime(date('Y-m-t 00:00:00') . ' +1 day');
      $delta = '1 month';
    }
    else if ( $options['period'] ) {
      $period_delta = strtotime(date('Y-m-d H:i:s', 0) . ' +' . $options['period']);
      # $delta = $options['period'];
    }
    
    $end = $now * 1000;
    
    $start = $end - $period_delta * 1000;
    
    $agg = 'sum';
    
    $cmd = "TS.RANGE {$key} {$start} + AGGREGATION {$agg} {$bucket}";
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
      if ( $options['period'] == '12m' ) {
        $ts[strtotime(date('Y-m-01', floor($mts / 1000)))] += $value;
      }
      else {
        $ts[floor($mts / 1000)] = $value;
      }
    }
    
    return self::to_timeline($ts, $start, $end, $delta);
  }
  
  public static function val($metric, $user_id = null, $options = []) {
    $data = self::data($metric, $user_id, $options);
    return array_sum($data);
  }
  
  private static function to_timeline($data, $start, $end, $delta) {
    $timeseries = [];
    $now = (strtotime(date('Y-m-d H:i:s', floor($end / 1000)) . ' -' . $delta)) * 1000;
    
    while ( $now >= $start ) {
      $t = floor($now / 1000);
      $timeseries[$t] = isset($data[$t]) ? $data[$t] : 0;
      
      $now = (strtotime(date('Y-m-d H:i:s', $t) . ' -' . $delta)) * 1000;
    }

    return $timeseries;
  }
}