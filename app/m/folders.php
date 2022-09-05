<?php return [
  array_map(
    function($f) {
      $metrics = metrics::find(null, ['f' => $f['id']]);
      $on = $_GET['f'] == $f['id'];
      return [
        ($on ? 'span' : 'a') . ($on ? '.on' : '') . ':/m?f=' . $f['id'] . ($metrics ? ('&m=' . $metrics[0]) : '') =>
          $on ?
          [ 'span' => e($f['title']), 'input' => [':data' => ['id' => $f['id']], ':spellcheck' => 'false', e($f['title']) ]] :
          [ ':data' => ['id' => $f['id']], e($f['title']) ]
      ];
    },
    mysqly::fetch('folders', ['user_id' => user::id()])
  ),
  
  'button.add-folder:new_folder()' => '',
  'button.show-folders:show_folders()' => '&#8230;',
];