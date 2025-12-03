<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_import_log;
use App\Models\Product_upload;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
   public function datatable()
    {
        $query = Product_upload::selectRaw("
                                    id,
                                    filename,
                                    status,
                                    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted
                                ");

        return datatables()->of($query)
            ->addColumn('expand', function ($row) {
                return '<button class="btn btn-sm btn-primary expand-btn" data-id="' . $row->id . '">+</button>';
            })
            ->rawColumns(['expand'])
            ->make(true);
    }

    // Child table = Products inside upload
    public function products($uploadId)
    {

        $query = Product::where('upload_id', $uploadId)
            ->selectRaw("title, status, shopify_id, errors, DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted");
        return datatables()->of($query)->make(true);
    }

    public function logs()
    {
        $query = Product_import_log::selectRaw("level,title, message, DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted");
        return datatables()->of($query)->make(true);
    }
    public function all_products()
    {
        $query = Product::with('upload')
            ->join('product_uploads', 'product_uploads.id', '=', 'products.upload_id')
            ->selectRaw("
                products.title,
                products.status,
                products.shopify_id,
                products.errors,
                product_uploads.filename,
                DATE_FORMAT(products.created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted
            ");    
            return datatables()->of($query)->make(true);
    }
}
