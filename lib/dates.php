<?php

class dates {
  public static function local_date($format, $time) {
    $dt = new DateTime(date('Y-m-d H:i:s', $time));
    
    if ( $_COOKIE['tz'] ) {
      $dt->setTimezone(new DateTimeZone($_COOKIE['tz']));
    }
    
    return $dt->format($format);
  }
  
  public static function date_by_period($period) {
    if ( $period == '24d' ) return 'm/d';
    if ( $period == '12m' ) return 'M `y';
    else return 'H:00';
  }
}