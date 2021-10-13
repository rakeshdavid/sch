<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    public static function checkPlatformMaintenanceMode()
    {
        $record = self::where('key', '=', 'platform_maintenance_mode')->first();
        if ($record && $record->value == 1) {
            return true;
        }

        return false;
    }

    public static function checkCoachesMaintenanceMode()
    {
        $record = self::where('key', '=', 'coaches_maintenance_mode')->first();
        if ($record && $record->value == 1) {
            return true;
        }

        return false;
    }
}
