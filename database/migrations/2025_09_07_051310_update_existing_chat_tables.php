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
        // Actualizar tabla conversations
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->enum('type', ['private', 'group'])->default('private')->after('title');
            $table->timestamp('last_activity')->nullable()->after('type');
        });

        // Actualizar tabla messages
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('sender_id', 'user_id');
            $table->enum('type', ['text', 'image', 'file'])->default('text')->after('content');
            $table->boolean('is_read')->default(false)->after('type');
            $table->timestamp('read_at')->nullable()->after('is_read');
        });

        // Renombrar tabla conversation_participants a conversation_user
        Schema::rename('conversation_participants', 'conversation_user');
        
        // Actualizar la nueva tabla conversation_user
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->timestamp('joined_at')->nullable()->after('user_id');
            $table->timestamp('last_read_at')->nullable()->after('joined_at');
        });

        // Crear tabla notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['message', 'system', 'mention']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropColumn(['joined_at', 'last_read_at']);
        });
        
        Schema::rename('conversation_user', 'conversation_participants');
        
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_read', 'read_at']);
            $table->renameColumn('user_id', 'sender_id');
        });
        
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['title', 'type', 'last_activity']);
        });
    }
};
