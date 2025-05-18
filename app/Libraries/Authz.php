<?php

namespace App\Libraries;

use App\Libraries\AuthUser;
use CodeIgniter\Exceptions\PageNotFoundException;

class Authz
{
    // Define your roles and permissions centrally
    public static function is(string $role): bool
    {
        return AuthUser::role() === $role;
    }
    
    public static function any(array $roles): bool
    {
        return in_array(AuthUser::role(), $roles);
    }
    
    public static function denyIfNot(string|array $roles)
    {
        $allowed = is_array($roles) ? self::any($roles) : self::is($roles);
        
        if(!$allowed) {
            throw PageNotFoundException::forPageNotFound();
        }
    }
    
    public static function can(string $permission): bool
    {
        // Extend this for real permission check logic
        $map = [
            'edit-report' => ['admin', 'supervisor'],
            'view-dashboard' => ['admin', 'intern', 'supervisor'],
        ];
        
        return isset($map[$permission]) && self::any($map[$permission]);
    }
    
    public static function denyIfCannot(string $permission)
    {
        if(!self::can($permission)) {
            throw PageNotFoundException::forPageNotFound();
        }
    }
}
