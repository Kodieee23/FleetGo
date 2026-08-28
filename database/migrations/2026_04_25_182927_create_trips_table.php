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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('purpose_id')->constrained('trip_purposes');
            $table->foreignId('department_id')->constrained('departments');
            $table->json('destination');
            $table->timestamp('time_out')->nullable();
            $table->timestamp('time_returned')->nullable();
            $table->text('other_purpose_description')->nullable();
            $table->boolean('is_offline_entry')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
