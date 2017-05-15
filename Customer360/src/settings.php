<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        // Couchbase settings
        'couchbase' => [
          'username' => 'Customer360',
          'password' => 'password',
          'bootstrapUri' => 'couchbase://127.0.0.1',
          'bucketName' => 'Customer360'
        ],
    ],
];
