<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // تشغيل جميع الـ Seeders بالترتيب الصحيح
        $this->call([
            GovernorateSeeder::class,      // المحافظات أولاً
            WilayatSeeder::class,          // الولايات ثانياً
            ServiceTypeSeeder::class,      // أنواع الخدمات ثالثاً
            TouristSiteSeeder::class,      // المواقع السياحية رابعاً
            TouristImageSeeder::class,     // صور المواقع السياحية خامساً
            TouristServiceSeeder::class,   // الخدمات السياحية أخيراً
        ]);
    }
}
