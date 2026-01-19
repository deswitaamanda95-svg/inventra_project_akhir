<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin (Email sesuai request Anda sebelumnya)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Buat Kategori Modern
        $cat1 = Category::create(['name' => 'Workstation', 'description' => 'Laptops and High-end PCs']);
        $cat2 = Category::create(['name' => 'Photography', 'description' => 'Cameras and Studio Gear']);
        $cat3 = Category::create(['name' => 'Peripherals', 'description' => 'Keyboards and Monitors']);

        // 3. Buat Item (Barang) Modern
        Item::create([
            'category_id' => $cat1->id,
            'name' => 'MacBook Pro M3 Max 16"',
            'description' => 'Space Black, 64GB RAM, 1TB SSD',
            'quantity' => 15, // Good Assets
            'quantity_repair' => 2, // In Repair
            'quantity_broken' => 1, // Broken Assets
            'condition' => 'Good',
            'room' => 'Development Lab'
        ]);

        Item::create([
            'category_id' => $cat2->id,
            'name' => 'Sony A7 IV Mirrorless',
            'description' => 'Body Only with 4K Video support',
            'quantity' => 5,
            'quantity_repair' => 1,
            'quantity_broken' => 0,
            'condition' => 'Good',
            'room' => 'Creative Studio'
        ]);

        Item::create([
            'category_id' => $cat3->id,
            'name' => 'Keychron Q1 Pro',
            'description' => 'Mechanical Keyboard Wireless Carbon Grey',
            'quantity' => 20,
            'quantity_repair' => 0,
            'quantity_broken' => 2,
            'condition' => 'Good',
            'room' => 'Open Space Office'
        ]);
    }
}