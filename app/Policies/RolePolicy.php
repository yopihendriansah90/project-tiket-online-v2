<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Super Admin secara otomatis mendapatkan akses via AuthServiceProvider.
     * Untuk semua user lain, kita akan tolak secara default.
     */

    public function viewAny(User $user): bool
    {
        // Hanya Super Admin yang bisa melihat daftar role
        return false;
    }

    public function view(User $user, Role $role): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Role $role): bool
    {
        return false;
    }

    public function delete(User $user, Role $role): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    // Dan seterusnya untuk method lain...
}