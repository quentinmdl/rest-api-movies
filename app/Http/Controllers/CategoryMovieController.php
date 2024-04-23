<?php

namespace App\Http\Controllers;

use App\Models\CategoryMovie;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\CategoryMovieResource;
use App\Http\Requests\StoreCategoryMovieRequest;
use App\Http\Requests\UpdateCategoryMovieRequest;
use App\Interfaces\CategoryMovieRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;

class CategoryMovieController extends Controller
{
    
    private CategoryMovieRepositoryInterface $categoryMovieRepositoryInterface;
    
    public function __construct(CategoryMovieRepositoryInterface $categoryMovieRepositoryInterface)
    {
        $this->categoryMovieRepositoryInterface = $categoryMovieRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
    **/
    /**
     * @OA\Get(
     *     path="/api/categoriesMovies",
     *     operationId="getCategoriesMoviesList",
     *     tags={"CategoriesMovies"},
     *     summary="Get list of categoriesMovies",
     *     description="Returns list of categoriesMovies",
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
     *         description="No categoriesMovies found"
     *     )
     * )
     */
    public function index()
    {
        $data = $this->categoryMovieRepositoryInterface->index();
        if ($data->isEmpty()) {
            return ResponseClass::sendResponse([], 'No categoriesMovies found', 500);
        }

        return ResponseClass::sendResponse(CategoryMovieResource::collection($data),'',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/categoriesMovies",
     *     operationId="storeCategoryMovie",
     *     tags={"CategoriesMovies"},
     *     summary="Store a new categoryMovie",
     *     description="Stores a new categoryMovie and returns the categoryMovie data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="CategoryMovie data",
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
    public function store(StoreCategoryMovieRequest $request)
    {
        DB::beginTransaction();
        try{
            $categoryMovie = $this->categoryMovieRepositoryInterface->store($request->all());

            DB::commit();
            return ResponseClass::sendResponse(new CategoryMovieResource($categoryMovie),'CategoryMovie created successfully',201);

        }catch(\Exception $ex){
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/categoriesMovies/{id}",
     *     operationId="getCategoryMovieById",
     *     tags={"CategoriesMovies"},
     *     summary="Get categoryMovie by ID",
     *     description="Returns a single categoryMovie",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of categoryMovie to return",
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
     *         description="CategoryMovie not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id)
    {
        $categoryMovie = $this->categoryMovieRepositoryInterface->getById($id);
        if (!$categoryMovie) {
            $responseCode = 404;
            $responseMessage = 'CategoryMovie not found';
            return ResponseClass::sendResponse("",$responseMessage,$responseCode);
        }

        return ResponseClass::sendResponse(new CategoryMovieResource($categoryMovie),'',200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/categoriesMovies/{id}",
     *     operationId="updateCategoryMovie",
     *     tags={"CategoriesMovies"},
     *     summary="Update an existing categoryMovie",
     *     description="Updates and returns a categoryMovie data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of categoryMovie that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="CategoryMovie data",
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
     *         description="CategoryMovie updated",
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
     *         description="CategoryMovie not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
     */
    public function update(UpdateCategoryMovieRequest $request, $id)
    {
        DB::beginTransaction();
        try{

            $categoryMovie = $this->categoryMovieRepositoryInterface->getById($id);
            if (!$categoryMovie) {
                $responseCode = 404;
                $responseMessage = 'CategoryMovie not found';
                return ResponseClass::sendResponse("",$responseMessage,$responseCode);
            }

            $updated = $this->categoryMovieRepositoryInterface->update($request->all(),$id);

            if ($updated) {
                $responseCode = 200;
                $responseMessage = 'CategoryMovie updated successfully';
            }  else {
                $responseCode = 422;
                $responseMessage = 'Unable to process the request';
            }
            DB::commit();
            return ResponseClass::sendResponse($categoryMovie ?? null,$responseMessage,$responseCode);

        }catch(\Exception $ex){
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/categoriesMovies/{id}",
     *     operationId="deleteCategoryMovie",
     *     tags={"CategoriesMovies"},
     *     summary="Delete a categoryMovie",
     *     description="Deletes a categoryMovie and returns a success message",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of categoryMovie to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="CategoryMovie deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="CategoryMovie not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting categoryMovie"
     *     )
     * )
     */
    public function destroy($id)
    {
        $categoryMovie = $this->categoryMovieRepositoryInterface->getById($id);
        if (!$categoryMovie) {
            $responseCode = 404;
            $responseMessage = 'CategoryMovie not found';
            return ResponseClass::sendResponse("",$responseMessage,$responseCode);
        }

        $deleted = $this->categoryMovieRepositoryInterface->delete($id);
        if(!$deleted) {
            return ResponseClass::sendResponse(null, 'Error deleting categoryMovie', 500);
        }

        return ResponseClass::sendResponse(null,'CategoryMovie deleted successfully',204);
    }
}
