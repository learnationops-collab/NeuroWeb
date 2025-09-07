<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * Users in this conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['joined_at', 'last_read_at'])
            ->withTimestamps();
    }

    /**
     * Messages in this conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Latest message in this conversation.
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Get the other participant in a private conversation.
     */
    public function getOtherParticipant($currentUserId)
    {
        return $this->users()->where('user_id', '!=', $currentUserId)->first();
    }

    /**
     * Get unread messages count for a specific user.
     */
    public function getUnreadCount($userId)
    {
        $lastReadAt = $this->users()->where('user_id', $userId)->first()?->pivot?->last_read_at;
        
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->when($lastReadAt, function ($query) use ($lastReadAt) {
                $query->where('created_at', '>', $lastReadAt);
            })
            ->count();
    }

    /**
     * Mark conversation as read for a user.
     */
    public function markAsRead($userId)
    {
        $this->users()->updateExistingPivot($userId, [
            'last_read_at' => now()
        ]);
    }
}
