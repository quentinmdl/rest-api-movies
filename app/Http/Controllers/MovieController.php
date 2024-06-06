<?php

namespace App\Http\Controllers;

use App\Models\Media;

use App\Models\Movie;
use Illuminate\Http\Request;

use App\Helpers\MediaUploader;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MovieResource;
use App\Http\Requests\StoreOrUpdateMovieRequest;
use App\Interfaces\MovieRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;

/**
 * @OA\Info(title="Movies - API", version="1.0")
 */ 
class MovieController extends Controller
{
    
    private MovieRepositoryInterface $movieRepositoryInterface;
    
    public function __construct(
        MovieRepositoryInterface $movieRepositoryInterface, 
        MediaUploader $mediaUploader
    )
    {
        $this->movieRepositoryInterface = $movieRepositoryInterface;
        $this->mediaUploader = $mediaUploader;
    }

    /**
     * Displays a list of resources.
    **/
    /**
     * @OA\Get(
     *     path="/api/movies",
     *     operationId="getMoviesList",
     *     tags={"Movies"},
     *     summary="Gets the list of movies",
     *     description="Returns the list of movies with optional pagination",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of movies per page",
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
     *         description="No movie(s) found"
     *     )
     * )
     */
    public function index($perPage = 10)
    {
        $perPage = request()->query('perPage', $perPage);

        $data = $this->movieRepositoryInterface->index($perPage);

        if ($data->isEmpty()) {
            return ResponseClass::sendResponse([], 'No movies found', 204);
        }
        
        return ResponseClass::sendResponse(MovieResource::collection($data),'',200, true);
    }

    /**
     * @OA\Get(
     *     path="/api/movies/search",
     *     operationId="searchMovies",
     *     tags={"Movies"},
     *     summary="Search movies by name or description",
     *     description="Returns a list of movies that match the search criteria",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query for movie name or description",
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
     *         description="No movies found"
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
            return ResponseClass::sendResponse(null, "Invalid query, missing query parameter {query}", 422);
        }

        $movies = $this->movieRepositoryInterface->search($query);
        if (!$movies) {
            return ResponseClass::sendResponse([], "No movies found", 404);
        }

        return ResponseClass::sendResponse(MovieResource::collection($movies), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/movies",
     *     operationId="storeMovie",
     *     tags={"Movies"},
     *     summary="Store a new movie",
     *     description="Stores a new movie and returns the movie data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Movie data",
     *         @OA\JsonContent(
     *             required={"name", "description", "release_date", "rating"},
     *             @OA\Property(property="name", type="string", example="A New Beginning"),
     *             @OA\Property(property="description", type="string", example="It's a story about a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rate", type="number", format="int", example=5),
     *             @OA\Property(property="duration", type="number", format="int", example=8)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A New Beginning"),
     *             @OA\Property(property="description", type="string", example="It's a story about a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rate", type="number", format="int", example=5),
     *             @OA\Property(property="duration", type="number", format="int", example=8)
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
    public function store(StoreOrUpdateMovieRequest $request)
    {
        DB::beginTransaction();
        try{
            $data['media'] = $request->file('media');

            $path = $this->mediaUploader->upload($data['media'], 'poster');
            $media = Media::storeOrUpdateMedia($path, 'poster');

            $data = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'release_date' => $request->input('release_date'),
                'rate' => $request->input('rate'),
                'duration' => $request->input('duration'),
                'media_id' => $media->id ?? null
            ];

            $movie = $this->movieRepositoryInterface->store($data);

            DB::commit();
            return ResponseClass::sendResponse(new MovieResource($movie),'Movie created successfully',201);

        }catch(\Exception $ex){
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/movies/{id}",
     *     operationId="getMovieById",
     *     tags={"Movies"},
     *     summary="Get movie by ID",
     *     description="Returns a single movie",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of movie to return",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A New Beginning"),
     *             @OA\Property(property="description", type="string", example="It's a story about a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rate", type="number", format="int", example=8),
     *             @OA\Property(property="duration", type="number", format="int", example=8)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id)
    {
        $movie = $this->movieRepositoryInterface->getById($id);
        if (!$movie) {
            return ResponseClass::sendResponse(null, "Movie not found", 404);
        }

        return ResponseClass::sendResponse(new MovieResource($movie),'',200);
    }


    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/movies/{id}",
     *     operationId="updateMovie",
     *     tags={"Movies"},
     *     summary="Update an existing movie",
     *     description="Updates and returns a movie data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of movie that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Movie data",
     *         @OA\JsonContent(
     *             required={"name", "description", "release_date", "rating"},
     *             @OA\Property(property="name", type="string", example="A New Beginning"),
     *             @OA\Property(property="description", type="string", example="It's a story about a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rate", type="number", format="int", example=8),
     *             @OA\Property(property="duration", type="number", format="int", example=8)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movie updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", example=1),
     *             @OA\Property(property="name", type="string", example="A New Beginning"),
     *             @OA\Property(property="description", type="string", example="It's a story about a new beginning..."),
     *             @OA\Property(property="release_date", type="string", format="date", example="2021-09-15"),
     *             @OA\Property(property="rate", type="number", format="int", example=8),
     *             @OA\Property(property="duration", type="number", format="int", example=8)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     )
     * )
     */
    public function update(StoreOrUpdateMovieRequest $request, $id)
    {
        DB::beginTransaction();
        try{
            $movie = $this->movieRepositoryInterface->getById($id);
            if (!$movie) {
                return ResponseClass::sendResponse(null, "Movie not found", 404);
            }
            
            $currentPath = $movie->media->media_path;
            
            $data['media'] = $request->file('media');
            
            $fileName = $this->mediaUploader->upload($data['media'], 'poster', $currentPath);

            $media = Media::storeOrUpdateMedia($fileName, 'poster', $movie->media_id);

            $data = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'release_date' => $request->input('release_date'),
                'rate' => $request->input('rate'),
                'duration' => $request->input('duration'),
                'media_id' => $media->id ?? null
            ];

            $updated = $this->movieRepositoryInterface->update($data,$id);

            if ($updated) {
                $responseCode = 200;
                $responseMessage = 'Movie updated successfully';
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
     *     path="/api/movies/{id}",
     *     operationId="deleteMovie",
     *     tags={"Movies"},
     *     summary="Delete a movie",
     *     description="Deletes a movie and returns a success message",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of movie to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Movie deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting movie"
     *     )
     * )
     */
    public function destroy($id)
    {
        $movie = $this->movieRepositoryInterface->getById($id);
        if (!$movie) {
            return ResponseClass::sendResponse(null, "Movie not found", 404);
        }

        $deleted = $this->movieRepositoryInterface->delete($id);
        if(!$deleted) {
            return ResponseClass::sendResponse(null, 'Error deleting movie', 500);
        }

        return ResponseClass::sendResponse(null,'Movie deleted successfully',204);
    }
}
