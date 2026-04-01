<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $status;
    public $justificativa;

    public function __construct(Review $review, $status, $justificativa = null)
    {
        $this->review = $review;
        $this->status = $status;
        $this->justificativa = $justificativa;
    }

    public function build()
    {
        $subject = $this->status === 'ativo' 
            ? 'Sua review foi aprovada!' 
            : 'Sua review foi recusada';

        return $this->subject($subject)
                    ->markdown('emails.review-status');
    }
}