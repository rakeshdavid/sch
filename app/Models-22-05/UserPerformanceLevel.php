<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPerformanceLevel extends Model
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
    protected $fillable = ['user_id', 'performance_level_id'];
}
