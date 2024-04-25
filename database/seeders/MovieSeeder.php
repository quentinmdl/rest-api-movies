<?php

namespace Database\Seeders;

use Carbon\Carbon;

use App\Models\Media;
use App\Models\Movie;
use App\Models\Category;
use App\Models\MediaType;
use App\Models\CategoryMovie;
use Illuminate\Database\Seeder;
use App\Http\Services\MovieService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovieSeeder extends Seeder
{

    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = $this->movieService->getMovies();

        foreach ($movies as $movie) {  

            $media = Media::create([
                'media_path' => null,
                'media_url' =>  "https://plus.unsplash.com/premium_photo-1666544989783-13fc7091024f?q=80&w=2160&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                'media_type' => MediaType::where('name','poster')->first()->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            

            $created = Movie::create([
                'name' => isset($movie['name']) ? $movie['name'] : (isset($movie['original_title']) ? $movie['original_title'] : 'NO TITLE'),
                'description' => $movie['overview'],
                'media_id' => $media->id ?? null,
                'release_date' => date("Y-m-d", strtotime($movie['release_date'])),
                'rating' => intval(round($movie['vote_average'], 0)),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            if(isset($movie['genres']) && !empty($movie['genres'])){
                foreach ($movie['genres'] as $category) {
                    CategoryMovie::create([
                        'movie_id' => $created['id'],
                        'category_id' => Category::where('name',$category['name'])->first()->id
                    ]);
                }
            }
        }
    }
}
