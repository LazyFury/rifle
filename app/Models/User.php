<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

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

    protected $json = [
        'email',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function searchable()
    {
        return [
            'id',
            'name',
            'email',
        ];
    }


    public function getPermissionsViaRoles(): Collection
    {
        $query = RoleHasPermission::where("role_id", $this->roles->pluck('id'))->whereHas('permission', function ($query) {
            $query->where('enabled', '1');
        })->with('permission');

        $permissions = Cache::remember('user_permissions_' . $this->id, now()->addMinutes(10), function () use ($query) {
            $permissions = $query->get();
            $permissions = $permissions->map(function ($item) {
                return $item->permission->name;
            });
            return $permissions;
        });

        // logger("permissions", [$permissions]);
        return $permissions;
    }
}
