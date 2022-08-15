<?php return [
  'html' => [
    ':v' => 3,
    ':title' => 'Measury - track & manage timeseries',
    ':head' => '<link rel="preconnect" href="https://fonts.googleapis.com">' .
               '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' .
               '<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;500&display=swap" rel="stylesheet">' .
               '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
               
    'div#content.c' . str_replace('/', '-', phpy::endpoint()) => phpy(),
    
    'div#foot' => [
      'a:/' => 'Measury.io home',
      'a:/contact' => 'Contact',
      'a:/privacy' => 'Privacy policy',
      'a:/terms' => 'Terms of Use',
      ['a' => ['Contribute on Github', ':href' => 'https://github.com/mrcrypster/measury', ':target' => '_blank']],
      ['a' => ['Building data-intensive apps', ':href' => 'https://medium.com/datadenys', ':target' => '_blank']]
    ],
    
    'style' => '#foot a[href="' . e(phpy::endpoint()) . '"] { pointer-events: none; color: #000; text-decoration: none; }'
  ]
];