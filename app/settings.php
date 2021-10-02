<?php
return [
    'settings' => [
        'cache_dir' => __DIR__ . '/../var/cache',
        'db' => [
            'host' => 'database',
            'user' => 'admin',
            'pass' => 'password',
            'dbname' => 'database',
            'port' => 47001,
        ],
        'phinx_settings' => [
            'paths' => [
                'migrations' => __DIR__ . '/../migrations/migrations',
                'seeds' => __DIR__ . '/../migrations/seeds',
            ],
            'environments' => [
                'default_migration_table' => 'phinxlog',
                'default_database' => 'dev',
                'dev' => [
                    'adapter' => 'pgsql',
                    'host' => 'database',
                    'name' => 'database',
                    'user' => 'admin',
                    'pass' => 'password',
                    'port' => 5432,
                ],
            ],
        ],
    ],
];