<?php
namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
   public function index(){
      return Movie::all();
   }

   public function getById($id){
      return Movie::find($id);
   }

   public function store(array $data){
      return Movie::create($data);
   }

   public function update(array $data,$id){
      return tap(Movie::whereId($id)->update($data));
   }
   
   public function delete($id){
      return Movie::destroy($id);
   }
}

