<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Traits\MailTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendStockQuantity extends Command
{
    use MailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_stock_quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send stock quantity to owner';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::setDefaultDriver('send_stock_quantity');

        logger()->info('=== START SendStockQuantity Command ===');

        $products = Product::with(['category'])
            ->where('stock_quantity', '<=', 2)
            ->orderBy('stock_quantity', 'ASC')
            ->get()
            ->groupBy(function ($product) {
                return $product->category->name;
            });

        $data['products'] = $products->toArray();

        $this->sendMail(
            'admin.mail.noti_stock_quantity',
            $data,
            'test@gmail.com',
            'Notification stock quantity',
            'Pyae Baby Store',
        );

        logger()->info('=== End SendStockQuantity Command ===');
    }
}
