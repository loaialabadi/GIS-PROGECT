<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
// إنشاء أول مستخدم أدمن
User::create([
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'), // غيّرها لكلمة قوية
    'role' => 'admin',
]);

// إضافة سارة لخدمة العملاء
User::create([
    'name' => 'سارة',
    'email' => 'sara@example.com',
    'password' => Hash::make('12345678'),
    'role' => 'customer_service',
]);

// إضافة لمياء للمراجعة
User::create([
    'name' => 'لمياء',
    'email' => 'lamia@example.com',
    'password' => Hash::make('12345678'),
    'role' => 'reviewer',
]);

// إضافة فاطمة للإدخال
User::create([
    'name' => 'فاطمة',
    'email' => 'fatma@example.com',
    'password' => Hash::make('12345678'),
    'role' => 'data_entry',
]);


    }
}
