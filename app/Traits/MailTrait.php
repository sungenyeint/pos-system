<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait MailTrait
{
    public function sendMail($template, $data, $to, $subject, $to_name = null)
    {
        Mail::send(['text' => $template], $data, function ($message) use ($to, $subject, $to_name) {
            $message->to($to, $to_name)
                ->subject($subject);
        });
    }
}
