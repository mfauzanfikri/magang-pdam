<?php

namespace App\Models;

use CodeIgniter\Model;

class ProposalModel extends Model
{
    protected $table = 'proposals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'institution', 'is_group', 'status',
        'leader_id', 'file_path', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected array $casts = [
        'is_group' => 'boolean',
    ];
    
    public function withMembers()
    {
        $subquery = "(SELECT CONCAT('[', GROUP_CONCAT(
                    JSON_OBJECT(
                        'id', u.id,
                        'name', u.name,
                        'email', u.email,
                        'role', u.role
                    )
                ), ']')
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
            ->select("proposals.*, $leaderSelect, $subquery AS members", false)
            ->join('users leader', 'leader.id = proposals.leader_id');
    }
    
    public function processJsonFields(array $rows): array
    {
        foreach ($rows as &$row) {
            $row['members'] = $row['members'] ? json_decode($row['members'], true) : [];
            $row['leader'] = $row['leader'] ? json_decode($row['leader'], true) : null;
        }
        return $rows;
    }
    
    public function belongsToUser(int $userId): self
    {
        return $this->groupStart()
            ->where('leader_id', $userId)
            ->orWhereIn('proposals.id', function($builder) use ($userId) {
                return $builder->select('proposal_id')
                    ->from('proposal_members')
                    ->where('user_id', $userId);
            })
            ->groupEnd();
    }
    
    public function active()
    {
        return $this->whereNotIn('proposals.id', function($builder) {
            return $builder->select('proposal_id')
                ->from('final_reports')
                ->whereIn('status', ['approved']);
        });
    }
}
