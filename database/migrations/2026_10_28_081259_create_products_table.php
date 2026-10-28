
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock')->default(0);

            $table->unsignedInteger('wholesale_min_qty')->default(10);
            $table->decimal('wholesale_price', 12, 2);

            $table->string('image_path')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
