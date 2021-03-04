<?php

namespace Jackabox\Shopify\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPShopify\ShopifySDK;

class ImportAllProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function handle()
    {
        foreach ($this->data as $product) {
            ImportSingleProductJob::dispatch($product);
        }
    }
}
