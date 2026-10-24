<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Связь «пользователь → роль»: внешний ключ role_id в таблице users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // nullOnDelete — при удалении роли у пользователей останется NULL.
            $table->foreignId('role_id')->nullable()->after('id')
                ->constrained('roles')->nullOnDelete();
        });
    }

    /**
     * Удаление внешнего ключа и столбца role_id.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
