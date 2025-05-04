<?php

namespace App\Models;

use CodeIgniter\Model;

class ProposalMemberModel extends Model
{
    protected $table            = 'proposal_members';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['proposal_id', 'user_id'];
    public $timestamps          = false;
}
