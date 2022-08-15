<?php

require_once __DIR__ . '/../lib.php';


phpy::on('/css.css', function($phpy) {
  header('Content-type: text/css');

  $less = $phpy->collect(['css', 'less']);
  if ( md5_file('/tmp/less.less') != md5($less) ) {
    file_put_contents('/tmp/less.less', $less);
    exec('lessc -s /tmp/less.less /tmp/less.css');
  }

  readfile('/tmp/less.css');
});

phpy::on('/js.js', function($phpy) {
  header('Content-type: application/javascript');

  $js = $phpy->collect(['js']);
  echo $js;
});

phpy::on('/\/m.*/misu', function($phpy) {
  if ( !$_COOKIE['sk'] ) {
    redirect('/');
  }
  
  return true;
});


phpy(['/' => __DIR__]);