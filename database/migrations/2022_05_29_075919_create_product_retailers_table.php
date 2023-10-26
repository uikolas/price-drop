<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_retailers', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->decimal('price')->nullable();
            $table->dateTime('price_updated_at')->nullable();
            $table->string('type');
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_retailers');
    }
};
