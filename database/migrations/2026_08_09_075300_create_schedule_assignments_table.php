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
        Schema::create('schedule_assignments', function (Blueprint $table) {
           $table->id();

            $table->foreignId('weekly_schedule_id')
                ->constrained('weekly_schedules')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('shift_id')
                ->nullable()
                ->constrained('shifts')
                ->nullOnDelete();

            $table->date('work_date');

            $table->string('status', 20)
                ->default('scheduled')
                ->index();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'weekly_schedule_id',
                'user_id',
                'work_date',
            ]);

            $table->index([
                'user_id',
                'work_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_assignments');
    }
};
