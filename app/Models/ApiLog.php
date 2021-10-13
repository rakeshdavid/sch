<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $table = 'api_logs';


    public static function writeLog($id, $params, $response ){
        $log = new ApiLog;
        $temp = ApiLog::all()->last()->id;
        $log->id = $temp+1;
        $log->log_type_id = $id;
        $log->request = $params;
        $log->response = $response;
        $log->save();
    }
}
