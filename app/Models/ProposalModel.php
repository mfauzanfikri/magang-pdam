<?php

namespace App\Models;

use CodeIgniter\Model;

class ProposalModel extends Model
{
    protected $table            = 'proposals';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'title', 'institution', 'is_group', 'status',
        'leader_id', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
}
