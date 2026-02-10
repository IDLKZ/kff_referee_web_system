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
        // Бронирование отелей в рамках командировки
        Schema::create('trip_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('hotel_rooms')->onDelete('set null')->comment('Тип номера');
            $table->dateTime('from_date')->comment('Дата заселения');
            $table->dateTime('to_date')->comment('Дата выселения');
            $table->text('info')->nullable();
            $table->foreignId('logist_id')->nullable()->constrained('users')->onDelete('set null')->comment('Логист, оформивший бронь');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_hotels');
    }
};
