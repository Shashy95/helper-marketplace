<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('helper_profile_id')->constrained();
            $table->foreignId('service_category_id')->constrained();
            $table->date('requested_date');
            $table->time('requested_time');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('address_note')->nullable();
            $table->decimal('agreed_price', 10, 2)->nullable();
            $table->enum('status', [
                'requested', 'accepted', 'declined',
                'in_progress', 'completed', 'cancelled',
            ])->default('requested');
            $table->timestamps();

            $table->index(['helper_profile_id', 'status']);
            $table->index(['client_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
