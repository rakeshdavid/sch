<?php

namespace App\Api\Controllers;

use Dingo\Api\Http\Request;
use App\Models\User;
use App\Models\UserActivityType;
use App\Models\UserGenre;
use App\Models\UserPerformanceLevel;
use App\Api\Transformers\CoachesTransformer;
use App\Api\Transformers\CoachTransformer;
use Illuminate\Support\Facades\DB;
use ApplePayHelper;
/**
 * Profile resource representation. Requires Authorization header.
 *
 * @Resource("Coaches", uri="/coaches")
 */
class CoachesController extends BaseController
{
    /**
     * Coaches list
     *
     * Get a coaches list.
     *
     * @Get("/?genres,genres")
     * @Parameters({
     *      @Parameter("genres", description="array of genres id's, optional"),
     *      @Parameter("levels", description="array of levels id's, optional")
     * })
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{
     *         {"id": 1, "first_name": "John", "last_name": "Doe", "avatar": "https://betaplatform.showcasehub.com/avatars/coach_1.jpg",
     *         "genres": {{"id": 1, "name": "Hip-hop"}, {"id": 4, "name": "Tap"}}},
     *         {"id": 2, "first_name": "Lara", "last_name": "Smith", "avatar": "https://betaplatform.showcasehub.com/avatars/coach_2.jpg",
     *         "genres": {{"id": 2, "name": "Ballet"}}}
     *     }, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function index(Request $request)
    {
        $genres_id = $request->get('genres');
        $levels_id = $request->get('levels');
        $name = $request->get('name');
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();
       
        //crunch
        $type_id = ($request->has('genres') || $request->get('levels')) ? 1 : 0;
        $coaches = self::findCoaches($type_id, $genres_id, $levels_id, $name);
        if($video_tax){
            foreach ($coaches as &$coache){
                if (!empty($coache)) {
                    $coache->price_summary_total = number_format(($coache->price_summary + ($coache->price_summary * (int)$video_tax->taxrate / 100)),2 );
                    $coache->price_summary_tax = number_format(($coache->price_summary * (int)$video_tax->taxrate / 100),2 );
                    $coache->price_detailed_total = number_format(($coache->price_detailed + ($coache->price_detailed * (int)$video_tax->taxrate / 100)),2 );
                    $coache->price_detailed_tax = number_format(($coache->price_detailed * (int)$video_tax->taxrate / 100),2 );
                    $coache->audition_product_id = ApplePayHelper::priceID($coache->price_summary);
                    $coache->audition_product_price =  ApplePayHelper::newPrice($coache->price_summary);
                    $coache->comp_product_id = ApplePayHelper::priceID($coache->price_detailed);
                    $coache->comp_product_price =  ApplePayHelper::newPrice($coache->price_detailed);
                }
                
            }
        }
        //$coaches = self::getAllCoaches();
        return $this->response()->collection($coaches, new CoachesTransformer());
    }
 
    /**
     * Coach profile
     *
     * JSON representation of coach profile.
     * **prices** - always contains summary and detailed prices in USD.
     * **gallery** - array of gallery items. May contain images and Youtube videos.
     *
     * @Get("/<coach_id>")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"id": 1, "first_name": "John", "last_name": "Doe", "overview": "Ullamcorper a nulla sapien nec.",
     *          "avatar": "https://betaplatform.showcasehub.com/avatars/coach.jpg",
     *         "social": "John Doe Instagram & FB Page as johndoe", "prices": {"summary": 100, "detailed": 150},
     *         "genres": {{"id": 1, "name": "Hip-hop"}, {"id": 2, "name": "Ballet"}}, "gives_feedback_to":
     *         {{"id": 1, "name": "Beginner"}, {"id": 2, "name": "Intermediate"}}, "certifications": {"Member of AEA",
     *         "Member of SDC"}, "teaching_positions": {"Broadway Dance Center", "Beijing Dance Academy"},
     *         "performance_credits": {"Choreographer", "Teacher", "Creative Director"}, "gallery": {{"id": 1, "path":
     *         "https://betaplatform.showcasehub.com/gallery/coach.jpg", "type": "image","video_thumbnail":""},
     *          {"id": 2, "path": "ag8OPzBNock","type": "video","video_thumbnail":"https://img.youtube.com/vi/uFO4Riu5DjU/0.jpg"}}},
     *      "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function coach(User $coach)
    {
       // return $coach;
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();
        
        $coache = &$coach;
        
        $coache->audition_product_id = ApplePayHelper::priceID($coache->price_summary);
        $coache->audition_product_price =  ApplePayHelper::newPrice($coache->price_summary);
        $coache->comp_product_id = ApplePayHelper::priceID($coache->price_detailed);
        $coache->comp_product_price = ApplePayHelper::newPrice($coache->price_detailed);
        if($video_tax){
            
                if (!empty($coache)) {
                    $coache->price_summary_total = number_format(($coache->price_summary + ($coache->price_summary * (int)$video_tax->taxrate / 100)),2 );
                    $coache->price_summary_tax = number_format(($coache->price_summary * (int)$video_tax->taxrate / 100),2 );
                    $coache->price_detailed_total = number_format(($coache->price_detailed + ($coache->price_detailed * (int)$video_tax->taxrate / 100)),2 );
                    $coache->price_detailed_tax = number_format(($coache->price_detailed * (int)$video_tax->taxrate / 100),2 );

                }
                
        
        }
        return $this->response->item($coach, new CoachTransformer());
    }

    public static function findCoaches($type_id = null, $genres_id = null, $levels_id = null,$name = null)
    {
        $result = User::whereRole(User::COACH_ROLE)->whereIsHidden(User::getVisibleOption())->orderBy('first_name');
        if($type_id === 0 && is_null($genres_id) && is_null($levels_id)) {
            return $result->get();
        }
        if($type_id > 0) {
            $type_related_users_id = array_flatten(UserActivityType::whereActivityTypeId($type_id)->select('user_id')
                ->get()->toArray());
            $result = $result->whereIn('id', $type_related_users_id);
        }
        if(!is_null($genres_id) && is_array($genres_id)) {
            $genres_related_users_id = array_flatten(UserGenre::whereIn('activity_genre_id', $genres_id)
                ->select('user_id')->get()->toArray());
            $result = $result->whereIn('id', $genres_related_users_id);
        }
        if(!is_null($levels_id) && is_array($levels_id)) {
            $levels_related_users_id = array_flatten(UserPerformanceLevel::whereIn('performance_level_id', $levels_id)
                ->select('user_id')->get()->toArray());
            $result = $result->whereIn('id', $levels_related_users_id);
        }
        if(!is_null($name) && $name !=''){
            $result = $result->where('first_name','like','%'.$name.'%');
        }
        return $result->get();
    }

    public static function getAllCoaches(){
        $coaches = User::whereRole(User::COACH_ROLE);

        return $coaches->get();
    }
}
