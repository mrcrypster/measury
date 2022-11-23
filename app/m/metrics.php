<?php return ['#metrics' => [
  '#folders' => [
    array_map(
      function($f) {
        $metrics = metrics::find(null, ['f' => $f['id']]);
        $on = $_GET['f'] == $f['id'];
        return [
          ($on ? 'span' : 'a') . ($on ? '.on' : '') . (!$f['id'] ? '.dflt' : '') . ':/m' . ($f['id'] ? '?f=' . $f['id'] : '') =>
            $on ?
            [
              'span' => e($f['title']),
              $f['id'] ? ['input' => [':data' => ['id' => $f['id']], ':spellcheck' => 'false', e($f['title']) ]] : []
            ] :
            [ ':data' => ['id' => $f['id']], e($f['title']) ]
        ];
      },
      [['title' => 'Unlabeled']] + mysqly::fetch('folders', ['user_id' => user::id()])
    ),
    
    'button.add-folder:new_folder()' => 'New label',
    # 'button.show-folders:show_folders()' => '&#8230;',
  ],
  
  !$metrics ? [
    'div.help' => [
      'p' => 'Send HTTP requests to the following url:',
      'code' => 'https://measury.io/' . user::data()['pk'] . '/<b>[event_name]</b>',
      ['p' => 'To send metric with a custom value:'],
      ['code' => 'https://measury.io/' . user::data()['pk'] . '/[event_name]/<b>[value]</b>']
    ]
  ] : [],
  
  'ol.metrics-list' => array_map(function($m) {
    return [
      'li' . ($_GET['m'] == $m ? '.on' : '') => [
        'a' => [
          $m,
          ':href' => url(['m' => $m]),
          ':data' => ['metric' => $m]
        ],
        
        'b' => v(metrics::val($m))
      ]
    ];
  }, $metrics)
]];