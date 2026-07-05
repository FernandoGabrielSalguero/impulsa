<?php

namespace App\Mail;

use App\Mail\Concerns\LogsCorreo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

abstract class ImpulsaMailable extends Mailable implements LogsCorreo
{
    use Queueable, SerializesModels;

    abstract public function subjectLine(): string;

    abstract public function htmlView(): string;

    abstract public function textView(): string;

    abstract public function viewData(): array;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine(),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: $this->htmlView(),
            text: $this->textView(),
            with: $this->viewData(),
        );
    }

    public function mailMeta(): array
    {
        return [];
    }
}