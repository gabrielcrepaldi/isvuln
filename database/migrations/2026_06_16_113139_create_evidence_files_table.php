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
        Schema::create('evidence_files', function (Blueprint $table) {
	   $table->id();
	   $table->foreignId('vulnerability_id')->constrained()->onDelete('cascade');
           $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
           $table->string('original_name');
           $table->string('stored_path');
           $table->string('mime_type')->nullable();
           $table->unsignedBigInteger('size')->nullable();
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence_files');
    }
};

