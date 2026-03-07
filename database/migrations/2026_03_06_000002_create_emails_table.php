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
            $table->foreignUuid('account_id')->constrained('mail_accounts')->onDelete('cascade');
            $table->string('message_id')->nullable()->index();
            $table->unsignedBigInteger('uid')->index();
            $table->string('folder')->index();
            $table->text('subject')->nullable();
            $table->string('from_email')->index();
            $table->string('sender_name')->nullable(); // Renamed from 'from'
            $table->text('recipients')->nullable(); // Renamed from 'to'
            $table->dateTime('sent_at')->index(); // Renamed from 'date'
            $table->boolean('is_seen')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->string('thread_id')->nullable()->index();
            $table->timestamps();

            // Unique constraint to avoid duplicating same message in same folder
            $table->unique(['account_id', 'folder', 'uid']);
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
