<?php return [
  'div#hi' => [
    'h1.logo#title' => '<b>Measury</b> <small>easily track and setup alerts for timeseries</small>',
    
    'form#auth:/auth' => [
      !user::id() ? [
        'input:email:Your Email' => '',
        'submit' => 'Signin'
      ] : [
        'a:/auth.button' => 'Go to my metrics'
      ]
    ],
    
    'img' => [':src' => '/img/measury-dashboard.png']
  ]
];