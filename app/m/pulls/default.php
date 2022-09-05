<?php

if ( $_GET['json'] ) {
  header('Content-type: text/plain');
  echo json_encode(mysqly::fetch('SELECT \'' . user::data()['pk'] . '\' as `key`, url, selector, metric FROM pulls WHERE user_id = :u', ['u' => user::id()]));
  exit;
}

return [
  ['tr' => ['th' => [ ':colspan' => 3, 'h3' => 'Pull data' ]]],
  
  array_map(function($p) {
    return ['tr' => [
      ['td' => [e($p['url']), 'code' => e($p['selector'])]],
      ['td' => [e($p['metric'])]],
      ['td' => $p['last_fetch'] ? dates::local_date('m/d H:i', strtotime($p['last_fetch'])) : 'not yet fetched'],
    ]];
  }, mysqly::fetch('pulls', ['user_id' => user::id()])),
  
  ['tr' => ['td' => [ ':colspan' => 3, 'form:/m/pulls/save' => [
    'input:url:URL' => '',
    'input:selector:Query Selector' => '',
    'input:metric:Choose Metric Name' => '',
    'submit' => 'Add pull op'
  ]]]]
];