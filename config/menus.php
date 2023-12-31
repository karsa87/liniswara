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
    'restock' => [
        'index' => [
            'title' => 'Re-Stok',
            'breadcrumbs' => [
                [
                    'name' => 'Katalog Produk',
                    'route' => '#',
                ],
                [
                    'name' => 'Re-Stock',
                    'route' => 'restock.index',
                ],
            ],
        ],
    ],
    'preorder_book' => [
        'index' => [
            'title' => 'Buku Preorder',
            'breadcrumbs' => [
                [
                    'name' => 'Katalog Produk',
                    'route' => '#',
                ],
                [
                    'name' => 'Buku Preorder',
                    'route' => 'preorder_book.index',
                ],
            ],
        ],
    ],
    'preorder' => [
        'index' => [
            'title' => 'Preorder',
            'breadcrumbs' => [
                [
                    'name' => 'Transaksi',
                    'route' => '#',
                ],
                [
                    'name' => 'Preorder',
                    'route' => 'preorder.index',
                ],
            ],
        ],
    ],
    'setting' => [
        'index' => [
            'title' => 'Setting',
            'breadcrumbs' => [
                [
                    'name' => 'Setting',
                    'route' => 'setting.index',
                ],
            ],
        ],
    ],
    'stock_product' => [
        'index' => [
            'title' => 'Log Stok Produk',
            'breadcrumbs' => [
                [
                    'name' => 'Log',
                    'route' => '#',
                ],
                [
                    'name' => 'Buku Preorder',
                    'route' => 'log.stock_product.index',
                ],
            ],
        ],
    ],
    'history' => [
        'index' => [
            'title' => 'Aktifitas',
            'breadcrumbs' => [
                [
                    'name' => 'Log',
                    'route' => '#',
                ],
                [
                    'name' => 'Aktifitas',
                    'route' => 'log.history.index',
                ],
            ],
        ],
    ],
    'order' => [
        'index' => [
            'title' => 'Pesanan',
            'breadcrumbs' => [
                [
                    'name' => 'Transaksi',
                    'route' => '#',
                ],
                [
                    'name' => 'Pesanan',
                    'route' => 'order.index',
                ],
            ],
        ],
    ],
    'order_sent' => [
        'index' => [
            'title' => 'Pengiriman',
            'breadcrumbs' => [
                [
                    'name' => 'Transaksi',
                    'route' => '#',
                ],
                [
                    'name' => 'Pengiriman',
                    'route' => 'order_sent.index',
                ],
            ],
        ],
    ],
    'order_arsip' => [
        'index' => [
            'title' => 'Arsip',
            'breadcrumbs' => [
                [
                    'name' => 'Transaksi',
                    'route' => '#',
                ],
                [
                    'name' => 'Arsip',
                    'route' => 'order_arsip.index',
                ],
            ],
        ],
    ],
    'import' => [
        'index' => [
            'title' => 'Import',
            'breadcrumbs' => [
                [
                    'name' => 'Log',
                    'route' => '#',
                ],
                [
                    'name' => 'Import',
                    'route' => 'log.import.index',
                ],
            ],
        ],
    ],
];
