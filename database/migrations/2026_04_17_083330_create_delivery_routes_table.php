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
         Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id();
            $table->date('delivery_date');
            $table->json('route_sequence');
            $table->decimal('total_distance', 10, 2);
            $table->integer('total_duration')->nullable(); // in minutes
            $table->string('algorithm_used'); // haversine, google_matrix
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_routes');
    }
};
