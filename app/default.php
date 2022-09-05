<?php return [
  'div#hi' => [
    'h1.logo#title' => '<b>Measury</b> <small>is used to collect and monitor timeseries data</small>',
    
    'form#auth:/auth' => [
      !user::id() ? [
        'input:email:Your Email' => '',
        'submit' => 'Signin'
      ] : [
        'a:/auth.button' => 'Go to my metrics'
      ]
    ],
    
    # 'img' => [':src' => '/img/measury-dashboard.png']
  ]
];