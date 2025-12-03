<?php
namespace App\Services;
use App\Models\Product_import_log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportLogger
{
    public function info(string $title, $message = null, $uploadId = null,$productId = null)
    {
        $this->write('info', $title, $message, $uploadId,$productId);
    }

    public function warning(string $title, $message = null, $uploadId = null,$productId = null)
    {
        $this->write('warning', $title, $message, $uploadId,$productId);
    }

    public function error(string $title, $message = null, $uploadId = null, $productId = null)
    {       
        $this->write('error', $title, $message, $uploadId,productId: $productId = null);
    }

    protected function write(string $level, string $title = null, $message = null,$uploadId = null,$productId = null)
    {
        $payload = [
            'title' => $title,
            'message' => is_string($message) ? $message : json_encode($message),
            'upload_id' => $uploadId,
            'product_id' => $productId,
        ];

        // write to file channel
        Log::info($title . ' - ' . ($payload['message'] ?? ''));

        // persist to DB
        Product_import_log::create([
            'upload_id' => $uploadId,
            'product_id' => $productId,
            'level' => $level,
            'title' => $title,
            'message' => $payload['message'],
        ]);
    }
}


?>