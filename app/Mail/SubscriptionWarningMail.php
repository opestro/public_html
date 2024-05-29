<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\Seller;

class SubscriptionWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
       return $this->subject('Nichen Rappel d\'abonnement')
                    ->view('email-templates.subscription-warning')
                    ->with([
                        'f_name' => $this->seller->f_name,
                        'l_name' => $this->seller->l_name,
                    ]);
    }
}
