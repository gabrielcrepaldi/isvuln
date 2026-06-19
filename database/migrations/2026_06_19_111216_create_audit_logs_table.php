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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
       	    $table->string('action');                    // created, updated, deleted, login, logout, login_failed
            $table->string('auditable_type')->nullable(); // App\Models\Vulnerability, etc.
            $table->unsignedBigInteger('auditable_id')->nullable();
	    $table->json('old_values')->nullable();
	    $table->json('new_values')->nullable();
	    $table->string('ip_address', 45)->nullable(); // 45 chars fits IPv6
	    $table->text('user_agent')->nullable();
	    $table->timestamp('created_at')->useCurrent(); // only created_at — logs are never updated
	    $table->index(['auditable_type', 'auditable_id']);
	    $table->index('user_id');
	    $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
