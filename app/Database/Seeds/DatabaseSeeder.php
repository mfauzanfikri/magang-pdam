<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use DateTime;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->prepareDirectories();
        
        $this->seedUsers();
        $this->seedProposals();
        $this->seedAttendances();
        $this->seedActivities();
        $this->seedFinalReports();
        $this->seedCertificates();
    }
    
    private function prepareDirectories()
    {
        helper('file');
        
        $dirs = [
            WRITEPATH . 'uploads/proposals',
            WRITEPATH . 'uploads/final-reports',
            WRITEPATH . 'uploads/activities',
            WRITEPATH . 'uploads/certificates',
        ];
        
        foreach ($dirs as $dir) {
            ensure_directory($dir, true, true);
        }
    }
    
    private function seedUsers()
    {
        $users = [];
        
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
        
        foreach (['candidate', 'intern', 'graduate'] as $role) {
            for ($i = 1; $i <= 6; $i++) {
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
        $faker = Factory::create();
        $users = $this->db->table('users')->get()->getResultArray();
        
        $grouped = ['candidate' => [], 'intern' => [], 'graduate' => []];
        $individuals = [];
        
        foreach ($users as $user) {
            if (isset($grouped[$user['role']]) && count($grouped[$user['role']]) < 3) {
                $grouped[$user['role']][] = $user;
            } elseif (in_array($user['role'], ['candidate', 'intern', 'graduate'])) {
                $individuals[] = $user;
            }
        }
        
        $proposalMembers = [];
        
        foreach ($grouped as $role => $members) {
            if (count($members) === 0) continue;
            
            $filePath = WRITEPATH . 'uploads/proposals/' . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
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
            
            foreach ($members as $member) {
                if ($member['id'] === $leader['id']) continue;
                
                $proposalMembers[] = [
                    'proposal_id' => $proposalId,
                    'user_id' => $member['id'],
                ];
            }
        }
        
        if (!empty($proposalMembers)) {
            $this->db->table('proposal_members')->insertBatch($proposalMembers);
        }
        
        $pendingIncluded = false;
        $rejectedIncluded = false;
        $individualProposals = [];
        
        foreach ($individuals as $user) {
            $status = 'approved';
            if ($user['role'] === 'candidate') {
                if (!$pendingIncluded) {
                    $status = 'pending';
                    $pendingIncluded = true;
                } elseif (!$rejectedIncluded) {
                    $status = 'rejected';
                    $rejectedIncluded = true;
                } else {
                    $status = $faker->randomElement(['pending', 'rejected']);
                }
            }
            
            $filePath = WRITEPATH . 'uploads/proposals/' . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
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
        
        if (!empty($individualProposals)) {
            $this->db->table('proposals')->insertBatch($individualProposals);
        }
    }
    
    private function seedFinalReports()
    {
        $faker = Factory::create();
        $destinationDir = WRITEPATH . 'uploads/final-reports/';
        $statuses = ['pending', 'approved', 'rejected'];
        $data = [];
        
        $graduateUserIds = array_column(
            $this->db->table('users')->where('role', 'graduate')->get()->getResultArray(),
            'id'
        );
        
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
        
        $internUserIds = array_column(
            $this->db->table('users')->where('role', 'intern')->get()->getResultArray(),
            'id'
        );
        
        $internProposals = $this->db->table('proposals')
            ->whereIn('leader_id', $internUserIds)
            ->get()->getResultArray();
        
        shuffle($internProposals);
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
        
        if (!empty($data)) {
            $this->db->table('final_reports')->insertBatch($data);
        }
    }
    
    private function seedActivities()
    {
        $faker = Factory::create();
        $destinationDir = WRITEPATH . 'uploads/activities/';
        $data = [];
        
        // Get intern and graduate users
        $users = $this->db->table('users')
            ->whereIn('role', ['intern', 'graduate'])
            ->get()->getResultArray();
        
        foreach ($users as $user) {
            // Get user's approved proposal (as leader or member)
            $proposal = $this->db->table('proposals')
                ->where('status', 'approved')
                ->groupStart()
                ->where('leader_id', $user['id'])
                ->orWhereIn('id', function ($builder) use ($user) {
                    return $builder->select('proposal_id')
                        ->from('proposal_members')
                        ->where('user_id', $user['id']);
                })
                ->groupEnd()
                ->orderBy('id', 'desc')
                ->get()->getFirstRow();
            
            // Skip if no proposal
            if (!$proposal) continue;
            
            // Generate 3–5 activities per user
            for ($i = 0; $i < rand(3, 5); $i++) {
                $start = $faker->dateTimeBetween('-1 month', 'now');
                $end = (clone $start)->modify('+' . rand(0, 2) . ' days');
                $filePath = $destinationDir . Uuid::uuid4()->toString() . '.jpg';
                copy(WRITEPATH . 'uploads/test.jpg', $filePath);
                
                $data[] = [
                    'user_id' => $user['id'],
                    'proposal_id' => $proposal->id,
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(2),
                    'photo_path' => str_replace(WRITEPATH, '', $filePath),
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        if (!empty($data)) {
            $this->db->table('activities')->insertBatch($data);
        }
    }
    
    private function seedAttendances()
    {
        $faker = Factory::create();
        $verifier = $this->db->table('users')->where('role', 'admin')->get()->getRowArray();
        $verifierId = $verifier['id'] ?? null;
        
        $proposals = $this->db->table('proposals')
            ->whereIn('status', ['approved', 'rejected'])
            ->get()->getResultArray();
        
        $memberships = $this->db->table('proposal_members')->get()->getResultArray();
        $proposalMembers = [];
        foreach ($memberships as $row) {
            $proposalMembers[$row['proposal_id']][] = $row['user_id'];
        }
        
        $usedUserIds = [];
        $data = [];
        
        foreach ($proposals as $proposal) {
            $userIds = $proposal['is_group']
                ? array_merge([$proposal['leader_id']], $proposalMembers[$proposal['id']] ?? [])
                : [$proposal['leader_id']];
            
            foreach (array_unique($userIds) as $userId) {
                $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
                
                // Only interns and graduates
                if (!in_array($user['role'], ['intern', 'graduate'])) {
                    continue;
                }
                
                if (in_array($userId, $usedUserIds)) continue;
                $usedUserIds[] = $userId;
                
                // Random date range: last 10 working days (Monday–Friday)
                $dates = [];
                $startDate = (new DateTime('-14 days'))->modify('next Monday');
                while (count($dates) < 10) {
                    if (!in_array($startDate->format('N'), ['6', '7'])) {
                        $dates[] = clone $startDate;
                    }
                    $startDate->modify('+1 day');
                }
                
                foreach ($dates as $day) {
                    $checkIn = $day->setTime(rand(8, 8), rand(0, 59), 0);
                    $checkOut = (clone $day)->setTime(rand(16, 16), rand(0, 59), 0);
                    
                    $status = $faker->randomElement(['verified', 'unverified', 'rejected']);
                    if ($user['role'] === 'graduate') {
                        $status = 'verified';
                    }
                    
                    $data[] = [
                        'proposal_id' => $proposal['id'],
                        'user_id' => $userId,
                        'date' => $day->format('Y-m-d'),
                        'check_in' => $checkIn->format('H:i:s'),
                        'check_out' => $checkOut->format('H:i:s'),
                        'status' => $status,
                        'verified_by' => ($status === 'verified' && $verifierId) ? $verifierId : null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }
        
        if (!empty($data)) {
            $this->db->table('attendances')->insertBatch($data);
        }
    }
    
    private function seedCertificates()
    {
        $graduates = $this->db->table('users')->where('role', 'graduate')->get()->getResultArray();
        $destinationDir = WRITEPATH . 'uploads/certificates/';
        $data = [];
        
        foreach ($graduates as $user) {
            // Find user's proposal
            $proposal = $this->db->table('proposals')
                ->select('id')->where('leader_id', $user['id'])->get()->getFirstRow();
            
            if (!$proposal) {
                $proposal = $this->db->table('proposal_members')
                    ->select('proposal_id id')
                    ->where('user_id', $user['id'])
                    ->get()->getFirstRow();
            }
            
            if (!$proposal) continue;
            
            // Find approved final report for the proposal
            $finalReport = $this->db->table('final_reports')
                ->select('id')
                ->where('proposal_id', $proposal->id)
                ->where('status', 'approved')
                ->get()
                ->getFirstRow();
            
            if (!$finalReport) continue;
            
            // Generate file
            $filePath = $destinationDir . Uuid::uuid4()->toString() . '.pdf';
            copy(WRITEPATH . 'uploads/test.pdf', $filePath);
            
            $data[] = [
                'final_report_id' => $finalReport->id, // updated foreign key
                'user_id' => $user['id'],
                'file_path' => str_replace(WRITEPATH, '', $filePath),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        if (!empty($data)) {
            $this->db->table('certificates')->insertBatch($data);
        }
    }
}
