<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'proposal_id', 'user_id', 'date',
        'check_in', 'check_out', 'is_verified',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
}
