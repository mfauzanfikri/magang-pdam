<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\AuthUser;
use App\Libraries\Authz;
use App\Models\ProposalMemberModel;
use App\Models\ProposalModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class ProposalsController extends BaseController
{
    private ProposalModel $proposalModel;
    private ProposalMemberModel $proposalMemberModel;
    private UserModel $userModel;
    
    public function __construct()
    {
        $this->proposalModel = new ProposalModel();
        $this->proposalMemberModel = new ProposalMemberModel();
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $query = $this->proposalModel
            ->withMembers();
        
        if(Authz::any(['candidate', 'intern', 'graduate'])) {
            $query->belongsToUser(AuthUser::id());
        }
        
        $raw = $query->findAll();
        $proposals = $this->proposalModel->processJsonFields($raw);
        
        $data = [
            'title' => 'Proposals',
        ];
        
        if(Authz::any(['admin', 'supervisor'])) {
            $proposalsByStatus = [
                'pending' => [],
                'approved' => [],
                'rejected' => [],
            ];
            
            foreach($proposals as $proposal) {
                $status = $proposal['status'];
                $proposalsByStatus[$status][] = $proposal;
            }
            
            $data['proposalByStatus'] = $proposalsByStatus;
        } else {
            $data['proposals'] = $proposals;
            $data['userHasActiveProposal'] = $this->proposalModel->belongsToUser(AuthUser::id())->active()->first() !== null;
        }
        
        return view('pages/proposals/index', $data);
    }
    
    public function store()
    {
        $isGroup = $this->request->getPost('is_group') !== null;
        
        $rules = [
            'title' => 'required|max_length[100]',
            'institution' => 'required|max_length[100]',
            'file' => [
                'uploaded[file]',
                'ext_in[file,pdf]',
                'mime_in[file,application/pdf]',
                'max_size[file,5120]', // 5MB max
            ],
        ];
        
        if ($isGroup) {
            $rules['members'] = 'required';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userId = AuthUser::id();
        $emails = [];
        
        if ($isGroup) {
            $emails = explode(';', $this->request->getPost('members'));
            $emails = array_map('trim', $emails);
            $emails = array_filter($emails);
            
            $existingUsers = $this->userModel->whereIn('email', $emails)->findAll();
            $foundEmails = array_map(fn($u) => $u['email'], $existingUsers);
            $notFound = array_diff($emails, $foundEmails);
            
            if (!empty($notFound)) {
                return redirect()->back()->withInput()->with('errors', [
                    'members' => 'The following emails are not registered: ' . implode(', ', $notFound),
                ]);
            }
            
            $memberIds = array_column($existingUsers, 'id');
            
            // Include current user in group check
            $allIdsToCheck = array_merge([$userId], $memberIds);
        } else {
            $allIdsToCheck = [$userId];
        }
        
        // === Check if any of the users already in active proposals
        $activeConflict = $this->proposalModel
            ->select('users.email')
            ->active()
            ->join('users', 'users.id = proposals.leader_id')
            ->groupStart()
            ->whereIn('proposals.leader_id', $allIdsToCheck)
            ->orWhereIn('proposals.id', function($builder) use ($allIdsToCheck) {
                return $builder->select('proposal_id')
                    ->from('proposal_members')
                    ->whereIn('user_id', $allIdsToCheck);
            })
            ->groupEnd()
            ->findAll();
        
        if (!empty($activeConflict)) {
            $conflictingEmails = array_unique(array_column($activeConflict, 'email'));
            return redirect()->back()->withInput()->with('errors', [
                'members' => 'These users are already in an active proposal: ' . implode(', ', $conflictingEmails),
            ]);
        }
        
        // === Upload the file
        $file = $this->request->getFile('file');
        $filename = \Ramsey\Uuid\Uuid::uuid4()->toString() . '.pdf';
        $file->move(WRITEPATH . 'uploads/proposals', $filename);
        
        $this->proposalModel->insert([
            'leader_id' => $userId,
            'title' => $this->request->getPost('title'),
            'institution' => $this->request->getPost('institution'),
            'is_group' => $isGroup ? 1 : 0,
            'status' => 'pending',
            'file_path' => 'uploads/proposals/' . $filename,
        ]);
        
        $proposalId = $this->proposalModel->getInsertID();
        
        if ($isGroup) {
            $memberData = array_filter($existingUsers, fn($u) => $u['id'] != $userId);
            
            $records = array_map(fn($user) => [
                'proposal_id' => $proposalId,
                'user_id' => $user['id'],
            ], $memberData);
            
            if (!empty($records)) {
                $this->proposalMemberModel->insertBatch($records);
            }
        }
        
        return redirect()->to('/proposals')->with('message', 'Proposal submitted successfully.');
    }
    
    public function delete($id)
    {
        $user = $this->proposalModel->find($id);
        
        if(!$user) {
            return redirect()->back()->withInput()->with('errors', 'Proposal not found.');
        }
        
        $this->proposalModel->delete($id);
        
        return redirect()->back()->withInput()->with('message', 'Proposal deleted successfully.');
    }
    
    public function approval($id)
    {
        $proposal = $this->proposalModel->where('id', $id)->first();
        
        if(!$proposal) {
            throw new PageNotFoundException();
        }
        
        $validationRules = [
            'approval' => 'required|in_list[approved,rejected]',
            'notes' => 'string'
        ];
        
        $notes = trim($this->request->getPost('notes') ?? '');
        $notes = $notes !== '' ? $notes : null;
        
        if(!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $approval = $this->request->getPost('approval');
        
        $this->proposalModel->update($id, [
            'status' => $approval,
            'notes' => $notes
        ]);
        
        return redirect()->back()->with('message', 'Proposal has been ' . $approval . '.');
    }
    
    public function getFile($id)
    {
        $proposal = $this->proposalModel->where('id', $id)->first();
        
        if($proposal && !empty($proposal['file_path'])) {
            $fullPath = WRITEPATH . $proposal['file_path'];
            
            if(file_exists($fullPath)) {
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
        $proposal = $this->proposalModel->where('id', $id)->first();
        
        if($proposal && !empty($proposal['file_path'])) {
            $fullPath = WRITEPATH . $proposal['file_path'];
            
            if(file_exists($fullPath)) {
                return response()->download($fullPath, $proposal['title']);
            }
        }
        
        throw PageNotFoundException::forPageNotFound("File not found.");
    }
}
