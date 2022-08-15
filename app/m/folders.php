<?php return array_map(
  function($f) {
    $on = $_GET['f'] == $f['id'];
    return [
      ($on ? 'span' : 'a') . ($on ? '.on' : '') . ':/m?f=' . $f['id'] =>
        $on ?
        [ 'span' => e($f['title']), 'input' => [':data' => ['id' => $f['id']], ':spellcheck' => 'false', e($f['title']) ]] :
        [ ':data' => ['id' => $f['id']], e($f['title']) ]
    ];
  },
  mysqly::fetch('folders', ['user_id' => user::id()])
);