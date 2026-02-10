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
        // Пользователи системы (судьи, логисты, администраторы, инспекторы)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->foreignId('image_id')->nullable()->constrained('files')->onDelete('set null')->comment('Фото профиля');
            $table->string('last_name', 255);
            $table->string('first_name', 255);
            $table->string('patronymic', 255)->nullable();
            $table->string('phone', 255)->index()->unique();
            $table->string('email', 255)->index()->unique();
            $table->string('username', 255)->index()->unique();
            $table->tinyInteger('sex')->default(0)->comment('0=не указан, 1=мужской, 2=женский');
            $table->string('iin', 12)->nullable()->index()->comment('ИИН (индивидуальный идентификационный номер)');
            $table->date('birth_date')->nullable();
            $table->text('password_hash')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false)->comment('Подтверждён ли аккаунт');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
