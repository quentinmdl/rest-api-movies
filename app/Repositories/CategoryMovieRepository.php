<?php
namespace App\Repositories;

use App\Models\CategoryMovie;
use App\Interfaces\CategoryMovieRepositoryInterface;
class CategoryMovieRepository implements CategoryMovieRepositoryInterface
{
    public function index(){
        return CategoryMovie::all();
    }

    public function getById($id){
       return CategoryMovie::find($id);
    }

    public function store(array $data){
       return CategoryMovie::create($data);
    }

    public function update(array $data,$id){
       return CategoryMovie::whereId($id)->update($data);
    }

    public function delete($id){
        CategoryMovie::destroy($id);
    }
} 