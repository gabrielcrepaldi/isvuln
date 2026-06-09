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
        Schema::create('vulnerabilities', function (Blueprint $table) {
		$table->id();
		$table->string('title');
		$table->text('description');
		$table->enum('severity', ['critical', 'high', 'medium', 'low', 'info']);
		$table->enum('status', ['open', 'in_progress', 'resolved', 'accepted_risk'])->default('open');
		$table->string('target');
		$table->string('cve_id')->nullable();
		$table->text('proof_of_concept')->nullable();
		
		// Foreign keys — who created it, who it's assigned to
		$table->foreignId('created_by')->constrained('users')->onDelete('cascade');
		$table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
		$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
