<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $frontendUrl = config('app.frontend_url') . '/login?token=' . $this->token;

        return $this->markdown('emails.login-token')
                    ->subject('Access to the Platform')
                    ->with([
                        'user' => $this->user,
                        'frontendUrl' => $frontendUrl,
                    ]);
    }
}
