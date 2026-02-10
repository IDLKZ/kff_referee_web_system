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
        // Справочник ролей пользователей (судья, логист, администратор и т.д.)
        Schema::create("roles", function (Blueprint $table) {
            $table->id();
            $table->string("title_ru", 255);
            $table->string("title_kk", 255)->nullable();
            $table->string("title_en", 255)->nullable();
            $table->string("value", 280)->unique()->index("idx_role_value")->comment('Системный код роли');
            $table->string("group", 280)->index("idx_role_group")->comment('Группировка ролей');
            $table->boolean("can_register")->default(false)->comment('Доступна ли роль при регистрации');
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("roles");
    }
};
