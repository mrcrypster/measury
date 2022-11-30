<?php

$r = [
  array_map(
    function($f) {
      $metrics = metrics::find(null, ['f' => $f['id']]);
      $on = $_REQUEST['f'] == $f['id'];
      return [
        ($on ? 'span' : 'a') . ($on ? '.on' : '') . (!$f['id'] ? '.dflt' : '') . ':folder_load(' . ($f['id'] ?: "''") . ', true);' =>
          $on ?
          [
            'span' => e($f['title']),
            $f['id'] ? ['input' => [':data' => ['id' => $f['id']], ':spellcheck' => 'false', e($f['title']) ]] : []
          ] :
          [ ':data' => ['id' => $f['id']], e($f['title']), ':onmousedown' => 'folder_load(' . ($f['id']) . ')' ]
      ];
    },
    array_merge([['title' => 'Unlabeled']], mysqly::fetch('folders', ['user_id' => user::id()]))
  ),
  
  'form:/m/folder' => [
    'input:title:New label...' => '',
    'submit' => '&raquo;'
  ]
];

pub('folders');

return $r;