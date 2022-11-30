<?php

$found = [];

if ( $_POST['q'] ) {
  $found = metrics::find(user::id(), ['q' => $_POST['q']]);
}

return [
  '#search-results' => array_map(function($m) {
    $m = mysqly::metrics_(['user_id' => user::id(), 'metric' => $m]);
    return ['li' => [
      ':data' => ['m' => $m['metric'], 'f' => $m['folder_id']],
      ':onmouseover' => "search_hover(this)",
      ':onclick' => "search_go(this)",
      'b' => e($m['metric']),
      'small' => e($m['note'])]
    ];
  }, $found) ?: ['em' => 'Nothing like that found, sorry...']
];