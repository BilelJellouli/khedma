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
        Schema::create('agents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->text('experience')->nullable();
            $table->json('skills')->nullable();
            $table->string('availability');
            $table->boolean('verified')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('agent_service', function (Blueprint $table) {
            $table->foreignUuid('agent_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('service_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_service');
        Schema::dropIfExists('agents');
    }
};
