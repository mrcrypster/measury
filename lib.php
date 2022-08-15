<?php

require_once '/var/www/mysqly/mysqly.php';
require_once '/var/www/phpy2/phpy.php';
require_once __DIR__ . '/lib/user.php';
require_once __DIR__ . '/lib/metrics.php';
require_once __DIR__ . '/lib/dates.php';
require_once __DIR__ . '/lib/redis.php';



# Render numeric value
function v($v) {
  return number_format($v, 0, '.', ' ');
}



# current url generator
function url($params = []) {
  $url = parse_url($_SERVER['REQUEST_URI']);
  parse_str($url['query'], $query);
  
  $query = array_merge($query, $params);
  
  return $url['path'] . ($query ? '?' . http_build_query($query):'');
}