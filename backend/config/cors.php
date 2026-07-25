<?php

return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:4200', 'http://127.0.0.1:4200', 'http://localhost:10103', 'http://127.0.0.1:10103', 'http://localhost:65065', 'http://127.0.0.1:65065'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
