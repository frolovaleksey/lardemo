<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Auth;

trait PermissionHelperTrait
{
    public function userCan(string $permission): bool
    {
        return (Auth::user() && Auth::user()->can($permission));
    }

    public function abortNotCan(string $permission)
    {
        if( !$this->userCan($permission) ){
            abort(403);
        }
    }
}
