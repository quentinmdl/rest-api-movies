<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;
use App\Http\Requests\StoreOrUpdateCategoryRequest;

class CategoryController extends Controller
{
    
    private CategoryRepositoryInterface $categoryRepositoryInterface;
    
    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
    **/
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     operationId="getCategoriesList",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     description="Returns list of categories with optional pagination",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of categories per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="No categories found"
     *     )
     * )
     */
    public function index($perPage = 10)
    {
        $perPage = request()->query('perPage', $perPage);

        $data = $this->categoryRepositoryInterface->index($perPage);

        if ($data->isEmpty()) {
            return ResponseClass::sendResponse([], 'No categories found', 404);
        }

        return ResponseClass::sendResponse(CategoryResource::collection($data),'',200, true);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/search",
     *     operationId="searchCategories",
     *     tags={"Categories"},
     *     summary="Search categories by name",
     *     description="Returns a list of categories that match the search criteria",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query for category name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No categories found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function search()
    {
        $query = request()->query('query');
        if(!$query){
            return ResponseClass::sendResponse("", "Invalid query, missing query parameter {query}", 422);
        }

        $categories = $this->categoryRepositoryInterface->search($query);
        if (!$categories) {
            return ResponseClass::sendResponse([], "No categories found", 404);
        }

        return ResponseClass::sendResponse(CategoryResource::collection($categories), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/categories",
     *     operationId="storeCategory",
     *     tags={"Categories"},
     *     summary="Store a new category",
     *     description="Stores a new category and returns the category data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data",
     *         @OA\JsonContent(
     *             required={"name", "description", "release_date", "rating"},
     *             @OA\Property(property="name", type="string", example="A new beginning"),
     *             @OA\Property(property="description", type="string", example="It's the story of a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rating", type="number", format="float", example=5),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A new beginning"),
     *             @OA\Property(property="description", type="string", example="It's the story of a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rating", type="number", format="float", example=5)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(StoreOrUpdateCategoryRequest $request)
    {
        // if ($request->fails()) {
        //     return ResponseClass::sendResponse([], $request->errors(), 422);
        // }

        DB::beginTransaction();
        try{
            $category = $this->categoryRepositoryInterface->store($request->all());

            DB::commit();
            return ResponseClass::sendResponse(new CategoryResource($category),'Category created successfully',201);

        }catch(\Exception $ex){
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     operationId="getCategoryById",
     *     tags={"Categories"},
     *     summary="Get category by ID",
     *     description="Returns a single category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A new beginning"),
     *             @OA\Property(property="description", type="string", example="It's the story of a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rating", type="number", format="float", example=8)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id)
    {
        $category = $this->categoryRepositoryInterface->getById($id);
        if (!$category) {
            return ResponseClass::sendResponse(null, "Category not found", 404);
        }

        return ResponseClass::sendResponse(new CategoryResource($category),'',200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     summary="Update an existing category",
     *     description="Updates and returns a category data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data",
     *         @OA\JsonContent(
     *             required={"name", "description", "release_date", "rating"},
     *             @OA\Property(property="name", type="string", example="A new beginning"),
     *             @OA\Property(property="description", type="string", example="It's the story of a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rating", type="number", format="float", example=8),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A new beginning"),
     *             @OA\Property(property="description", type="string", example="It's the story of a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rating", type="number", format="float", example=8)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
     */
    public function update(StoreOrUpdateCategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try{

            $category = $this->categoryRepositoryInterface->getById($id);
            if (!$category) {
                return ResponseClass::sendResponse(null, "Category not found", 404);
            }

            $updated = $this->categoryRepositoryInterface->update($request->all(),$id);

            if ($updated) {
                $responseCode = 200;
                $responseMessage = 'Category updated successfully';
            }  else {
                $responseCode = 422;
                $responseMessage = 'Unable to process the request';
            }
            DB::commit();
            return ResponseClass::sendResponse($updated ?? null,$responseMessage,$responseCode);

        }catch(\Exception $ex){
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     description="Deletes a category and returns a success message",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of category to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting category"
     *     )
     * )
     */
    public function destroy($id)
    {
        $category = $this->categoryRepositoryInterface->getById($id);
        if (!$category) {
            return ResponseClass::sendResponse(null, "Category not found", 404);
        }

        $deleted = $this->categoryRepositoryInterface->delete($id);
        if(!$deleted) {
            return ResponseClass::sendResponse(null, 'Error deleting category', 500);
        }

        return ResponseClass::sendResponse(null,'Category deleted successfully',204);
    }
}
