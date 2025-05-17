<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table            = 'activities';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'user_id', 'title', 'description', 'photo_path',
        'start_date', 'end_date', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    
    /**
     * Join activities with user, returning JSON-encoded user column
     */
    public function withUser()
    {
        $userJson = "JSON_OBJECT(
            'id', users.id,
            'name', users.name,
            'email', users.email,
            'role', users.role
        ) AS user";
        
        return $this
            ->select("activities.*, {$userJson}", false)
            ->join('users', 'users.id = activities.user_id');
    }
    
    /**
     * Decodes the JSON user column into a nested array.
     */
    public function processJsonFields(array $rows): array
    {
        foreach ($rows as &$row) {
            $row['user'] = $row['user'] ? json_decode($row['user'], true) : null;
        }
        
        return $rows;
    }
}
