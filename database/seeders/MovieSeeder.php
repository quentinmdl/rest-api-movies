<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Movie;

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
            Movie::create([
                'name' => isset($movie['title']) ? $movie['title'] : (isset($movie['original_title']) ? $movie['original_title'] : 'NO TITLE'),
                'description' => $movie['overview'],
                'release_date' => date("Y-m-d", strtotime($movie['release_date'])),
                'rating' => intval(round($movie['vote_average'], 0)),
            ]);
        }
    }
}
