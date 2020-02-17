<?php

namespace App\Console\Commands;

use App\Http\Controllers\HelperController;
use App\Http\Controllers\OrdersController;
use App\Mail\UpdateMail;
use App\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailThreeDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = \Carbon\Carbon::today()->subDays(3)->format('Y-m-d');
        $orders = Order::where('last_email_at', '>=', $date)->orWhereNull('last_email_at')->get();

        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $orderQ = $order->has_design_details()->where('order_id', $order->id)->whereIN('status', ['In-Processing'])->get();
                if (count($orderQ) > 0) {
                    try {
                        Mail::to($order->email)->send(new UpdateMail($order));
                        $order->last_email_at = now()->format('Y-m-d');
                        $order->save();
                    } catch (\Exception $e) {

                    }
                }
            }

        }
    }
}
