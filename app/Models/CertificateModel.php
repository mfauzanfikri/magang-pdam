<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table            = 'certificates';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'user_id', 'final_report_id', 'file_path',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
}
