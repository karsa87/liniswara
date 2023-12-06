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
    'supplier' => [
        'index' => [
            'title' => 'Pemasok',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Pemasok',
                    'route' => 'supplier.index',
                ],
            ],
        ],
    ],
    'customer' => [
        'index' => [
            'title' => 'Agen',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Agen',
                    'route' => 'customer.index',
                ],
            ],
        ],
    ],
    'customer_address' => [
        'index' => [
            'title' => 'Alamat Agen',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Agen',
                    'route' => 'customer.index',
                ],
                [
                    'name' => 'Alamat Agen',
                    'route' => '#',
                ],
            ],
        ],
    ],
    'expedition' => [
        'index' => [
            'title' => 'Ekspedisi',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Ekspedisi',
                    'route' => 'expedition.index',
                ],
            ],
        ],
    ],
    'collector' => [
        'index' => [
            'title' => 'Penagih',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Penagih',
                    'route' => 'collector.index',
                ],
            ],
        ],
    ],
    'product' => [
        'index' => [
            'title' => 'Produk',
            'breadcrumbs' => [
                [
                    'name' => 'Katalog Produk',
                    'route' => '#',
                ],
                [
                    'name' => 'Produk',
                    'route' => 'product.index',
                ],
            ],
        ],
    ],
];
