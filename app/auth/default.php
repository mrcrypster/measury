<?php

if ( $_COOKIE['sk'] ) {
  redirect('/m');
}

if ( $_GET['ac'] ) {
  # Add signature to link and check it to fight bruteforce
  if ( $u = mysqly::users_(['ac' => $_GET['ac']]) ) {
    setcookie('sk', $u['sk'], time() + 60*60*24*30, '/');
    redirect('/m');
  }
  else {
    redirect('/');
  }
}
else if ( $email = $_POST['email'] ) {
  $u = mysqly::users_(['email' => $email]);
  
  if ( !$u ) {
    $symbols = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
    shuffle($symbols);
    
    $pk = $sk = '';
    
    for ( $i = 0; $i < 6; $i++ ) $pk .= $symbols[array_rand($symbols)];
    for ( $i = 0; $i < 32; $i++ ) $sk .= $symbols[array_rand($symbols)];
    
    $id = mysqly::insert('users', [
      'email' => $email,
      'pk' => $pk,
      'sk' => $sk
    ]);
  }
  
  $ac = mt_rand(1000000, 9999999);
  mysqly::update('users', $u['id'] ?: $id, ['ac' => $ac]);
  
  $body = 'Aloha!' . "\n\n".
          'Follow this link to sign in to your Measury dashboard:' . "\n" .
          'https://measury.io/auth?ac=' . $ac . "\n\n" .
          'Your Measury service';
           
  email($email, 'Your Measury.io Auth', $body);
  
  $domain = explode('@', $email)[1];
  return [
    '#auth' => [
      'Check your email to continue, ',
      'a' => [
        ':href' => 'http://' . $domain,
        ':target' => '_blank',
        'open ' . $domain
      ],
    ]
  ];
  exit;
}