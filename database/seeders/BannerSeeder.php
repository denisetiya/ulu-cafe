<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'image_path' => 'https://images.unsplash.com/photo-1544025162-d76690b60944?q=80&w=2074&auto=format&fit=crop', // Meeting/Cafe vibe
            'title' => 'Nikmati Suasana Nyaman',
            'description' => 'Tempat terbaik untuk nongkrong dan bekerja dengan kopi pilihan.',
            'is_active' => true,
        ]);

        Banner::create([
            'image_path' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop', // Food vibe
            'title' => 'Menu Spesial Hari Ini',
            'description' => 'Cicipi hidangan lezat kami yang dibuat dengan bahan berkualitas.',
            'is_active' => true,
        ]);
    }
}
