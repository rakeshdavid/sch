<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Helpers\Mailer;
use App\Models\Video;

class SendReminderEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $job_info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_info)
    {
        $this->job_info = $job_info;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = Video::where('id', $this->job_info['video_id'])->select('status')->first();
        if($video->status != Video::STATUS_REVIEWED) {
            $timestamp = strtotime($this->job_info['date']);

            $mail_to_coach = new Mailer();
            $mail_to_coach->subject = 'Reminder to make a review:';
            $mail_to_coach->to_email = $this->job_info['coach_email'];
            $mail_to_coach->sendMail('auth.emails.reminderToMakeReview',
                [
                    'user_name' => $this->job_info['performer_name'],
                    'coach_name' => $this->job_info['coach_name'],
                    'days' => $this->job_info['days_left'],
                    'date' => date( 'F d, Y h:i a', $timestamp )
                ]);

        }

    }
}
