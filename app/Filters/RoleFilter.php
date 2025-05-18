<?php

namespace App\Filters;

use App\Libraries\AuthUser;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = AuthUser::role();
        
        if (!$userRole) {
            return redirect()->to('/login');
        }
        
        foreach ($arguments as $arg) {
            if (str_starts_with($arg, '!')) {
                $forbiddenRole = ltrim($arg, '!');
                if ($userRole === $forbiddenRole) {
                    // Forbidden role
                    throw PageNotFoundException::forPageNotFound();
                }
            } else {
                // If any allowed role matches, grant access
                if ($userRole === $arg) {
                    return;
                }
            }
        }
        
        // If no positive match and no specific block, deny
        throw PageNotFoundException::forPageNotFound();
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing
    }
}
