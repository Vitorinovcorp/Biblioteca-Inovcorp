<?php

namespace App\Mail;

use App\Models\Encomenda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EncomendaConfirmadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $encomenda;

    public function __construct(Encomenda $encomenda)
    {
        $this->encomenda = $encomenda;
    }

    public function build()
    {
        return $this->subject('Encomenda Confirmada - ' . $this->encomenda->numero_encomenda)
                    ->markdown('emails.encomenda-confirmada');
    }
}