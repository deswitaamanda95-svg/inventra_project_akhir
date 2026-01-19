<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\MaintenanceLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InventraSeeder extends Seeder
{
    public function run()
    {
        // 1. KATEGORI MODERN
        $categories = [
            ['name' => 'Workstation', 'description' => 'High-end laptops and desktop units'],
            ['name' => 'Peripherals', 'description' => 'Keyboards, Mice, and Monitors'],
            ['name' => 'Studio Gear', 'description' => 'Cameras, Lighting, and Audio'],
        ];
        foreach ($categories as $cat) { Category::create($cat); }

        // 2. USER DENGAN NAMA MODERN
        $admin = User::create([
            'name' => 'Alex Rivera',
            'email' => 'alex.admin@inventra.io',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Jordan Kenzie',
            'email' => 'jordan@inventra.io',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Viona Clarissa',
            'email' => 'viona@inventra.io',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 3. ITEM GADGET TERKINI
        $items = [
            [
                'category_id' => 1,
                'name' => 'MacBook Pro M3 Max 16"',
                'description' => 'Space Black, 64GB RAM, 1TB SSD',
                'quantity' => 12,
                'quantity_repair' => 1,
                'condition' => 'Good',
                'room' => 'Development Lab',
            ],
            [
                'category_id' => 2,
                'name' => 'Keychron Q1 Pro Wireless',
                'description' => 'Mechanical Keyboard - Carbon Grey',
                'quantity' => 15,
                'condition' => 'Good',
                'room' => 'Open Space Area',
            ],
            [
                'category_id' => 3,
                'name' => 'Sony A7 IV + 24-70mm GM II',
                'description' => 'Full-frame Mirrorless Camera Kit',
                'quantity' => 4,
                'quantity_repair' => 1,
                'condition' => 'Good',
                'room' => 'Creative Studio',
            ],
            [
                'category_id' => 2,
                'name' => 'Logitech MX Master 3S',
                'description' => 'Ergonomic Wireless Mouse - Pale Grey',
                'quantity' => 20,
                'condition' => 'Good',
                'room' => 'Design Wing',
            ],
        ];
        foreach ($items as $it) { Item::create($it); }

        // 4. RIWAYAT PEMINJAMAN REALISTIS
        // Jordan meminjam MacBook (Selesai)
        Loan::create([
            'item_id' => 1,
            'user_id' => $user1->id,
            'loan_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->subDays(3),
            'return_date' => Carbon::now()->subDays(3),
            'status' => 'returned',
            'approved_by' => $admin->id,
        ]);

        // Viona meminjam Kamera Sony (Sedang Berjalan)
        Loan::create([
            'item_id' => 3,
            'user_id' => $user2->id,
            'loan_date' => Carbon::now()->subDays(2),
            'due_date' => Carbon::now()->addDays(5),
            'status' => 'borrowed',
            'approved_by' => $admin->id,
        ]);

        // 5. LOG MAINTENANCE
        MaintenanceLog::create([
            'item_id' => 3,
            'issue_detail' => 'Sensor cleaning and firmware update',
            'technician_name' => 'Sony Center - Tech Support',
            'start_date' => Carbon::now()->subDays(1),
            'estimated_finish' => Carbon::now()->addDays(3),
            'status' => 'ongoing',
        ]);
    }
}