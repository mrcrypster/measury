<?php return [
  '#doc' => [
    'a.back:/' => 'Return',
    'h1' => 'Terms of Use',
    'div.body' => (function() {
      ob_start();
      include __DIR__ . '/terms.html';
      return ob_get_clean();
    })()
  ]
];