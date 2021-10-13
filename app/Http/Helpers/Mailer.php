<?php

namespace App\Http\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use \Mail;
use \Config;
use App\Models\ApiLog;
use App\Models\LogType;

class Mailer extends Model
{

    public $from_email = false;
    public $from_name = false;
    public $to_email = false;
    public $subject = false;

    public function sendMail($template, $params=[]){

        if(!$this->from_email){
            $this->from_email = 'noreply@showcasehub.com';
        }
        if(!$this->from_name){
            $this->from_name = 'ShowcaseHub';
        }
        if(!$this->to_email){
            $this->to_email = 'noreply@showcasehub.com';
        }
        if(!$this->subject){
            $this->subject = 'ShowcaseHub reminder';
        }

        //if( env('SEND_MAIL',false) ){
        $response =  Mail::send(['html' =>$template, 'text'=>'auth.textEmail.textEmail'], $params, function (Message $message) {
                $message
                    ->from($this->from_email, $this->from_name)
                    ->to($this->to_email)
                    ->subject($this->subject);
            });
        //}

        $log_mail_params = [
            'template'=>$template,
            'from_email' => $this->from_email,
            'from_name' => $this->from_name,
            'to_email' => $this->to_email,
            'subject' => $this->subject,
            'params'=>$params,
        ];
        $response = ($response)? 'true' : 'false';

        ApiLog::writeLog(LogType::select('id')->where('name', 'mail')->first()->id,
            json_encode($log_mail_params), $response);

    }


}
