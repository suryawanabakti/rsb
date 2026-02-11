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
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('letter_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('submission_date');

            $table->enum('status', [
                'draft',
                'submitted',
                'verified',
                'approved',
                'rejected',
                'completed'
            ])->default('submitted')->index();

            $table->text('admin_notes')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_requests');
    }
};
