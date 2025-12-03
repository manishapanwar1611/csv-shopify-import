<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use app\models\Product_upload;
use App\Services\ImportLogger;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class ProcessCsvUpload implements ShouldQueue
{
    use Queueable;
    public $product_upload;

    /**
     * Create a new job instance.
     */
    public function __construct(Product_upload $product_upload)
    {
        $this->product_upload=$product_upload;

    }

    /**
     * Execute the job.
     */
    public function handle(ImportLogger $importLogger): void
    {
        $this->product_upload->status = 'processing';
        $this->product_upload->save();

        $importLogger->info('Upload processing started', ['filename' => $this->product_upload->filename], $this->product_upload->id);


        $path = storage_path("app/public/uploads/{$this->product_upload->filename}");
        if (!file_exists($path)) {
            $importLogger->error('CSV missing', "File not found at $path",  $this->product_upload->id);
            $this->product_upload->status = 'failed';
            $this->product_upload->save();
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        $grouped = [];

        $rowCount = 0;
        foreach ($records as $row) {
            $rowCount++;
            // Normalize keys and trim
            $row = array_map(function($v){ return is_string($v) ? trim($v) : $v; }, $row);
            //Log::info( $row);

            $handle = $row['Handle'];
            $variantSku = $row['Variant SKU'] ?? null;
            $importLogger->info('Parsed row To Product table', ['row' => $rowCount, 'handle' => $handle], $this->product_upload->id);

            // create product record or if exist update it
            $product = Product::firstOrNew(['handle' => $handle]);
            $product->upload_id = $this->product_upload->id;
            $product->handle = $handle;
            $product->title = $row['Title'] ?? null;
            $product->body_html = $row['Body HTML'] ?? null;
            $product->vendor = $row['Vendor'] ?? null;
            $product->product_type = $row['Product Type'] ?? null;
            $product->tags = isset($row['Tags'])  ? implode(',', array_map('trim', explode(',', $row['Tags'])))  : null;
            $product->variant_sku = $variantSku;
            $product->variant_price = $row['Variant Price'] ?? null;
            $product->variant_compare_at_price = $row['Variant Compare At Price'] ?? null;
            $product->variant_requires_shipping = strtolower($row['Variant Requires Shipping'] ?? 'true') === 'true';
            $product->variant_taxable = strtolower($row['Variant Taxable'] ?? 'true') === 'true';
            $product->variant_inventory_qty = (int)($row['Variant Inventory Qty'] ?? 0);
            $product->variant_inventory_policy = $row['Variant Inventory Policy'] ?? null;
            $product->variant_fulfillment_service = $row['Variant Fulfillment Service'] ?? null;
            $product->variant_weight = $row['Variant Weight'] ?? null;
            $product->variant_weight_unit = $row['Variant Weight Unit'] ?? null;
            $product->image_src = $row['Image Src'] ?? null;
            $product->image_position = $row['Image Position'] ?? null;
            $product->image_alt_text = $row['Image Alt Text'] ?? null;
            $product->status = 'pending';
            $product->save();


           $grouped[] = $product->id;
        }

        $this->product_upload->total_rows = $rowCount;
        $this->product_upload->save();

         // Dispatch ImportProductJob per product
        foreach ($grouped as $product_id) {

            ImportProductJob::dispatch($product_id, $this->product_upload);
        }

        $this->product_upload->status = 'completed';
        $this->product_upload->save();
        $importLogger->info('Upload processing end', ['filename' => $this->product_upload->filename], $this->product_upload->id);



    }
}
