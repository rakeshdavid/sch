<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use App\Models\ActivityGenre;
use App\Models\PerformanceLevel;
use App\Http\Controllers\Controller;

class ActionsController extends Controller {
	
	public function showSearchCoachForm(Request $request) {
	    $activity_types = ActivityType::all();
        $performance_levels = PerformanceLevel::all();
		return \View::make( 'customer.search-coach', [
            'activity_types' => $activity_types,
            'performance_levels' => $performance_levels
        ]);
	}

	public function getGenres(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $genres = ActivityGenre::select('id', 'name')->whereActivityTypeId((int)$request->get('activity'))->orderBy('name', 'ASC')->get();
        return response()->json($genres);
    }

    /**
     * Get genres by activity types ids via Ajax
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenresNew(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $activityTypesIds = $request->only('activityTypesIds');
        $types = ActivityType::getGenresByActivityTypesIds($activityTypesIds);

        return response()->json($types);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchCoach(Request $request)
    {
        $activity_types = ActivityType::all();
        $performance_levels = PerformanceLevel::orderBy('order', 'ASC')->get();
        $genres = [];
        $genres_id = $request->get('genres');
        $levels_id = $request->get('levels');

        //crunch
        if ($request->has('genres') or $request->get('levels')){
            $type_id = 1;
            } else { $type_id = 0; }
        //$type_id = (int) $request->get('type');

        $filters = $type_id > 0 ? true : false;
        $filters = (is_array($genres_id) ? true : $filters) || (is_array($levels_id) ? true : $filters);
        //dd($genres_id);
        if(!is_null($genres_id) && !is_array($genres_id)) {
            $genres_id = explode(',', $genres_id);
            $filters = true;
        }
        if(!is_null($levels_id) && !is_array($levels_id)) {
            $levels_id = explode(',', $levels_id);
            $filters = true;
        }
        if($type_id > 0) {
            $genres = ActivityGenre::whereActivityTypeId($type_id)->get();
        }
        $coaches = User::findCoaches($type_id, $genres_id, $levels_id);
        return view( 'customer.search-coach', [
            'activity_types' => $activity_types,
            'performance_levels' => $performance_levels,
            'genres' => $genres,
            'coaches' => $coaches,
            'genres_id' => $genres_id,
            'type_id' => $type_id == 0 ? null : $type_id,
            'levels_id' => $levels_id,
            'filters' => $filters,
        ]);
	}
	
	/*public function showSearchCoachResults() {
		return \View::make( 'customer.searched-coach-list', [
			'coaches' => User::whereRole( User::COACH_ROLE )->get(),
		] );
	}*/
}
