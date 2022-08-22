<?php

$r = mysqly::rules_(['id' => $_POST['id'], 'user_id' => user::id()]);

if ( $r ) {
  mysqly::remove('rules', $r['id']);
  mysqly::remove('alerts', ['rule_id' => $r['id']]);
}

return [
  '#r-' . $r['id'] => ''
];