<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Auction;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        Auction::factory()->create([
            'title'=>'Pack Koala adorable',
            'description'=>'Taza + llavero con imagen del koala.',
            'image_url'=>'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=1200',
            'starting_price'=>1000,
            'current_price'=>1000,
            'ends_at'=>now()->addHours(20)
        ]);
        Auction::factory()->create([
            'title'=>'Pack Tigre valiente',
            'description'=>'Taza + llavero con tigre.',
            'image_url'=>'https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=1200',
            'starting_price'=>1500,
            'current_price'=>1500,
            'ends_at'=>now()->addHours(26)
        ]);
    }
}
