<?php

namespace App\Api\Controllers;

use App\Models\PerformanceLevel;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Api\Transformers\ActivityTypeTransformer;

/**
 * Genres, activity genres, performance levels representation. Requires Authorization header.
 *
 * @Resource("Genres, Activity genres, Performance levels")
 */
class DataController extends BaseController
{
    /**
     * Get levels list
     *
     * Get a JSON representation of levels.
     *
     * @Get("/levels")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{{"id": 1, "name": "Beginner"}, {"id": 2, "name": "Intermediate"}, {"id": 3,
     *         "name": "Advanced"}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error": "Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function levels()
    {
        $performance_levels = PerformanceLevel::select('id', 'name')->get();

        return $this->response->array(['data' => $performance_levels, 'status_code' => 200]);
    }

    /**
     * Get genres list
     *
     * Get a JSON representation of genres.
     *
     * @Get("/genres")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{{"id": 1, "name": "	Instrumental Jazz"}, {"id": 2, "name": "Ballet"}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error": "Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function genres()
    {
        $performance_levels = ActivityGenre::select('id', 'name')->get();

        return $this->response->array(['data' => $performance_levels, 'status_code' => 200]);
    }

    /**
     * Activity genres list
     *
     * Get list of available activity types and their genres.
     *
     * @Get("/activity-types")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {{"id": 1, "name": "dancing", "activity_genres": {{"id":1, "name": "hip-hop"},
     *         {"id": 2, "name": "ballet"}}}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function activityTypes()
    {
        return $this->response()->collection(ActivityType::all(), new ActivityTypeTransformer());
    }
}
