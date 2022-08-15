<?php

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  return ['#contact-form' => 'Message received, thanks!'];
}

return [
  '#doc' => [
    'a.back:/' => 'Return',
    'h1' => 'Contact',
    'form.body#contact-form:/contact' => [
      'textarea:body:Your message' => '',
      'input:email:Your email' => '',
      'submit' => 'Send'
    ]
  ]
];