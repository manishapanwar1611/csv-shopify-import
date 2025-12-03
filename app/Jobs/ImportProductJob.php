<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\ImportLogger;
use App\Services\ShopifyService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
class ImportProductJob implements ShouldQueue
{
    use Queueable;

    protected $product_id;
    protected $product_upload;
    /**
     * Create a new job instance.
     */
    public function __construct($product_id,$product_upload)
    {
        $this->product_id = $product_id;
        $this->product_upload = $product_upload;
    }

    /**
     * Execute the job.
     */
    public function handle(ShopifyService $shopify, ImportLogger $importLogger)
    {

         $product_data = Product::findOrFail($this->product_id);
         $importLogger->info('Import product job started', ['handle' => $product_data->title], $this->product_upload->id,$this->product_id);

        //Log::info($product_data);

        try {
            // Create product
            $product = $shopify->createProduct([
                'title'        => $product_data->title,
                'body_html'    => $product_data->body_html,
                'vendor'       => $product_data->vendor,
                'product_type' => $product_data->product_type,
                'tags'         => $product_data->tags,
                'handle'       => $product_data->handle,

                 "options" => [
                    ["name" => "Variant"]
                ],

                "variants" => [
                    [
                        "option1"             => $product_data->variant_title ?? "Default",
                        "sku"                 => $product_data->variant_sku,
                        "price"               => $product_data->variant_price,
                        "compare_at_price"    => $product_data->variant_compare_at_price,
                        "requires_shipping"   => $product_data->variant_requires_shipping,
                        "taxable"             => $product_data->variant_taxable,
                        "inventory_policy"    => $product_data->variant_inventory_policy,
                        "weight"              => $product_data->variant_weight,
                        "weight_unit"         => $product_data->variant_weight_unit,
                        "inventory_quantity"  => $product_data->variant_inventory_qty,
                    ]
                ],
            ]);

            Log::info($product);

            $productId = $product['product']['id'];

            $shopify->addToCollection($productId);
        
            $importLogger->info('Product created', ['shopify_id' => $productId], $this->product_upload->id,$this->product_id);


            // Create images
            if ($product_data->image_src) {
               $sho_img= $shopify->uploadImage($productId, $product_data->image_src);
                  Log::info($sho_img);

            }
            

            // Mark success

            $product_data->status='successful';
            $product_data->shopify_id=$productId;
            $product_data->save();


        

      



        } catch (Exception $e) {    

            $product_data->status='failed';
            $product_data->save();

            $importLogger->error('Shopify error', $e->getMessage(), $this->product_upload->id, $this->product_id);

            throw $e;
        }
    }  
    
}



?>
