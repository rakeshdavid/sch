<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $user->load('activity_types', 'activity_genres', 'performance_levels');
        return [
            'id' => (int) $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'email' => $user->email,
            'avatar' => preg_match('/^(http:\/\/|https:\/\/)/', $user->avatar) ?  $user->avatar : url($user->avatar),
            'contact_email' => $user->contact_email,
            'website' => $user->wevsites, // lol
            'social_links' => $user->social_links,
            'birth_date' => $user->birthday,
            'about' => strip_tags($user->about),
            'location' => $user->location,
            'location_state' => $user->location_state,
            'activities' => $user->activity_types
                ->reduce(function ($carry, $type) {
                    $carry[] = ['id' => $type->id, 'name' => $type->name];
                    return $carry;
                }, []),
            'genres' => $user->activity_genres
                ->reduce(function ($carry, $genre) {
                    $carry[] = ['id' => $genre->id, 'name' => $genre->name];
                    return $carry;
                }, []),
            'levels' => $user->performance_levels
                ->reduce(function ($carry, $level) {
                    $carry[] = ['id' => $level->id, 'name' => $level->name];
                    return $carry;
                }, []),
        ];
    }
}
