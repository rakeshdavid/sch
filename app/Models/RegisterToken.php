<?php

namespace App\Models;

use ClassPreloader\Config;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Helpers\Mailer;

class RegisterToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'token', 'email', 'created_at'
    ];

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  string  $token
     * @return bool
     */
    public static function exists($token)
    {
        $tokenModel = self::where('token', $token)->firstOrFail()->toArray();

        return $tokenModel && ! self::tokenExpired($tokenModel['created_at']);
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $token
     * @return bool
     */
    protected static function tokenExpired($token)
    {
        $expiresAt = Carbon::parse($token)->addWeek();

        return $expiresAt->isPast();
    }

    protected static function checkTokenUnique($token)
    {
        return self::where('token', '=', $token)->exists();
    }

    protected static function generateToken()
    {
        return Str::random(10);
    }

    protected static function getUniqueToken()
    {
        $token = self::generateToken();
        if( ! self::checkTokenUnique($token)) {
            return $token;
        } else {
            return getUniqueUrl();
        }
    }

    public static function toDataTable($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $invites = self::select([
            'token',
            'email',
            'created_at',
            'id'
        ]);

        $recordsTotal = $invites->count();

        $invites = self::prepareSearch($invites, $search);

        $recordsFiltered = $invites->count();
        $invites = $invites->limit($length)->offset($request->get('start'))->orderBy('created_at', 'desc')->get();
        if ($invites->count() == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $data = [];
        $i = 0;
        foreach ($invites as $invite) {
            $data[$i][0] = $invite->createInviteLink();
            $data[$i][1] = $invite->email;
            $data[$i][2] = $invite->created_at;
            $data[$i][3] = $invite->id;
            $i++;
        }

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    protected static function prepareSearch($instance, $search = null)
    {
        $instance = $instance->where(function($query) use($search) {
            $query->orWhere('email', 'LIKE', '%'.$search.'%');
        });

        return $instance;
    }

    /**
     * Create new record
     *
     * @param array $attributes
     * @return bool
     */
    public static function create(array $attributes = [])
    {
        $attributes['token'] = self::getUniqueToken();
        $attributes['created_at'] = Carbon::now(\Config::get('app.timezone'))->toDateTimeString();
        $model = new static($attributes);
        if($model->save()) {
            $model->sendInvite();
        }

        return $model->save();
    }

    /**
     * Created link for register with token
     *
     * @example 'domain.com/register/Ud7sa&hGfd
     * @return string
     */
    public function createInviteLink()
    {
        return route('coach.register.form', ['token' => $this->token]);
    }

    protected function sendInvite()
    {
        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $this->email;
        $mail->sendMail('admin.coaches.emails.new_invite', [
            'link' => $this->createInviteLink()
        ]);
    }
}
