<?php

require_once '/var/www/mysqly/mysqly.php';
require_once '/var/www/phpy2/phpy.php';
require_once __DIR__ . '/lib/user.php';
require_once __DIR__ . '/lib/metrics.php';
require_once __DIR__ . '/lib/dates.php';
require_once __DIR__ . '/lib/redis.php';
require_once '/var/lib/aws/aws.phar';



# Render numeric value
function v($v) {
  return number_format($v, 0, '.', ' ');
}



# current url generator
function url($params = []) {
  $url = parse_url($_SERVER['REQUEST_URI']);
  parse_str($url['query'], $query);
  
  $query = array_merge($query, $params);
  
  return $url['path'] . ($query ? '?' . http_build_query($query):'');
}



# send email
function email($to, $subject, $body) {
  static $ses;
  
  if ( !$ses ) {
    $ses = new Aws\Ses\SesClient([ 'profile' => 'default', 'version' => '2010-12-01', 'region' => 'us-east-1' ]);
  }
  
  $sender = 'ai@measury.io';

  try {
    $result = $ses->sendEmail([
      'Destination' => [ 'ToAddresses' => [$to] ],
      'ReplyToAddresses' => [$sender],
      'Source' => $sender,
      'Message' => [
        'Body' => [ 'Text' => [ 'Charset' => 'utf-8', 'Data' => $body ] ],
        'Subject' => [ 'Charset' => 'utf-8', 'Data' => $subject ],
      ]
    ]);
    return true;
  }
  catch (AwsException $e) {
    # echo $e->getMessage();
    return false;
  }
}