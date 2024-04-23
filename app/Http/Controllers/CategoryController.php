<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;

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
     *     description="Returns list of categories",
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
    public function index()
    {
        $data = $this->categoryRepositoryInterface->index();
        if ($data->isEmpty()) {
            return ResponseClass::sendResponse([], 'No categories found', 500);
        }

        return ResponseClass::sendResponse(CategoryResource::collection($data),'',200);
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
     *             @OA\Property(property="name", type="string", example="Un nouveau départ"),
     *             @OA\Property(property="description", type="string", example="C'est l'histoire d'un nouveau départ..."),
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
     *             @OA\Property(property="name", type="string", example="Un nouveau départ"),
     *             @OA\Property(property="description", type="string", example="C'est l'histoire d'un nouveau départ..."),
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
    public function store(StoreCategoryRequest $request)
    {
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
     *             @OA\Property(property="name", type="string", example="Un nouveau départ"),
     *             @OA\Property(property="description", type="string", example="C'est l'histoire d'un nouveau départ..."),
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
            $responseCode = 404;
            $responseMessage = 'Category not found';
            return ResponseClass::sendResponse("",$responseMessage,$responseCode);
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
     *             @OA\Property(property="name", type="string", example="Un nouveau départ"),
     *             @OA\Property(property="description", type="string", example="C'est l'histoire d'un nouveau départ..."),
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
     *             @OA\Property(property="name", type="string", example="Un nouveau départ"),
     *             @OA\Property(property="description", type="string", example="C'est l'histoire d'un nouveau départ..."),
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
    public function update(UpdateCategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try{

            $category = $this->categoryRepositoryInterface->getById($id);
            if (!$category) {
                $responseCode = 404;
                $responseMessage = 'Category not found';
                return ResponseClass::sendResponse("",$responseMessage,$responseCode);
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
            return ResponseClass::sendResponse($category ?? null,$responseMessage,$responseCode);

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
            $responseCode = 404;
            $responseMessage = 'Category not found';
            return ResponseClass::sendResponse("",$responseMessage,$responseCode);
        }

        $deleted = $this->categoryRepositoryInterface->delete($id);
        if(!$deleted) {
            return ResponseClass::sendResponse(null, 'Error deleting category', 500);
        }

        return ResponseClass::sendResponse(null,'Category deleted successfully',204);
    }
}
