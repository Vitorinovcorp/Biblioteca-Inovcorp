<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DevolucaoConfirmadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Requisicao $requisicao;

    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Livro Devolvido - Confirmação',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.devolucao-confirmada',
        );
    }
}