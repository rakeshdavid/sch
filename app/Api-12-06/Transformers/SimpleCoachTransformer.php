<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class SimpleCoachTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param User $coach
     * @return array
     */
    public function transform(User $coach)
    {
        return [
            'id' => (int) $coach->id,
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
        ];
    }
}
