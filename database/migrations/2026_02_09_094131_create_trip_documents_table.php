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
        // Финансовые документы командировки (билеты, квитанции, чеки)
        Schema::create('trip_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade')->comment('Скан/фото документа');
            $table->string('title', 255)->comment('Название документа');
            $table->text('info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('price', 10, 2)->nullable()->comment('Цена за единицу');
            $table->decimal('qty', 10, 2)->nullable()->comment('Количество');
            $table->decimal('total_price', 10, 2)->nullable()->comment('Итоговая сумма');
            $table->foreignId('logist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_documents');
    }
};
