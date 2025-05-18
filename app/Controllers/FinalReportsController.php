<?php

namespace App\Controllers;

use App\Libraries\AuthUser;
use App\Libraries\Authz;
use App\Models\CertificateModel;
use App\Models\FinalReportModel;
use App\Models\ProposalMemberModel;
use App\Models\ProposalModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class FinalReportsController extends BaseController
{
    private FinalReportModel $finalReportModel;
    private ProposalModel $proposalModel;
    private ProposalMemberModel $proposalMemberModel;
    private UserModel $userModel;
    private CertificateModel $certificateModel;
    
    public function __construct()
    {
        $this->finalReportModel = new FinalReportModel();
        $this->proposalModel = new ProposalModel();
        $this->proposalMemberModel = new ProposalMemberModel();
        $this->userModel = new UserModel();
        $this->certificateModel = new CertificateModel();
    }
    
    public function index()
    {
        $userId = AuthUser::id();
        
        // Get base query
        $query = $this->finalReportModel->withProposal()->withCertificate();
        
        if (Authz::any(['candidate', 'intern', 'graduate'])) {
            $query->belongsToUser($userId);
        }
        
        // Fetch and process data
        $raw = $query->orderBy('id', 'desc')->findAll();
        $finalReports = $this->finalReportModel->processJsonFields($raw);
        
        $data = [
            'title' => 'Final Reports',
        ];
        
        if (Authz::any(['admin', 'supervisor'])) {
            // Group by status for admin/supervisor view
            $finalReportsByStatus = [
                'pending' => [],
                'approved' => [],
                'rejected' => [],
            ];
            
            foreach ($finalReports as $finalReport) {
                $status = $finalReport['status'];
                $finalReportsByStatus[$status][] = $finalReport;
            }
            
            $data['finalReportsByStatus'] = $finalReportsByStatus;
        } else {
            // Intern/candidate/graduate view
            $data['finalReports'] = $finalReports;
            
            $activeProposal = $this->proposalModel
                ->belongsToUser($userId)
                ->active()
                ->first();
            
            $data['userHasActiveProposal'] = $activeProposal !== null;
            $data['isLeader'] = $activeProposal && $activeProposal['leader_id'] == $userId;
            
            $data['userHasPendingFinalReports'] = false;
            if ($data['isLeader'] && $activeProposal) {
                $data['userHasPendingFinalReports'] = $this->finalReportModel
                        ->where('proposal_id', $activeProposal['id'])
                        ->whereIn('status', ['pending', 'approved'])
                        ->countAllResults() > 0;
            }
        }
        
        return view('pages/final-reports/index', $data);
    }
    
    public function store()
    {
        $userId = AuthUser::id();
        
        // === Find active proposal where user is the leader ===
        $proposal = $this->proposalModel
            ->where('leader_id', $userId)
            ->active()
            ->first();
        
        if (!$proposal) {
            return redirect()->back()->with('errors', 'You are not allowed to submit a final report or you don’t have an active proposal.');
        }
        
        // === Validate inputs ===
        $rules = [
            'title' => 'required|max_length[100]',
            'file' => [
                'uploaded[file]',
                'ext_in[file,pdf]',
                'mime_in[file,application/pdf]',
                'max_size[file,5120]', // 5MB
            ],
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // === Upload the file ===
        $file = $this->request->getFile('file');
        $filename = \Ramsey\Uuid\Uuid::uuid4()->toString() . '.pdf';
        $file->move(WRITEPATH . 'uploads/final-reports', $filename);
        
        // === Save final report ===
        $this->finalReportModel->insert([
            'proposal_id' => $proposal['id'],
            'title' => $this->request->getPost('title'),
            'file_path' => 'uploads/final-reports/' . $filename,
            'status' => 'pending',
            'note' => null,
        ]);
        
        return redirect()->back()->withInput()->with('message', 'Final report submitted successfully.');
    }
    
    public function delete($id)
    {
        $user = $this->finalReportModel->find($id);
        
        if(!$user) {
            return redirect()->back()->withInput()->with('errors', 'Final report not found.');
        }
        
        $this->finalReportModel->delete($id);
        
        return redirect()->back()->withInput()->with('message', 'Final report deleted successfully.');
    }
    
    public function approval($id)
    {
        $finalReport = $this->finalReportModel->where('id', $id)->first();
        
        if (!$finalReport) {
            throw new PageNotFoundException();
        }
        
        $validationRules = [
            'approval' => 'required|in_list[approved,rejected]',
            'notes' => 'permit_empty|string'
        ];
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $approval = $this->request->getPost('approval');
        $notes = trim($this->request->getPost('notes') ?? '');
        $notes = $notes !== '' ? $notes : null;
        
        $this->finalReportModel->update($id, [
            'status' => $approval,
            'note' => $notes,
        ]);
        
        // If approved, change leader and members role to 'graduate'
        if ($approval === 'approved') {
            $proposal = $this->proposalModel->where('id', $finalReport['proposal_id'])->first();
            
            if ($proposal) {
                $userIds = [$proposal['leader_id']];
                
                // Add member IDs
                $memberIds = $this->proposalMemberModel
                    ->where('proposal_id', $proposal['id'])
                    ->findAll();
                
                foreach ($memberIds as $member) {
                    $userIds[] = $member['user_id'];
                }
                
                // Remove duplicates
                $userIds = array_unique($userIds);
                
                // Update roles
                $this->userModel
                    ->whereIn('id', $userIds)
                    ->set(['role' => 'graduate'])
                    ->update();
            }
        }
        
        return redirect()->back()->with('message', 'Final report has been ' . $approval . '.');
    }
    
    public function issueCertificate($id)
    {
        $finalReport = $this->finalReportModel
            ->withProposal()
            ->where('final_reports.id', $id)
            ->first();
        
        if (!$finalReport) {
            throw new PageNotFoundException('Final report not found.');
        }
        
        $decoded = $this->finalReportModel->processJsonFields([$finalReport])[0];
        $proposal = $decoded['proposal'];
        $userIds = [];
        
        // Collect all user IDs: leader and members
        if ($proposal['leader']) {
            $userIds[] = $proposal['leader']['id'];
        }
        
        foreach ($proposal['members'] as $member) {
            $userIds[] = $member['id'];
        }
        
        // Validate files for each user
        foreach ($userIds as $userId) {
            $fieldName = 'file_' . $userId;
            
            if (!$this->validate([
                $fieldName => [
                    'uploaded[' . $fieldName . ']',
                    'ext_in[' . $fieldName . ',pdf]',
                    'mime_in[' . $fieldName . ',application/pdf]',
                    'max_size[' . $fieldName . ',5120]'
                ]
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }
        
        $records = [];
        foreach ($userIds as $userId) {
            $fieldName = 'file_' . $userId;
            $file = $this->request->getFile($fieldName);
            
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = \Ramsey\Uuid\Uuid::uuid4()->toString() . '.pdf';
                $file->move(WRITEPATH . 'uploads/certificates', $newName);
                
                $records[] = [
                    'final_report_id' => $id,
                    'user_id' => $userId,
                    'file_path' => 'uploads/certificates/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        if (!empty($records)) {
            $this->certificateModel->insertBatch($records);
        }
        
        return redirect()->to('/final-reports')->with('message', 'Certificates issued successfully.');
    }
    
    public function getFile($id)
    {
        $finalReport = $this->finalReportModel->where('id', $id)->first();
        
        if ($finalReport && !empty($finalReport['file_path'])) {
            $fullPath = WRITEPATH . $finalReport['file_path'];
            
            if (file_exists($fullPath)) {
                $mimeType = mime_content_type($fullPath);
                return response()
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Length', filesize($fullPath))
                    ->setBody(file_get_contents($fullPath));
            }
        }
        
        throw PageNotFoundException::forPageNotFound("File not found.");
    }
    
    public function downloadFile($id)
    {
        $finalReport = $this->finalReportModel->where('id', $id)->first();
        
        if ($finalReport && !empty($finalReport['file_path'])) {
            $fullPath = WRITEPATH . $finalReport['file_path'];
            
            if (file_exists($fullPath)) {
                return response()->download($fullPath, $finalReport['title']);
            }
        }
        
        throw PageNotFoundException::forPageNotFound("File not found.");
    }
    
    public function getCertificateFile($finalReportId, $userId)
    {
        $certificate = $this->certificateModel
            ->where('final_report_id', $finalReportId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$certificate || empty($certificate['file_path'])) {
            throw new PageNotFoundException('Certificate not found.');
        }
        
        $fullPath = WRITEPATH . $certificate['file_path'];
        
        if (!is_file($fullPath)) {
            throw new PageNotFoundException('File not found.');
        }
        
        return response()
            ->setHeader('Content-Type', mime_content_type($fullPath))
            ->setHeader('Content-Length', filesize($fullPath))
            ->setBody(file_get_contents($fullPath));
    }
}
