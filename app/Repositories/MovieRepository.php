<?php
namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
   public function index($perPage){
      return Movie::paginate($perPage);
   }

   public function search($query){
      $movies = Movie::where('name', 'LIKE', "%{$query}%")
      ->orWhere('description', 'LIKE', "%{$query}%")
      ->get();

      return !$movies->isEmpty() ? $movies : null;
   }

   public function getById($id){
      return Movie::find($id);
   }

   public function store(array $data){
      return Movie::create($data);
   }

   public function update(array $data,$id){
      $movie = Movie::find($id);
      $movie->update($data);
      return $movie;
   }
   
   public function delete($id){
      return Movie::destroy($id);
   }
}

