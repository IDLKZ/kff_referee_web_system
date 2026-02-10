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
        // Справочник турниров (Премьер-Лига, Кубок Казахстана и т.д.)
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null')->comment('Логотип турнира');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('title_ru', 255);
            $table->string('title_kk', 255);
            $table->string('title_en', 255);
            $table->string('short_title_ru', 255);
            $table->string('short_title_kk', 255);
            $table->string('short_title_en', 255);
            $table->text('description_ru')->nullable();
            $table->text('description_kk')->nullable();
            $table->text('description_en')->nullable();
            $table->string('value', 280)->unique()->comment('Системный код турнира');
            $table->integer('level')->default(0)->comment('Уровень турнира (0=низший)');
            $table->tinyInteger('sex')->default(0)->comment('0=смешанный, 1=мужской, 2=женский');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
