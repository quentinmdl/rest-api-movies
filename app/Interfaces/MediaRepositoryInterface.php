<?php

namespace App\Interfaces;

interface MediaRepositoryInterface
{
    public function index($perPage);
    public function getById($id);
    public function delete($id);
}
