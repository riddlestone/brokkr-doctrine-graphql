<?php

return [
    'modules' => [
        'Laminas\Router',
        'Laminas\Mvc\Middleware',
        'Riddlestone\Brokkr\GraphQL',
        'DoctrineModule',
        'DoctrineORMModule',
        'Riddlestone\Brokkr\DoctrineGraphQL',
    ],
    'module_listener_options' => [
        'use_laminas_loader' => false,
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
        'config_cache_enabled' => false,
        'module_map_cache_enabled' => false,
    ],
];
