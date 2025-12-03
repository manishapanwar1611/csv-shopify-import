<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_id')->index();
            $table->string('handle')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->longText('tags')->nullable();
            $table->string('published')->nullable();
            $table->string('variant_sku')->nullable();
            $table->decimal('variant_price', 12, 2)->nullable();
            $table->decimal('variant_compare_at_price', 12, 2)->nullable();
            $table->boolean('variant_requires_shipping')->default(true);
            $table->boolean('variant_taxable')->default(true);
            $table->string('variant_inventory_tracker')->default('shopify');
            $table->integer('variant_inventory_qty')->default(0);
            $table->string('variant_inventory_policy')->nullable();
            $table->string('variant_fulfillment_service')->nullable();
            $table->decimal('variant_weight', 8, 3)->nullable();
            $table->string('variant_weight_unit')->nullable();
            $table->string('image_src')->nullable();
            $table->integer('image_position')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->string('shopify_id')->nullable()->index();
            $table->enum('status', ['pending','processing','successful','failed'])->default('pending');
            $table->json('errors')->nullable();
            $table->timestamps();
            $table->foreign('upload_id')->references('id')->on('product_uploads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
