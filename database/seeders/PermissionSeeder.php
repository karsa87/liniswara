<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private $rolePermissions;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->rolePermissions = collect();

        $this->createPermissionRole();
        $this->createPermissionUser();
        $this->createPermissionBranch();
        $this->createPermissionArea();
        $this->createPermissionSetting();
        $this->createPermissionSupplier();
        $this->createPermissionCollector();
        $this->createPermissionCustomer();
        $this->createPermissionCustomerAddress();
        $this->createPermissionExpedition();
        $this->createPermissionCategory();
        $this->createPermissionProduct();
        $this->createPermissionRestock();
        $this->createPermissionPrerestock();
        $this->createPermissionPreorder();
        $this->createPermissionPreorderBook();
        $this->createPermissionLogStock();
        $this->createPermissionOrder();
        $this->createPermissionOrderSent();
        $this->createPermissionOrderArsip();
        $this->createPermissionWriter();

        $this->syncPermissionRole();
    }

    private function createPermissionRole()
    {
        $permissions = [
            [
                'name' => 'Role - Tambah',
                'key' => 'role-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - Edit',
                'key' => 'role-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - View',
                'key' => 'role-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - Delete',
                'key' => 'role-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionUser()
    {
        $permissions = [
            [
                'name' => 'User - Tambah',
                'key' => 'user-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - Edit',
                'key' => 'user-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - View',
                'key' => 'user-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - Delete',
                'key' => 'user-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionBranch()
    {
        $permissions = [
            [
                'name' => 'Gudang - Tambah',
                'key' => 'branch-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - Edit',
                'key' => 'branch-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - View',
                'key' => 'branch-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - Delete',
                'key' => 'branch-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionArea()
    {
        $permissions = [
            [
                'name' => 'Area - Tambah',
                'key' => 'area-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Area - Edit',
                'key' => 'area-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Area - View',
                'key' => 'area-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Area - Delete',
                'key' => 'area-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionSetting()
    {
        $permissions = [
            [
                'name' => 'Setting - Edit',
                'key' => 'setting-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Setting - View',
                'key' => 'setting-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Setting - Delete',
                'key' => 'setting-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionSupplier()
    {
        $permissions = [
            [
                'name' => 'Pemasok - Tambah',
                'key' => 'supplier-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pemasok - Edit',
                'key' => 'supplier-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pemasok - View',
                'key' => 'supplier-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pemasok - Delete',
                'key' => 'supplier-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionCollector()
    {
        $permissions = [
            [
                'name' => 'Penagih - Tambah',
                'key' => 'collector-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penagih - Edit',
                'key' => 'collector-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penagih - View',
                'key' => 'collector-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penagih - Delete',
                'key' => 'collector-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionCustomer()
    {
        $permissions = [
            [
                'name' => 'Agen - Tambah',
                'key' => 'customer-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Agen - Edit',
                'key' => 'customer-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Agen - View',
                'key' => 'customer-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Agen - Delete',
                'key' => 'customer-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionCustomerAddress()
    {
        $permissions = [
            [
                'name' => 'Alamat Agen - Tambah',
                'key' => 'customer_address-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Alamat Agen - Edit',
                'key' => 'customer_address-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Alamat Agen - View',
                'key' => 'customer_address-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Alamat Agen - Delete',
                'key' => 'customer_address-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionExpedition()
    {
        $permissions = [
            [
                'name' => 'Ekspedisi - Tambah',
                'key' => 'expedition-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Ekspedisi - Edit',
                'key' => 'expedition-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Ekspedisi - View',
                'key' => 'expedition-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Ekspedisi - Delete',
                'key' => 'expedition-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionCategory()
    {
        $permissions = [
            [
                'name' => 'Kategori - Tambah',
                'key' => 'category-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Kategori - Edit',
                'key' => 'category-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Kategori - View',
                'key' => 'category-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Kategori - Delete',
                'key' => 'category-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionWriter()
    {
        $permissions = [
            [
                'name' => 'Penulis - Tambah',
                'key' => 'writer-add',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penulis - Edit',
                'key' => 'writer-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penulis - View',
                'key' => 'writer-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Penulis - Delete',
                'key' => 'writer-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionProduct()
    {
        $permissions = [
            [
                'name' => 'Produk - Tambah',
                'key' => 'product-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Produk - Edit',
                'key' => 'product-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Produk - View',
                'key' => 'product-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Produk - View Detail',
                'key' => 'product-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Produk - Delete',
                'key' => 'product-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionRestock()
    {
        $permissions = [
            [
                'name' => 'Restock - Tambah',
                'key' => 'restock-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Restock - View',
                'key' => 'restock-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Restock - View Detail',
                'key' => 'restock-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Restock - Delete',
                'key' => 'restock-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionPrerestock()
    {
        $permissions = [
            [
                'name' => 'Prerestock - Tambah',
                'key' => 'prerestock-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Prerestock - View',
                'key' => 'prerestock-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Prerestock - Edit',
                'key' => 'prerestock-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Prerestock - View Detail',
                'key' => 'prerestock-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Prerestock - Delete',
                'key' => 'prerestock-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Prerestock - Migrate',
                'key' => 'prerestock-migrate',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionPreorder()
    {
        $permissions = [
            [
                'name' => 'Preorder - Tambah',
                'key' => 'preorder-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Edit',
                'key' => 'preorder-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - View',
                'key' => 'preorder-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - View Detail',
                'key' => 'preorder-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Delete',
                'key' => 'preorder-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Update Diskon',
                'key' => 'preorder-update_discount',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Update Status',
                'key' => 'preorder-update_status',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Migrasi ke Ready',
                'key' => 'preorder-migrate_ready',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Print Purchasing Order',
                'key' => 'preorder-print_po',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Print Faktur',
                'key' => 'preorder-print_faktur',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Track',
                'key' => 'preorder-track',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Preorder - Export',
                'key' => 'preorder-export',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionPreorderBook()
    {
        $permissions = [
            [
                'name' => 'Preorder Book - View',
                'key' => 'preorder_book-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionLogStock()
    {
        $permissions = [
            [
                'name' => 'Log Stock - View',
                'key' => 'log_stock-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionOrder()
    {
        $permissions = [
            [
                'name' => 'Order - Tambah',
                'key' => 'order-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Edit',
                'key' => 'order-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - View',
                'key' => 'order-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - View Detail',
                'key' => 'order-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Delete',
                'key' => 'order-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Update Diskon',
                'key' => 'order-update_discount',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Update Status',
                'key' => 'order-update_status',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Track',
                'key' => 'order-track',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Export',
                'key' => 'order-export',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Print Surat Jalan',
                'key' => 'order-print_sent_letter',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Print Faktur',
                'key' => 'order-print_faktur',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Print Purchase Order',
                'key' => 'order-print_po',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Order - Print Address',
                'key' => 'order-print_address',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionOrderSent()
    {
        $permissions = [
            [
                'name' => 'Pengiriman - View',
                'key' => 'order_sent-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - View Detail',
                'key' => 'order_sent-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Update Diskon',
                'key' => 'order_sent-update_discount',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Update Status',
                'key' => 'order_sent-update_status',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Track',
                'key' => 'order_sent-track',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Print Surat Jalan',
                'key' => 'order_sent-print_sent_letter',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Print Faktur',
                'key' => 'order_sent-print_faktur',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Print Purchase Order',
                'key' => 'order_sent-print_po',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Pengiriman - Print Address',
                'key' => 'order_sent-print_address',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionOrderArsip()
    {
        $permissions = [
            [
                'name' => 'Arsip - View',
                'key' => 'order_arsip-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Arsip - View Detail',
                'key' => 'order_arsip-view_detail',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Arsip - Print Faktur',
                'key' => 'order_arsip-print_faktur',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Arsip - Print Purchase Order',
                'key' => 'order_arsip-print_po',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermission($permissions)
    {
        foreach ($permissions as $inputPermission) {
            $permission = Permission::updateOrCreate(
                [
                    'key' => $inputPermission['key'],
                ],
                [
                    'name' => $inputPermission['name'],
                    'key' => $inputPermission['key'],
                ]
            );

            foreach ($inputPermission['role-slug'] as $roleSlug) {
                $this->rolePermissions->push([
                    'role_slug' => $roleSlug,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }

    private function syncPermissionRole()
    {
        foreach ($this->rolePermissions->groupBy('role_slug') as $roleSlug => $permissions) {
            $role = Role::whereSlug($roleSlug)->withoutGlobalScope('exclude_developer')->first();
            if ($role) {
                $role->permissions()->syncWithoutDetaching(
                    collect($permissions)->pluck('permission_id')->toArray()
                );
            }
        }
    }
}
