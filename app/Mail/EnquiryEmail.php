<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $contact;
    public $fromMail;


    public function __construct($from,$data)
    {
        //
        $this->contact = $data;
        $this->fromMail = $from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->fromMail, $this->contact)
        ->subject('CUSTOMER ENQUIRY | SMSONLINE')
                ->markdown('emails.enquiry');
    }
}
