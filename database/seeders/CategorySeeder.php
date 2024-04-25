<?php

namespace Database\Seeders;

use Carbon\Carbon;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Http\Services\CategoryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = $this->categoryService->getCategories();

        foreach ($categories as $category) {   
            Category::create([
                'name' => $category['name'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
