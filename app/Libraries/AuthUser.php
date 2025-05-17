<?php

namespace App\Libraries;

class AuthUser
{
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        return session()->get('isLoggedIn') === true;
    }
    
    /**
     * Get the full user array from session
     */
    public static function user(): ?array
    {
        return session()->get('user') ?? null;
    }
    
    /**
     * Get a specific attribute (e.g., name, email, role)
     */
    public static function get(string $key, $default = null)
    {
        $user = self::user();
        return $user[$key] ?? $default;
    }
    
    /**
     * Get user ID
     */
    public static function id(): ?int
    {
        return self::get('id');
    }
    
    /**
     * Get user role
     */
    public static function role(): ?string
    {
        return self::get('role');
    }
    
    /**
     * Get user name
     */
    public static function name(): ?string
    {
        return self::get('name');
    }
    
    /**
     * Log out the current user
     */
    public static function logout(): void
    {
        session()->destroy();
    }
}
