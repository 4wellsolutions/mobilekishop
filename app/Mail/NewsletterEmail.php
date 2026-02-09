<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

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
        return $this->subject("Top Pick of the week - Latest Blogs")
                    ->from('info@mobilekishop.net','MobileKiShop')
                    ->to($this->user->email)
                    ->view('emails.newsletter')
                    ->with([
                        'user' => $this->user
                    ]);
    }
}
