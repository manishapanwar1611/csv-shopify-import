<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ShopifyService
{

    protected $baseurl;
    protected $token;
    protected $collection_id;

    public function __construct()
    {
       $domain=config('services.shopify.domain');
       $token=config('services.shopify.token');
       $collection_id=config('services.shopify.collection_id');

      // $this->baseurl = "https://$domain/admin/api/2024-07/collections/$collection_id";
       $this->baseurl = "https://$domain/admin/api/2024-07";
       $this->token = $token;
       $this->collection_id = $collection_id;
    }

    private function call($method, $endpoint, $data = [])
    {
         return Http::withHeaders([
                'X-Shopify-Access-Token' => $this->token
            ])
            ->$method($this->baseurl . $endpoint, $data)
            ->json();
    }

    public function createProduct($data)
    {
        return $this->call('post', '/products.json', ['product' => $data]);
    }

    // public function addVariant($productId, $data)
    // {
    //     return $this->call('post', "/products/$productId/variants.json", ['variant' => $data]);
    // }

    public function uploadImage($productId, $src)
    {
        return $this->call('post', "/products/$productId/images.json", [
            'image' => ['src' => $src]
        ]);
    }
    public function addToCollection($productId)
    {
        return $this->call('post', "/collects.json", [
            'collect' => [
                'collection_id' => $this->collection_id,
                'product_id' => $productId
            ]
        ]);
    }
   

}

?>