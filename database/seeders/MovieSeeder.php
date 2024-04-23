<?php

namespace Database\Seeders;

use App\Models\Movie;

use App\Models\Category;
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
            $created = Movie::create([
                'name' => isset($movie['name']) ? $movie['name'] : (isset($movie['original_title']) ? $movie['original_title'] : 'NO TITLE'),
                'description' => $movie['overview'],
                'release_date' => date("Y-m-d", strtotime($movie['release_date'])),
                'rating' => intval(round($movie['vote_average'], 0)),
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
