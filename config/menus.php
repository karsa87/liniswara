<?php

return [
    'dashboard' => [
        'title' => 'Dashboard',
        'route' => 'dashboard',
    ],
    'permission' => [
        'index' => [
            'title' => 'Permission',
            'breadcrumbs' => [
                [
                    'name' => 'User & Hak Akses',
                    'route' => '#',
                ],
                [
                    'name' => 'Permission',
                    'route' => 'permission.index',
                ],
            ],
        ],
    ],
    'role' => [
        'index' => [
            'title' => 'Role',
            'breadcrumbs' => [
                [
                    'name' => 'User & Hak Akses',
                    'route' => '#',
                ],
                [
                    'name' => 'Role',
                    'route' => 'role.index',
                ],
            ],
        ],
    ],
    'branch' => [
        'index' => [
            'title' => 'Gudang',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Gudang',
                    'route' => 'branch.index',
                ],
            ],
        ],
    ],
];
