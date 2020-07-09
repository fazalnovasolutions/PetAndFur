<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesignMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $order;
    private $product;
    private $sender = 'support@petandfur.com';


    public function __construct(Order $order,$title)
    {
        $this->order = $order;
        $this->product = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender, 'PET&FUR')->subject('Design Uploaded - Pet&Fur')->view('design')->with([
            "order" => $this->order,
            "product" => $this->product,
        ]);
    }
}
