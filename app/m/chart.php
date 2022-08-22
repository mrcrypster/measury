<?php

$data = metrics::data($metric['metric'], null, ['period' => $period ?: '1 day']);
$max = $data ? max($data) : 0;

return array_map(function($k, $v) use ($max, $period) {
  $line = [
    'em' => dates::local_date(dates::date_by_period($period), $k),
    [$max && $v ? ['b' => [':style' => 'width:' . (60 * $v / $max) . '%']] : ''],
    'i' => v($v)
  ];
  return [
    'li#' . $k => $period ? array_reverse($line) :$line
  ];
}, array_keys($data), array_values($data));