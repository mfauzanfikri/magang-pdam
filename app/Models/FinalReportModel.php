<?php

namespace App\Models;

use CodeIgniter\Model;

class FinalReportModel extends Model
{
    protected $table            = 'final_reports';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'proposal_id', 'file_path', 'status',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
}
