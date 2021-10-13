<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentsList extends Model
{
    protected $table = 'payments_lists';//wtf

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }
}
