<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helper_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('helper_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_category_id')->constrained();
            $table->decimal('rate', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['helper_profile_id', 'service_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helper_services');
    }
};
