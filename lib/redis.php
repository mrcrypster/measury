<?php

function redis($cmd, $callback = null) {
  $payload = $cmd . "\r\n";
  $stream = stream_socket_client("tcp://127.0.0.1:6379", $errno, $errstr);
  if ( !$stream ) {
    die("{$errno}: {$errstr}");
  }
  
  fwrite($stream, $payload);
  stream_socket_shutdown($stream, STREAM_SHUT_WR);
  $contents = stream_get_contents($stream);
  fclose($stream);
  
  $lines = explode("\r\n", $contents);
  $num = trim(array_shift($lines), '*');
  if ( $callback ) {
    return $callback($num, $lines);
  }
}