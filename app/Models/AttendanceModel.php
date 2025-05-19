<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendances';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'proposal_id', 'user_id', 'date',
        'check_in', 'check_out', 'status', 'verified_by',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    
    public function withRelations()
    {
        // Attendee (always required)
        $userSelect = "JSON_OBJECT(
        'id', IFNULL(user.id, ''),
        'name', IFNULL(user.name, ''),
        'email', IFNULL(user.email, ''),
        'role', IFNULL(user.role, '')
    ) AS user";
        
        // Verifier (nullable)
        $verifierSelect = "IF(attendances.verified_by IS NULL, NULL, JSON_OBJECT(
        'id', verifier.id,
        'name', verifier.name,
        'email', verifier.email,
        'role', verifier.role
    )) AS verified_by_user";
        
        // Leader
        $leaderSelect = "JSON_OBJECT(
        'id', IFNULL(leader.id, ''),
        'name', IFNULL(leader.name, ''),
        'email', IFNULL(leader.email, ''),
        'role', IFNULL(leader.role, '')
    )";
        
        // Members (workaround: GROUP_CONCAT)
        $membersSubquery = "(SELECT CONCAT('[', GROUP_CONCAT(
        JSON_OBJECT(
            'id', IFNULL(u.id, ''),
            'name', IFNULL(u.name, ''),
            'email', IFNULL(u.email, ''),
            'role', IFNULL(u.role, '')
        )
    ), ']')
    FROM proposal_members pm
    JOIN users u ON u.id = pm.user_id
    WHERE pm.proposal_id = proposals.id)";
        
        // Proposal with embedded leader & members
        $proposalSelect = "JSON_OBJECT(
        'id', IFNULL(proposals.id, ''),
        'title', IFNULL(proposals.title, ''),
        'institution', IFNULL(proposals.institution, ''),
        'is_group', IFNULL(proposals.is_group, 0),
        'status', IFNULL(proposals.status, ''),
        'leader', $leaderSelect,
        'members', $membersSubquery
    ) AS proposal";
        
        return $this
            ->select("attendances.*, $userSelect, $verifierSelect, $proposalSelect", false)
            ->join('users user', 'user.id = attendances.user_id')
            ->join('users verifier', 'verifier.id = attendances.verified_by', 'left')
            ->join('proposals', 'proposals.id = attendances.proposal_id')
            ->join('users leader', 'leader.id = proposals.leader_id');
    }
    
    public function processJsonFields(array $rows): array
    {
        foreach ($rows as &$row) {
            $row['user'] = !empty($row['user']) ? json_decode($row['user'], true) : null;
            $row['verified_by_user'] = !empty($row['verified_by_user']) ? json_decode($row['verified_by_user'], true) : null;
            $row['proposal'] = !empty($row['proposal']) ? json_decode($row['proposal'], true) : null;
            
            if (is_array($row['proposal'])) {
                // is_group as boolean
                if (isset($row['proposal']['is_group'])) {
                    $row['proposal']['is_group'] = (bool) $row['proposal']['is_group'];
                }
                
                // members as array or []
                if (!is_array($row['proposal']['members'])) {
                    $row['proposal']['members'] = json_decode($row['proposal']['members'], true) ?? [];
                }
                
                // leader as object or null
                if (!is_array($row['proposal']['leader'])) {
                    $row['proposal']['leader'] = json_decode($row['proposal']['leader'], true) ?? null;
                }
            }
        }
        
        return $rows;
    }
}
