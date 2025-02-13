<?php

namespace App\Http\Controllers;

use App\Services\User\PermissionHelperTrait;

abstract class Controller
{
    use PermissionHelperTrait;
}
