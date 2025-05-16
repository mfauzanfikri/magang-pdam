<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Files\File;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->seedUsers();
        $this->seedProposals();
        $this->seedAttendances();
        $this->seedActivities();
        $this->seedFinalReports();
        $this->seedCertificates();
    }
    
    private function seedUsers()
    {
        $users = [];
        
        // 1 admin + 1 supervisor
        $users[] = [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
        ];
        $users[] = [
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'supervisor',
            'status' => 'active',
        ];
        
        // 6 candidate, 6 intern, 6 graduate
        foreach(['candidate', 'intern', 'graduate'] as $role) {
            for($i = 1; $i <= 6; $i++) {
                $users[] = [
                    'name' => ucfirst($role) . ' ' . $i,
                    'email' => "{$role}{$i}@example.com",
                    'password' => password_hash('password', PASSWORD_DEFAULT),
                    'role' => $role,
                    'status' => 'active',
                ];
            }
        }
        
        $this->db->table('users')->insertBatch($users);
    }
    
    private function seedProposals()
    {
        helper('file');
        
        $faker = Factory::create();
        
        $users = $this->db->table('users')->get()->getResultArray();
        
        $grouped = ['candidate' => [], 'intern' => [], 'graduate' => []];
        $individuals = [];
        
        foreach($users as $user) {
            if(isset($grouped[$user['role']]) && count($grouped[$user['role']]) < 3) {
                $grouped[$user['role']][] = $user;
            } elseif(in_array($user['role'], ['candidate', 'intern', 'graduate'])) {
                $individuals[] = $user;
            }
        }
        
        $proposalMembers = [];
        
        foreach($grouped as $role => $members) {
            if(count($members) === 0) {
                continue;
            }
            
            $destinationDir = WRITEPATH . 'uploads/proposals/';
            if(ensure_directory($destinationDir, true)) {
                $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
                copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            }
            
            $leader = $members[0];
            $this->db->table('proposals')->insert([
                'leader_id' => $leader['id'],
                'title' => ucfirst($role) . ' Group Proposal',
                'institution' => $faker->company(),
                'is_group' => 1,
                'status' => $role === 'candidate' ? 'pending' : 'approved',
                'file_path' => str_replace(WRITEPATH, '', $filePath),
            ]);
            $proposalId = $this->db->insertID();
            
            foreach($members as $member) {
                $proposalMembers[] = [
                    'proposal_id' => $proposalId,
                    'user_id' => $member['id'],
                ];
            }
        }
        
        if(!empty($proposalMembers)) {
            $this->db->table('proposal_members')->insertBatch($proposalMembers);
        }
        
        $pendingIncluded = false;
        $rejectedIncluded = false;
        $individualProposals = [];
        foreach($individuals as $user) {
            $role = $user['role'];
            
            if($role !== 'candidate') {
                $status = 'approved';
            } else {
                // Force first two candidate statuses
                if(!$pendingIncluded) {
                    $status = 'pending';
                    $pendingIncluded = true;
                } elseif(!$rejectedIncluded) {
                    $status = 'rejected';
                    $rejectedIncluded = true;
                } else {
                    $status = $faker->randomElement(['pending', 'rejected']);
                }
            }
            
            $destinationDir = WRITEPATH . 'uploads/proposals/';
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            if(ensure_directory($destinationDir, true)) {
                copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            }
            
            $individualProposals[] = [
                'leader_id' => $user['id'],
                'title' => 'Individual Proposal - ' . $faker->sentence(2),
                'institution' => $faker->company(),
                'is_group' => 0,
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        if(!empty($individualProposals)) {
            $this->db->table('proposals')->insertBatch($individualProposals);
        }
    }
    
    private function seedFinalReports()
    {
        helper('file');
        
        $faker = Factory::create();
        
        $destinationDir = WRITEPATH . 'uploads/final-reports/';
        ensure_directory($destinationDir, true); // assumes you have a helper for this
        
        $statuses = ['pending', 'approved', 'rejected'];
        $data = [];
        
        // === GRADUATE FINAL REPORTS ===
        $graduateUsers = $this->db->table('users')
            ->where('role', 'graduate')
            ->get()->getResultArray();
        $graduateUserIds = array_column($graduateUsers, 'id');
        
        $graduateProposals = $this->db->table('proposals')
            ->whereIn('leader_id', $graduateUserIds)
            ->get()->getResultArray();
        
        foreach ($graduateProposals as $proposal) {
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
            $data[] = [
                'proposal_id' => $proposal['id'],
                'title' => $faker->sentence(3),
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'status' => 'approved',
                'note' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // === INTERN FINAL REPORTS ===
        $internUsers = $this->db->table('users')
            ->where('role', 'intern')
            ->get()->getResultArray();
        $internUserIds = array_column($internUsers, 'id');
        
        $internProposals = $this->db->table('proposals')
            ->whereIn('leader_id', $internUserIds)
            ->get()->getResultArray();
        
        // Assign one of each status first
        shuffle($internProposals); // randomize proposals
        foreach ($statuses as $i => $status) {
            $proposal = $internProposals[$i];
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
            $data[] = [
                'proposal_id' => $proposal['id'],
                'title' => $faker->sentence(3),
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'status' => $status,
                'note' => $status === 'rejected' ? $faker->sentence(6) : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // The rest get random statuses
        for ($i = count($statuses); $i < count($internProposals); $i++) {
            $proposal = $internProposals[$i];
            $status = $faker->randomElement($statuses);
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
            $data[] = [
                'proposal_id' => $proposal['id'],
                'title' => $faker->sentence(3),
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'status' => $status,
                'note' => $status === 'rejected' ? $faker->sentence(6) : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        // INSERT
        if (!empty($data)) {
            $this->db->table('final_reports')->insertBatch($data);
        }
    }
    
    private function seedActivities()
    {
        helper('file');
        
        $faker = Factory::create();
        
        $users = $this->db->table('users')
            ->whereIn('role', ['intern', 'graduate'])
            ->get()->getResultArray();
        
        $data = [];
        foreach($users as $user) {
            for($i = 0; $i < rand(3, 5); $i++) {
                $start = $faker->dateTimeBetween('-1 month', 'now');
                $end = (clone $start)->modify('+' . rand(0, 2) . ' days');
                
                $destinationDir = WRITEPATH . 'uploads/activities/';
                $filePath = $destinationDir . Uuid::uuid4()->toString() . '.jpg';
                if(ensure_directory($destinationDir, true)) {
                    copy(WRITEPATH . 'uploads/test.jpg', $filePath);
                }
                
                $data[] = [
                    'user_id' => $user['id'],
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(2),
                    'photo_path' => $filePath,
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        if(!empty($data)) {
            $this->db->table('activities')->insertBatch($data);
        }
    }
    
    private function seedAttendances()
    {
        $faker = Factory::create();
        
        $memberships = $this->db->table('proposal_members')->get()->getResultArray();
        $userProposalMap = [];
        foreach($memberships as $row) {
            $userProposalMap[$row['user_id']] = $row['proposal_id'];
        }
        
        $data = [];
        foreach($userProposalMap as $userId => $proposalId) {
            for($i = 0; $i < rand(5, 10); $i++) {
                $checkIn = $faker->dateTimeBetween('-3 weeks', 'now');
                $checkOut = (clone $checkIn)->modify('+' . rand(4, 8) . ' hours');
                
                $data[] = [
                    'proposal_id' => $proposalId,
                    'user_id' => $userId,
                    'date' => $checkIn->format('Y-m-d'),
                    'check_in' => $checkIn->format('H:i:s'),
                    'check_out' => $checkOut->format('H:i:s'),
                    'is_verified' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        if(!empty($data)) {
            $this->db->table('attendances')->insertBatch($data);
        }
    }
    
    private function seedCertificates()
    {
        helper('file');
        
        $graduates = $this->db->table('users')
            ->where('role', 'graduate')
            ->get()->getResultArray();
        
        $data = [];
        
        foreach($graduates as $user) {
            $proposal = $this->db->table('proposals')
                ->select('id')
                ->where('leader_id', $user['id'])
                ->get()
                ->getFirstRow();
            
            if(!$proposal) {
                $proposal = $this->db->table('proposal_members')
                    ->select('proposal_id id')
                    ->where('user_id', $user['id'])
                    ->get()
                    ->getFirstRow();
            }
            
            if(!$proposal) {
                continue;
            }
            
            $destinationDir = WRITEPATH . 'uploads/proposals/';
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            if(ensure_directory($destinationDir, true)) {
                copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            }
            
            $data[] = [
                'proposal_id' => $proposal->id,
                'user_id' => $user['id'],
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        if(!empty($data)) {
            $this->db->table('certificates')->insertBatch($data);
        }
    }
}
