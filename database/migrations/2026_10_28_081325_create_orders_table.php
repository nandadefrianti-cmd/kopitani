
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code')->unique();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('farmer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'dikirim',
                'selesai',
            ])->default('menunggu_pembayaran');

            $table->text('delivery_address');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
