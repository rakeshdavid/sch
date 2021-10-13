<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGenre extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'activity_type_id'];

    /**
     * Relationship with parent activity type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity_type()
    {
        return $this->belongsTo(ActivityType::class);
    }
}
