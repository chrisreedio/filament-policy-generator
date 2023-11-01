<?php


namespace {{Namespace}}\Policies;

use {{Namespace}}\Models\User;
use {{Namespace}}\Models\{{Model}};
use Illuminate\Auth\Access\HandlesAuthorization;

class {{Model}}Policy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        //
    }

    public function view(?User $user, {{Model}} ${{modelVariable}}): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, {{Model}} ${{modelVariable}}): bool
    {
        //
    }

    public function delete(User $user, {{Model}} ${{modelVariable}}): bool
    {
        //
    }

    public function restore(User $user, {{Model}} ${{modelVariable}}): bool
    {
        //
    }

    public function forceDelete(User $user, {{Model}} ${{modelVariable}}): bool
    {
        //
    }
}
