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
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("driver_id");
            $table->foreign("driver_id")->references("id")->on("drivers")->onDelete("cascade");
            $table->string("card_number");
            $table->string("country");
            $table->string("state");
            $table->string("city");
            $table->string("license");
            $table->string("id_card");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_profiles');
    }
};
