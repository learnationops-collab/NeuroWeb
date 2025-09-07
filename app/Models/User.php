<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Un usuario puede tener muchos roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a certain role.
     */
    public function hasRole(string $roleName)
    {
        return $this->roles->contains('name', $roleName)->exists();
    }

    /**
     * Get contacts based on user's role.
     */
    public function getContactsByRole()
    {
        $userRole = $this->roles->first()->name ?? 'estudiante';
        
        $contacts = [];
        
        switch ($userRole) {
            case 'estudiante':
                // Estudiantes solo ven neuro_team
                $contacts['Equipo NeuroWeb'] = User::whereHas('roles', function ($query) {
                    $query->where('name', 'neuro_team');
                })->with('roles')->get();
                break;
                
            case 'neuro_team':
                // Neuro_team ve admin y estudiantes (en 2 secciones)
                $contacts['Administradores'] = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->with('roles')->get();
                
                $contacts['Estudiantes'] = User::whereHas('roles', function ($query) {
                    $query->where('name', 'estudiante');
                })->with('roles')->get();
                break;
                
            case 'admin':
                // Admin solo ve neuro_team
                $contacts['Equipo NeuroWeb'] = User::whereHas('roles', function ($query) {
                    $query->where('name', 'neuro_team');
                })->with('roles')->get();
                break;
        }
        
        return $contacts;
    }

    /**
     * Conversations this user participates in.
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot(['joined_at', 'last_read_at'])
            ->withTimestamps()
            ->orderBy('last_activity', 'desc');
    }

    /**
     * Messages sent by this user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Notifications sent by this user.
     */
    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sender_id');
    }

    /**
     * Get or create a private conversation between two users.
     */
    public function getConversationWith($otherUserId)
    {
        return $this->conversations()
            ->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->where('type', 'private')
            ->first() ?? $this->createConversationWith($otherUserId);
    }

    /**
     * Create a new private conversation with another user.
     */
    private function createConversationWith($otherUserId)
    {
        $conversation = Conversation::create([
            'type' => 'private',
            'last_activity' => now(),
        ]);

        $conversation->users()->attach([
            $this->id => ['joined_at' => now()],
            $otherUserId => ['joined_at' => now()],
        ]);

        return $conversation;
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('read', false)->count();
    }
}
