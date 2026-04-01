<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $citizen;

    public function __construct(Review $review)
    {
        $this->review = $review;
        $this->citizen = $review->user;
    }

    public function build()
    {
        return $this->subject('Nova Review Aguardando Moderação')
                    ->markdown('emails.new-review');
    }
}