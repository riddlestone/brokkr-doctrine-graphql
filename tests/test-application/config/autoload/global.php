<?php

use Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities\TestEntity;

return [
    'doctrine' => [
        'driver' => [
            'my_annotation_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../../../Classes/Entities',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Riddlestone\Brokkr\DoctrineGraphQL\Test' => 'my_annotation_driver',
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOSqlite\Driver::class,
                'params' => [
                    'memory' => true,
                ],
            ],
        ],
    ],
    'graphql_fields' => [
        'query_fields' => [
            'test_entities' => TestEntity::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'errors/404',
        'exception_template' => 'errors/500',
        'template_path_stack' => [
            __DIR__ . '/../../views',
        ],
    ],
];
