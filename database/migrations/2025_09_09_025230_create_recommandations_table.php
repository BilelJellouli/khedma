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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mission_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('agent_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('users')->cascadeOnDelete();
            $table->string('relation');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
