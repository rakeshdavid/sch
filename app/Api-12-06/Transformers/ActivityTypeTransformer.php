<?php

namespace App\Api\Transformers;

use App\Models\ActivityType;
use League\Fractal\TransformerAbstract;

class ActivityTypeTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param ActivityType $type
     * @return array
     */
    public function transform(ActivityType $type)
    {
        $type->load('activity_genres');
        return [
            'id' => (int) $type->id,
            'name' => $type->name,
            'activity_genres' => $type->activity_genres->reduce(function ($carry, $genre) {
                $carry[] = [
                    'id' => $genre->id,
                    'name' => $genre->name,
                ];
                return $carry;
            }, []),
        ];
    }
}
