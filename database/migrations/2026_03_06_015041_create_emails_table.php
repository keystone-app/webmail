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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('imap_uid');
            $table->string('folder');
            $table->string('subject')->nullable();
            $table->string('from');
            $table->string('to');
            $table->dateTime('date');
            $table->longText('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'folder', 'imap_uid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
