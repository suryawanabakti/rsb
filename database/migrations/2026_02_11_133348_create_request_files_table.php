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
        Schema::create('request_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('letter_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_name');
            $table->string('file_path');

            // best practice tambahan
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->timestamp('uploaded_at')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_files');
    }
};
