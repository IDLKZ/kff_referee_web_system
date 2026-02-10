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
        // Сегменты перемещений в рамках командировки (перелёт, переезд и т.д.)
        Schema::create('trip_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('transport_type_id')->constrained('transport_types')->onDelete('cascade');
            $table->foreignId('departure_city_id')->constrained('cities')->onDelete('cascade')->comment('Откуда');
            $table->foreignId('arrival_city_id')->constrained('cities')->onDelete('cascade')->comment('Куда');
            $table->dateTime('from_date')->comment('Дата/время отправления');
            $table->dateTime('to_date')->comment('Дата/время прибытия');
            $table->text('info')->nullable()->comment('Номер рейса, вагон и т.д.');
            $table->foreignId('logist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_migrations');
    }
};
