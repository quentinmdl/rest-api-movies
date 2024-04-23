<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function index($perPage);
    public function search($query);
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}