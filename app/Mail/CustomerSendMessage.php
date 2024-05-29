<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Model\Seller;
use App\User; 

class CustomerSendMessage extends Mailable
{
    use Queueable, SerializesModels;
   
    public $user;
    public $seller;

    public function __construct(User $user, Seller $seller)
    {
      
        $this->user = $user;
        $this->seller = $seller;
    }

    public function build()
    {
        
        return $this->subject('Nichen message d\'un client')
                    ->view('email-templates.customer_send_message')
                    ->with([
                       
                        'user' =>  $this->user,
                        'seller' => $this->seller,
                    ]);
    }
}
