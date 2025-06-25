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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->morphs('profileable');
            $table->foreignId('document_type_id')->nullable()->constrained()->onDelete('set null');
            $table->string('document_number', 11)->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->json('adicional_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
