<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'signature',
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

    public function canAccessPanel(Panel $panel): bool
    {
        switch ($panel->getId()) {
            case 'admin':
                return $this->hasRole(Role::ADMIN);

            case 'finance':
                return $this->hasRole(Role::FINANCE);

            case 'logistics':
                return $this->hasRole(Role::LOGISTICS);

            case 'staff':
                return $this->hasRole(Role::STAFF);
            case 'meal':
                return $this->hasRole(Role::MEAL);
            case 'procurement':
                return $this->hasRole(Role::PROCUREMENT);
            case 'program':
                return $this->hasRole(Role::PROGRAM);
            case 'hr':
                return $this->hasRole(Role::HR);
        }
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->using(ProjectUser::class)
            ->withPivot('project_involvement_percentage')
            ->withTimestamps();
    }

    public function appraisals(): HasMany
    {
        return $this->hasMany(Appraisal::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'staff_id', 'id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function payroll(): HasOne
    {
        return $this->hasOne(Payroll::class);
    }

    public function staffDetail(): HasOne
    {
        return $this->hasOne(StaffDetail::class);
    }

    public function vehicleMovements(): HasMany
    {
        return $this->hasMany(VehicleMovement::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : 'https://ui-avatars.com/api/?name=' . $this->name;
    }

    public function sign()
    {
        return $this->signature ? $this->signature : 'https://ui-avatars.com/api/?rounded=true&name='.$this->name;

    }

    public function isHod(): bool
    {
        $inDepartment = Department::all()->pluck('hod_id')->toArray();

        return in_array($this->id, $inDepartment);
    }


}
