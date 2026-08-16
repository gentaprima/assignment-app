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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_number', 50)
                ->nullable()
                ->unique()
                ->after('name');

            $table->string('role', 20)
                ->default('employee')
                ->index()
                ->after('password');

            $table->string('phone', 30)
                ->nullable()
                ->after('role');

            $table->boolean('is_active')
                ->default(true)
                ->index()
                ->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['employee_number']);
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);

            $table->dropColumn([
                'employee_number',
                'role',
                'phone',
                'is_active',
            ]);
        });
    }
};
