<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to MobileKiShop')
                    ->from('info@mobilekishop.net','MobileKiShop')
                    ->bcc(["4wellsolutions@gmail.com","info@mobilekishop.net"])
                    ->to($this->user->email)
                    ->view('emails.registration')
                    ->with([
                        'user' => $this->user
                    ]);
    }
}
