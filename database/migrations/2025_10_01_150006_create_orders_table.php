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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->cascadeOnDelete();
            $table->foreignId("driver_id")->nullable()->constrained('drivers')->onDelete('set null');

            $table->string('pickup_lat');
            $table->string('pickup_lng');
            $table->string('dropoff_lat');
            $table->string('dropoff_lng');
            $table->decimal('price', 8, 2)->nullable();
            $table->enum("status", [
                "pending",
                "accepted",
                "on_the_way",
                "delivered",
                "canceled"
            ])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
