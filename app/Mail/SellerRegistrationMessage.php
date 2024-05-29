<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\Seller;

class SellerRegistrationMessage extends Mailable
{
    use Queueable, SerializesModels;

      public $sellerEmail;
    public $totalPrice;

    public function __construct($sellerEmail, $totalPrice)
    {
        $this->sellerEmail = $sellerEmail;
        $this->totalPrice = $totalPrice;
    }

    public function build()
    {
        return $this->subject('Nichen Confirmation de compte')->view('email-templates.seller_registration_message');
    }
}