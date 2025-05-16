<?php

namespace App\Models;

use CodeIgniter\Model;

class FinalReportModel extends Model
{
    protected $table = 'final_reports';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'proposal_id', 'title', 'file_path', 'status',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    
    public function withProposal()
    {
        $membersSubquery = "(SELECT JSON_ARRAYAGG(JSON_OBJECT(
                                'id', u.id,
                                'name', u.name,
                                'email', u.email,
                                'role', u.role
                            ))
                            FROM proposal_members pm
                            JOIN users u ON u.id = pm.user_id
                            WHERE pm.proposal_id = proposals.id)";
        
        $leaderSelect = "JSON_OBJECT(
                            'id', leader.id,
                            'name', leader.name,
                            'email', leader.email,
                            'role', leader.role
                        ) AS leader";
        
        return $this
            ->select("final_reports.*,
                          proposals.id AS proposal_id,
                          proposals.title AS proposal_title,
                          proposals.institution,
                          proposals.is_group,
                          proposals.status AS proposal_status,
                          $leaderSelect,
                          $membersSubquery AS members", false)
            ->join('proposals', 'proposals.id = final_reports.proposal_id')
            ->join('users leader', 'leader.id = proposals.leader_id');
    }
    
    public function processJsonFields(array $rows): array
    {
        foreach ($rows as &$row) {
            // Decode nested JSON fields
            $members = $row['members'] ? json_decode($row['members'], true) : [];
            $leader = $row['leader'] ? json_decode($row['leader'], true) : null;
            
            // Move proposal fields under 'proposal'
            $row['proposal'] = [
                'id' => $row['proposal_id'],
                'title' => $row['proposal_title'],
                'institution' => $row['institution'],
                'is_group' => (bool) $row['is_group'],
                'status' => $row['proposal_status'],
                'members' => $members,
                'leader' => $leader
            ];
            
            // Clean up
            unset(
                $row['proposal_id'],
                $row['proposal_title'],
                $row['institution'],
                $row['is_group'],
                $row['proposal_status'],
                $row['members'],
                $row['leader']
            );
        }
        
        return $rows;
    }
}
