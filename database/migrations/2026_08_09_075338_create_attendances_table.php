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
        Schema::create('attendances', function (Blueprint $table) {
             $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->restrictOnDelete();

            $table->foreignId('schedule_assignment_id')
                ->nullable()
                ->constrained('schedule_assignments')
                ->nullOnDelete();

            // CHECK IN
            $table->timestamp('check_in_at')->nullable();

            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();

            // Akurasi GPS dari browser, meter
            $table->decimal('check_in_accuracy', 10, 2)->nullable();

            // Jarak user ke cabang ketika absen, meter
            $table->decimal('check_in_distance', 10, 2)->nullable();

            // CHECK OUT
            $table->timestamp('check_out_at')->nullable();

            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();

            $table->decimal('check_out_accuracy', 10, 2)->nullable();
            $table->decimal('check_out_distance', 10, 2)->nullable();

            $table->string('status', 20)
                ->default('present')
                ->index();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique('schedule_assignment_id');

            $table->index([
                'user_id',
                'check_in_at',
            ]);

            $table->index([
                'branch_id',
                'check_in_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
