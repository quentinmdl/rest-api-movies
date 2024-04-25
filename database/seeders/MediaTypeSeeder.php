<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\MediaType;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $media_types = ['poster','image','video','file'];

        foreach($media_types as $media_type) {
            MediaType::create([
                'name' => $media_type,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
