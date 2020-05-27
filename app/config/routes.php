<?php

return [
    '' => [
        'controller' => 'task',
        'action'     => 'index'
    ],
    '\?page=[0-9]+' => [
        'controller' => 'task',
        'action'     => 'index'
    ],
    '\?page=[0-9]+\&order_by=[a-z]+\&sort=(asc|desc)' => [
        'controller' => 'task',
        'action'     => 'index'
    ],
    'task/[0-9]+' => [
        'controller' => 'task',
        'action'     => 'edit'
    ],
    'task/[0-9]+/update' => [
        'controller' => 'task',
        'action'     => 'update'
    ],
    'task/create' => [
        'controller' => 'task',
        'action'     => 'create'
    ],
    'task/store' => [
        'controller' => 'task',
        'action'     => 'store'
    ],
    'admin' => [
        'controller' => 'admin',
        'action'     => 'index'
    ],
    'admin/login' => [
        'controller' => 'admin',
        'action'     => 'login'
    ],
    'admin/logout' => [
        'controller' => 'admin',
        'action'     => 'logout'
    ],
];