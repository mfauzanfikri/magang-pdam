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
        $proposalAlias = 'p';
        
        $membersSubquery = "(SELECT CONCAT('[', GROUP_CONCAT(
        JSON_OBJECT(
            'id', u.id,
            'name', u.name,
            'email', u.email,
            'role', u.role
        )
    ), ']')
    FROM proposal_members pm
    JOIN users u ON u.id = pm.user_id
    WHERE pm.proposal_id = {$proposalAlias}.id)";
        
        $leaderSelect = "JSON_OBJECT(
        'id', leader.id,
        'name', leader.name,
        'email', leader.email,
        'role', leader.role
    ) AS leader";
        
        return $this
            ->select("final_reports.*,
                  {$proposalAlias}.id AS proposal_id,
                  {$proposalAlias}.title AS proposal_title,
                  {$proposalAlias}.institution,
                  {$proposalAlias}.is_group,
                  {$proposalAlias}.status AS proposal_status,
                  $leaderSelect,
                  $membersSubquery AS members", false)
            ->join("proposals {$proposalAlias}", "{$proposalAlias}.id = final_reports.proposal_id")
            ->join('users leader', "leader.id = {$proposalAlias}.leader_id");
    }
    
    public function belongsToUser(int $userId)
    {
        return $this
            ->distinct() // eliminate duplicate final_reports
            ->join('proposals', 'proposals.id = final_reports.proposal_id')
            ->groupStart()
            ->where('proposals.leader_id', $userId)
            ->orWhereIn('proposals.id', function ($builder) use ($userId) {
                return $builder->select('proposal_id')
                    ->from('proposal_members')
                    ->where('user_id', $userId);
            })
            ->groupEnd();
    }
    
    public function processJsonFields(array $rows): array
    {
        foreach ($rows as &$row) {
            $members = $row['members'] ? json_decode($row['members'], true) : [];
            $leader = $row['leader'] ? json_decode($row['leader'], true) : null;
            
            $row['proposal'] = [
                'id' => $row['proposal_id'],
                'title' => $row['proposal_title'],
                'institution' => $row['institution'],
                'is_group' => (bool) $row['is_group'],
                'status' => $row['proposal_status'],
                'members' => $members,
                'leader' => $leader
            ];
            
            unset(
                $row['proposal_id'],
                $row['proposal_title'],
                $row['institution'],
                $row['is_group'],
                $row['proposal_status'],
                $row['members'],
                $row['leader']
            );
            
            // Determine if all users received certificates using final_report_id now
            $row['is_certificate_issued'] = false;
            
            $allUserIds = array_map(fn($u) => $u['id'], $members);
            if ($leader) {
                $allUserIds[] = $leader['id'];
            }
            
            if (!empty($allUserIds)) {
                $foundCerts = $this->db->table('certificates')
                    ->where('final_report_id', $row['id']) // use final_report_id
                    ->whereIn('user_id', $allUserIds)
                    ->countAllResults();
                
                $row['is_certificate_issued'] = ($foundCerts === count($allUserIds));
            }
        }
        
        return $rows;
    }
    
    public function withCertificate()
    {
        return $this->join('certificates', 'certificates.final_report_id = final_reports.id', 'left');
    }
}
