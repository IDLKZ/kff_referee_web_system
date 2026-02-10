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
        // Хранилище метаданных загруженных файлов (логотипы, документы, протоколы)
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("filename", 280)->comment('Оригинальное имя файла');
            $table->text("file_path")->comment('Путь к файлу в хранилище');
            $table->unsignedBigInteger("file_size_bytes")->nullable()->comment('Размер файла в байтах');
            $table->string("content_type")->nullable()->comment('MIME-тип файла');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
