<?php

if ( metrics::total() ) {
  $rules = mysqly::fetch('rules', ['user_id' => user::id()]);
  $covered = round(100 * count($rules)/metrics::total());
}

$html = [
  metrics::total() ? [
    'span#coverage' => [
      '.covered.progressbar' . ($covered == 100 ? '.ok' : '') => [
        ':onclick' => 'coverage()',
        '.done.bar' => [':style' => 'width:' . $covered . '%'],
        ['.hint' => [['b' => count($rules)], ' out of ', ['b' => metrics::total()], ' metrics are covered with automated checks']]
      ],
      
      'ul' => array_map(function($m) {
        return ['li' => ['a:/m?m=' . $m['metric'] . '&f=' . $m['folder_id'] => e($m['metric'])]];
      }, metrics::uncovered(user::id()))
    ]
  ] : [],

  '#alerts' => phpy('/m/alerts/list', ['rules' => $rules]),
];

return $_POST ? $html : ['#health' => $html];