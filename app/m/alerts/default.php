<?php

$metric = $_GET['m'] ? mysqly::metrics_(['user_id' => user::id(), 'metric' => $_GET['m']]) : null;

return [
  '#nav' => phpy('m/nav')
  
];