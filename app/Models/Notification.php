<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * The user this notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who triggered this notification.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Mark this notification as read.
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create a message notification.
     */
    public static function createMessageNotification($userId, $senderId, $conversationId, $messageContent)
    {
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => 'message',
            'title' => 'Nuevo mensaje',
            'message' => substr($messageContent, 0, 100) . (strlen($messageContent) > 100 ? '...' : ''),
            'data' => ['conversation_id' => $conversationId],
        ]);
    }
}
