<?php

if ( $_POST['id'] ) {
  
  if ( $_POST['title'] ) {
    mysqly::update('folders', ['id' => $_POST['id'], 'user_id' => user::id()], ['title' => $_POST['title']]);
  }
  else {
    mysqly::remove('folders', ['id' => $_POST['id'], 'user_id' => user::id()]);
  }
  
}
else {
  $id = mysqly::insert('folders', [
    'user_id' => user::id(),
    'title' => $_POST['title'] ?: 'New label'
  ]);
  
  $_REQUEST['f'] = $id;
  return ['#folders' => phpy('/m/folders'), '#metrics-list' => ''];
}