<?php

if ( $_POST['m'] ) {
  mysqly::update('metrics', [
    'user_id' => user::id(),
    'metric' => $_POST['m']
  ], [
    'note' => $_POST['note']
  ]);
}