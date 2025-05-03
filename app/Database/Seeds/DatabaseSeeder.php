<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

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
        
        $proposalData = [];
        $proposalMembers = [];
        
        // Buat 3 proposal grup
        foreach($grouped as $role => $members) {
            if(count($members) === 0) {
                continue; // Skip jika tidak ada anggota
            }
            
            $leader = $members[0];
            $this->db->table('proposals')->insert([
                'leader_id' => $leader['id'], // ✅ Perubahan di sini
                'title' => ucfirst($role) . ' Group Proposal',
                'is_group' => 1,
                'created_at' => date('Y-m-d H:i:s'),
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
        
        // Buat proposal individu
        $individualProposals = [];
        foreach($individuals as $user) {
            $individualProposals[] = [
                'leader_id' => $user['id'], // ✅ Perubahan di sini
                'title' => 'Individual Proposal - ' . $faker->sentence(2),
                'is_group' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        if(!empty($individualProposals)) {
            $this->db->table('proposals')->insertBatch($individualProposals);
        }
    }
    
    private function seedFinalReports()
    {
        $faker = Factory::create();
        
        $graduateUsers = $this->db->table('users')
            ->where('role', 'graduate')
            ->get()->getResultArray();
        $graduateUserIds = array_column($graduateUsers, 'id');
        
        $graduateProposals = $this->db->table('proposals')
            ->whereIn('leader_id', $graduateUserIds)
            ->get()->getResultArray();
        
        $data = [];
        foreach($graduateProposals as $proposal) {
            $data[] = [
                'proposal_id' => $proposal['id'],
                'file_path' => 'uploads/reports/' . $faker->uuid() . '.pdf',
                'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        
        if(!empty($data)) {
            $this->db->table('final_reports')->insertBatch($data);
        }
    }
    
    private function seedActivities()
    {
        $faker = Factory::create();
        
        $users = $this->db->table('users')
            ->whereIn('role', ['intern', 'graduate'])
            ->get()->getResultArray();
        
        $data = [];
        foreach($users as $user) {
            for($i = 0; $i < rand(3, 5); $i++) {
                $start = $faker->dateTimeBetween('-1 month', 'now');
                $end = (clone $start)->modify('+' . rand(0, 2) . ' days');
                
                $data[] = [
                    'user_id' => $user['id'],
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(2),
                    'photo_path' => 'uploads/photos/' . $faker->uuid() . '.jpg',
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
        $faker = Factory::create();
        
        // semua graduate
        $graduates = $this->db->table('users')
            ->where('role', 'graduate')
            ->get()->getResultArray();
        
        $data = [];
        
        foreach ($graduates as $user) {
            /**
             * 1️⃣  Cari proposal di mana dia KETUA.
             * 2️⃣  Jika tidak ada, cari proposal apa pun di mana dia anggota (proposal_members).
             * 3️⃣  Jika tetap tidak ketemu, skip – supaya tidak melanggar FK.
             */
            $proposal = $this->db->table('proposals')
                ->select('id')
                ->where('leader_id', $user['id'])
                ->get()
                ->getFirstRow();
            
            if (! $proposal) {
                $proposal = $this->db->table('proposal_members')
                    ->select('proposal_id id')
                    ->where('user_id', $user['id'])
                    ->get()
                    ->getFirstRow();
            }
            
            if (! $proposal) {
                // graduate ini belum punya proposal yang sah; lewati agar tidak men‑trigger FK error
                continue;
            }
            
            // Buat PDF palsu
            $fileName = 'certificate_' . $faker->uuid . '.pdf';
            $relative = 'uploads/certificates/' . $fileName;
            $absolute = WRITEPATH . $relative;
            
            if (! is_dir(dirname($absolute))) {
                mkdir(dirname($absolute), 0777, true);
            }
            file_put_contents($absolute, '%PDF‑1.4 Fake PDF');
            
            $data[] = [
                'proposal_id' => $proposal->id,   // ✅ sekarang diisi
                'user_id'     => $user['id'],
                'file_path'   => $relative,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];
        }
        
        if (! empty($data)) {
            $this->db->table('certificates')->insertBatch($data);
        }
    }
}
