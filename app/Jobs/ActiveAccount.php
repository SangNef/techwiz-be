<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ActiveAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $activationLink;

    public function __construct($email, $activationLink)
    {
        $this->email = $email;  
        $this->activationLink = $activationLink;
    }

    public function handle()
    {

        Mail::send('mails.activation', ['link' => $this->activationLink], function ($message) {
            $message->to($this->email)
                    ->subject('Kích hoạt tài khoản');
        });
    }
}
