<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserInvitedEmail extends Mailable
{
    use Queueable, SerializesModels;

   /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Aktivasi Akun Penatani Anda';

        return $this->view('emails.user_invited')
                    ->subject($subject)
                    ->with($this->data);
    }
}
