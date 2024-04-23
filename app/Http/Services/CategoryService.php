<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class CategoryService {

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



    public function getCategories($page = 1) {
        try {
            $items = [];

            $response = $this->client->request('GET', env('API_DBMOVIE_URL').'/genre/movie/list?language=en', [
                'headers' => [
                    'Authorization' => 'Bearer '.env('API_DBMOVIE_KEY'),
                    'accept' => 'application/json',
                ],
            ]);
    
            if ($response->getStatusCode() != 200 && $response->getStatusCode() != 401) {
                throw new \Exception("Erreur inattendue: " . $response->getStatusCode());
            }
            $categories = json_decode($response->getBody()->getContents(), true);

            foreach ($categories['genres'] as $category) {
                $items[] = [
                    'name' => $category['name'],
                ];
            }


        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Exception("Erreur lors de la requête: " . $e->getMessage());
        }
        
        return $items;
    }


}



?>
