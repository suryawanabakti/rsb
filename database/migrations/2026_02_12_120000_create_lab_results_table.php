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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('letter_request_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('test_name');
            $table->date('test_date');
            $table->json('result_data');
            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'validated'])->default('pending')->index();

            $table->foreignId('inputted_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('validated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
