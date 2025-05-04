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
}
