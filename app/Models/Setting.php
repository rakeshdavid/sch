<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Video;
use App\Models\ChallengesParticipant;
use App\Models\AuditionList;

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
    public static function getReviewCommission($participation_type = Video::PARTICIPATION_TYPE)
    {
      switch ($participation_type){
         case ChallengesParticipant::PARTICIPATION_TYPE:
            $key = 'coach_commission_reviewing_challenge';          
            break;
         case AuditionList::PARTICIPATION_TYPE:
            $key = 'coach_commission_reviewing_audition';          
            break;
         case Video::PARTICIPATION_TYPE:
            $key = 'coach_commission_reviewing_video';          
            break;
      }
      $reviewCommission = self::where('key', $key)->first();
      return (!empty($reviewCommission->value) ? $reviewCommission->value : 20);
    }
}
