<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code;
    protected $email;

    /**
     * Tạo một phiên bản mới của công việc.
     *
     * @param string $code Mã xác nhận
     * @param string $email Email người nhận
     */
    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Thực hiện công việc gửi email.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'subject' => 'Mã xác nhận quên mật khẩu',
            'body' => 'Mã xác nhận của bạn là: ' . $this->code,
        ];

        Mail::send('mails.mail-notify', ['data' => $data], function ($message) use ($data) {
            $message->to($this->email)
                    ->subject($data['subject']);
        });
    }
}
