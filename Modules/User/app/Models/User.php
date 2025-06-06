<?php

namespace Modules\User\App\Models; // Corrected Namespace

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Keep if using MustVerifyEmail interface
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable // Consider adding MustVerifyEmail if needed
{
    /** @use HasFactory<\Database\Factories\UserFactory> */ // Assuming default factory path, adjust if module has its own.
    use HasFactory, Notifiable;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    public static function newFactory() // Changed to public
    {
        return \Modules\User\Database\Factories\UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'first_name',
        'other_name',
        'username',
        'email', // Made nullable in migration
        'password',
        'nic',
        'date_of_birth',
        'gender',
        'designation',
        'default_role_id',
        'telephone',
        'last_seen_at',
        'online_status',
        'is_locked',
        'signature_path',
        'is_active',
        'email_verified_at', // Keep if using Laravel's verification
        // 'created_by', // Typically not mass assigned
        // 'updated_by', // Typically not mass assigned
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
            'date_of_birth' => 'date',
            'last_seen_at' => 'datetime',
            'is_locked' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials from the first_name.
     */
    public function initials(): string
    {
        if (empty($this->first_name)) {
            return '';
        }
        return Str::of($this->first_name)
            ->substr(0, 1)
            ->upper();
    }

    /**
     * Get the user who created this record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this record.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Example: Link to a Role model (adjust if your Role model is named differently or in a different module)
    // public function defaultRole()
    // {
    //    // Assuming 'default_role_id' stores an ID from a 'roles' table.
    //    // return $this->belongsTo(Role::class, 'default_role_id');
    // }
}
