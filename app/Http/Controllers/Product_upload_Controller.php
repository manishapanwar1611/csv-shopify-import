<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store_upload_product;
use App\Jobs\ProcessCsvUpload;
use App\Models\Product_upload;
use App\Services\ImportLogger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class Product_upload_Controller extends Controller 
{

    public $importLogger;
    public function __construct(ImportLogger $importLogger)
    {
        $this->importLogger=$importLogger;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()     
    {

       

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
         return view('product_upload.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store_upload_product  $request)
    {
        $file = $request->file('product_csv');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename,'public');
 

        $product_upload= New Product_upload();
        $product_upload->filename=$filename;
        $product_upload->filepath=$path;
        $product_upload->status='uploaded';
        $product_upload->save();

        $this->importLogger->info('CSV Uploaded Successfully', 'CSV Uploaded Successfully', $product_upload->id);

        //for add product to product table and import into shopify
        ProcessCsvUpload::dispatch($product_upload);

        return Redirect::route('product_upload.create')->with('success', 'Product CSV Uploaded Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
