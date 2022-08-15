<?php

class user {
  public static function id() {
    if ( !$_COOKIE['sk'] ) {
      return;
    }
    return mysqly::users_(['sk' => $_COOKIE['sk']])['id'];
  }
  
  public static function data($id = null) {
    $id = $id ?: self::id();
    return $id ? mysqly::users_($id) : null;
  }
}