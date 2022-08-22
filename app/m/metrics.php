<?php return [
  'ol.metrics-list' => array_map(function($m) {
    return [
      'li' . ($_GET['m'] == $m ? '.on' : '') => [
        'a' => [
          $m,
          ':href' => url(['m' => $m]),
          ':data' => ['metric' => $m]
        ],
        
        # 'b' => v(metrics::val($m))
      ]
    ];
  }, $metrics)
];