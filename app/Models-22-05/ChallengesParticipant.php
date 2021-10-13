<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengesParticipant extends Model
{
    public function challenges(){

		return $this->belongsTo(challenges::class);

	}
}
