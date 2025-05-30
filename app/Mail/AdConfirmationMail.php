<?php

namespace App\Mail;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ad;

    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    public function build()
    {
        return $this->subject('confirm create the ad')
            ->text('emails.ad_confirmation')
            ->with([
                'ad_title' => $this->ad->title,
                'user_name' => $this->ad->user->name,
                'ad_url' => url('/ads/' . $this->ad->id),
            ]);
    }
}
