<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Review;

class ReviewPost extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

   
    public function build()
    {
        return $this->subject('Review Just Landed!')
                    ->from('info@mobilekishop.net','MobileKiShop')
                    ->bcc(["4wellsolutions@gmail.com","info@mobilekishop.net"])
                    ->to($this->review->email)
                    ->view('emails.review')
                    ->with([
                        'review' => $this->review
                    ]);
    }
}
