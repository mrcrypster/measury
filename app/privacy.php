<?php return [
  '#doc' => [
    'a.back:/' => 'Return',
    'h1' => 'Privacy Policy',
    'div.body' => (function() {
      ob_start();
      include __DIR__ . '/privacy.html';
      return ob_get_clean();
    })()
  ]
];