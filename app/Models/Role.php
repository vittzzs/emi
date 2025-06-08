<?php

namespace App\Models;

use App\Traits\SecuritySistem\RoleSecurityTrait;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use RoleSecurityTrait;
}
