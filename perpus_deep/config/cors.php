<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Idealnya ini diisi sesuai domain Flutter (misal: http://localhost:3000)
    // Tapi untuk dev/testing boleh pakai '*' dulu
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    // WAJIB true kalau pakai token/kredensial
    'supports_credentials' => true,

];
