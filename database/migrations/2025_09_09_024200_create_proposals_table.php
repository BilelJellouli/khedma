<?php

declare(strict_types=1);

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
        Schema::create('proposals', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('mission_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('agent_id')->constrained()->cascadeOnDelete();
            $table->string('initiator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
