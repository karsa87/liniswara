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
    'customer_school' => [
        'index' => [
            'title' => 'Sekolah Agen',
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
                    'name' => 'Sekolah Agen',
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
    'prerestock' => [
        'index' => [
            'title' => 'Naik Cetak',
            'breadcrumbs' => [
                [
                    'name' => 'Naik Cetak',
                    'route' => '#',
                ],
                [
                    'name' => 'Naik Cetak',
                    'route' => 'prerestock.index',
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
    'return_order' => [
        'index' => [
            'title' => 'Retur',
            'breadcrumbs' => [
                [
                    'name' => 'Transaksi',
                    'route' => '#',
                ],
                [
                    'name' => 'Retur',
                    'route' => 'return_order.index',
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
    'writer' => [
        'index' => [
            'title' => 'Penulis',
            'breadcrumbs' => [
                [
                    'name' => 'Penulis',
                    'route' => '#',
                ],
                [
                    'name' => 'Penulis',
                    'route' => 'writer.index',
                ],
            ],
        ],
    ],
    'area' => [
        'index' => [
            'title' => 'Area',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Area',
                    'route' => '#',
                ],
            ],
        ],
    ],
    'area_school' => [
        'index' => [
            'title' => 'Area Sekolah',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Area',
                    'route' => 'area.index',
                ],
                [
                    'name' => 'Area Sekolah',
                    'route' => '#',
                ],
            ],
        ],
    ],
    'school' => [
        'index' => [
            'title' => 'Sekolah',
            'breadcrumbs' => [
                [
                    'name' => 'Master Data',
                    'route' => '#',
                ],
                [
                    'name' => 'Sekolah',
                    'route' => 'school.index',
                ],
            ],
        ],
    ],
];
