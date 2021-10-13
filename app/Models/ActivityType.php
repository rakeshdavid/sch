<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get genres by activity types ids
     *
     * @param array $activityTypesIds
     * @return mixed
     */
    public static function getGenresByActivityTypesIds($activityTypesIds)
    {
        $genres = self::with(['activity_genres' => function($query) {
            $query->select('id', 'activity_type_id', 'name as text');
        }])
        ->whereIn('id', array_flatten($activityTypesIds))
        ->get();

        $genres = $genres->map(function($value, $key) {
            $result = $value;
            $result['text'] = $value['name'];
            $result['children'] = $value['activity_genres'];
            unset($result['activity_genres'], $result['name']);
            return $result;
        }, $genres);

        return $genres;
    }

    public function activity_genres()
    {
        return $this->hasMany(ActivityGenre::class);
    }

}
