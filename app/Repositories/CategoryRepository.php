<?php
namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
   public function index($perPage){
      return Category::paginate($perPage);
   }

   public function search($query){
      $categories = Category::where('name', 'LIKE', "%{$query}%")
      ->get();

      return !$categories->isEmpty() ? $categories : null;
   }

   public function getById($id){
      return Category::find($id);
   }

   public function store(array $data){
      return Category::create($data);
   }

   public function update(array $data,$id){
      $category = Category::find($id);
      $category->update($data);
      return $category;
   }

   public function delete($id){
      return Category::destroy($id);
   }
} 