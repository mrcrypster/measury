<?php

if ( $_POST['selector'] && $_POST['url'] && $_POST['metric'] ) {
  mysqly::insert('pulls', [
    'user_id' => user::id(),
    'selector' => $_POST['selector'],
    'metric' => $_POST['metric'],
    'url' => $_POST['url']
  ]);
}

return ['#pull' => phpy('/m/pulls')];