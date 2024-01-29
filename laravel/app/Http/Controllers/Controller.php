<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function checkRole($user, $permission)
    {
        // if ($user->isAdmin == true) {
            return true;
        // }
        // if ($user->role && $user->role->status == 1) {
        //     $permissions = json_decode($user->role->permission, true);

        //     return in_array($permission, $permissions);
        // }

        // return false;
    }
}
