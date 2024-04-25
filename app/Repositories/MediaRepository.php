<?php
namespace App\Repositories;

use App\Models\Media;
use App\Interfaces\MediaRepositoryInterface;

class MediaRepository implements MediaRepositoryInterface
{
   public function index($perPage){
      return Media::orderBy('id', 'asc')->paginate($perPage);
   }

   public function getById($id){
      return Media::find($id);
   }

   public function delete($id){
      return Media::destroy($id);
   }
}

