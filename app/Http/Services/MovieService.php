<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class MovieService {


    public function __construct() {
        try {
            $this->client = new \GuzzleHttp\Client();
            $this->init($this->client);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la création du client Guzzle: " . $e->getMessage());
        }

    }

    public function init($client) {
        try {
            $response = $client->request('GET', env('API_DBMOVIE_URL').'authentication', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('API_DBMOVIE_KEY'),
                    'accept' => 'application/json',
                ],
            ]);
            if ($response->getStatusCode() != 200 && $response->getStatusCode() != 401) {
                throw new \Exception("Erreur inattendue: " . $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception("Erreur lors de la requête: " . $e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), true);
    }



    public function getMovies($page = 1) {
        try {
            $items = [];

            $response = $this->client->request('GET', env('API_DBMOVIE_URL').'movie/changes?page='.$page, [
                'headers' => [
                    'Authorization' => 'Bearer '.env('API_DBMOVIE_KEY'),
                    'accept' => 'application/json',
                ],
            ]);
    
            if ($response->getStatusCode() != 200 && $response->getStatusCode() != 401) {
                throw new \Exception("Erreur inattendue: " . $response->getStatusCode());
            }
            $movies = json_decode($response->getBody()->getContents(), true);

            if(!empty($movies['results'])) {
                foreach($movies['results'] as $movie) {
                    try {
                        $response = $this->client->request('GET', env('API_DBMOVIE_URL').'movie/'.$movie['id'], [
                            'headers' => [
                                'Authorization' => 'Bearer '.env('API_DBMOVIE_KEY'),
                                'accept' => 'application/json',
                            ],
                        ]);
                        if ($response->getStatusCode() == 200) {
                            $movieDetails = json_decode($response->getBody()->getContents(), true);
                            $items[] = $movieDetails;
                        } elseif ($response->getStatusCode() == 404) {
                            throw new \Exception("Film non trouvé avec l'ID externe: " . $movie['id']);
                        }
                    } catch (\GuzzleHttp\Exception\ClientException $e) {
                        continue;
                        // throw new \Exception("Erreur lors de la recherche du film: " . $e->getMessage());
                    }
                }
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception("Erreur lors de la requête: " . $e->getMessage());
        }
        
        return $items;
    }


}



?>
