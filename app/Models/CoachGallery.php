<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachGallery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'visible', 'type', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
