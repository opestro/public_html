<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\Seller;

class SellerApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->subject('Approbation du magasin Nichen.')->view('email-templates.seller_approved');
    }
}
