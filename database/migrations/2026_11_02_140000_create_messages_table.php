<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TASK 6 + TASK 7 - Table for the messages sent from the Contact form.
 * The Contact form (task 6) saves into it, the Messages menu (task 7) reads it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150);
            $table->string('subject', 150)->nullable();
            $table->text('message');
            $table->timestamps();   // created_at = sending time
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
