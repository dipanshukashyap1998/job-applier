<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Company $company;

    public string $resumePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Company $company, string $resumePath)
    {
        $this->company = $company;
        $this->resumePath = $resumePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application for ' . $this->company->designation,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application',
            with: [
                'company' => $this->company,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->resumePath)
                ->as('Dipanshu Resume.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
