<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MediaResource;
use App\Interfaces\MediaRepositoryInterface;
use App\Classes\ApiResponseClass as ResponseClass;

class MediaController extends Controller
{

    private MediaRepositoryInterface $mediaRepositoryInterface;
    
    public function __construct(
        MediaRepositoryInterface $mediaRepositoryInterface
    )
    {
        $this->mediaRepositoryInterface = $mediaRepositoryInterface;
    }

    
    /**
     * Displays a list of resources.
    **/
    /**
     * @OA\Get(
     *     path="/api/medias",
     *     operationId="getMediaList",
     *     tags={"Media"},
     *     summary="Gets the list of media",
     *     description="Returns the list of media with optional pagination",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of medias per page",
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
     *         response=404,
     *         description="No medias found"
     *     )
     * )
     */
    public function index($perPage = 10)
    {
        $perPage = request()->query('perPage', $perPage);

        $data = $this->mediaRepositoryInterface->index($perPage);

        if ($data->isEmpty()) {
            return ResponseClass::sendResponse([], 'No medias found', 404);
        }
        
        return ResponseClass::sendResponse(MediaResource::collection($data),'',200, true);
    }

    
    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/medias/{id}",
     *     operationId="getMediaById",
     *     tags={"Media"},
     *     summary="Get media by ID",
     *     description="Returns a single media",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of media to return",
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
     *             @OA\Property(property="media_path", type="string", example="/path/to/media"),
     *             @OA\Property(property="media_url", type="string", example="http://example.com/media"),
     *             @OA\Property(property="media_type", type="string", example="image"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2021-09-15T00:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2021-09-15T00:00:00Z")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id)
    {
        $media = $this->mediaRepositoryInterface->getById($id);
        if (!$media) {
            return ResponseClass::sendResponse(null, "Media not found", 404);
        }

        return ResponseClass::sendResponse(new MediaResource($media),'',200);
    }


    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/medias/{id}",
     *     operationId="deleteMedia",
     *     tags={"Media"},
     *     summary="Delete a media",
     *     description="Deletes a media and returns a success message",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of media to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Media deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Media not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting media"
     *     )
     * )
     */
    public function destroy($id)
    {
        $media = $this->mediaRepositoryInterface->getById($id);
        if (!$media) {
            return ResponseClass::sendResponse(null, "Media not found", 404);
        }

        $deleted = $this->mediaRepositoryInterface->delete($id);
        if(!$deleted) {
            return ResponseClass::sendResponse(null, 'Error deleting media', 500);
        }

        return ResponseClass::sendResponse(null,'Media deleted successfully',204);
    }
}
