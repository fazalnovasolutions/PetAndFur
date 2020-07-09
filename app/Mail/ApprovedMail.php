<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $order;
    private $line_item;
    private $sender = 'support@petandfur.com';
    public function __construct(Order $order,$id)
    {
        $this->order = $order;
        $this->line_item = $id;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'PET&FUR')->subject('Design Approved - Pet&Fur')->view('approved')->with([
            "order" =>$this->order,
            "line_item" => $this->line_item,
        ]);
    }
}
