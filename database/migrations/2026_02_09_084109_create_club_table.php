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
        // Футбольные клубы (поддержка иерархии через parent_id: основная команда → молодёжка)
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null')->comment('Логотип клуба');
            $table->foreignId('parent_id')->nullable()->constrained('clubs')->onDelete('set null')->comment('Родительский клуб');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('type_id')->nullable()->constrained('club_types')->onDelete('set null');
            $table->text('short_name_ru');
            $table->text('short_name_kk');
            $table->text('short_name_en');
            $table->text('full_name_ru');
            $table->text('full_name_kk');
            $table->text('full_name_en');
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('bin', 12)->nullable()->index()->comment('БИН (бизнес-идентификационный номер)');
            $table->date('foundation_date')->nullable();
            $table->text('address_ru')->nullable();
            $table->text('address_kk')->nullable();
            $table->text('address_en')->nullable();
            $table->json('phone')->nullable()->comment('Массив телефонов');
            $table->text('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
