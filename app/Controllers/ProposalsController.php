<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProposalModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class ProposalsController extends BaseController
{
    private $proposalModel;
    
    public function __construct()
    {
        $this->proposalModel = new ProposalModel();
    }
    
    public function index()
    {
        $raw = $this->proposalModel->withMembers()->findAll();
        $proposals = $this->proposalModel->processJsonFields($raw);
       
        $proposalsByStatus = [
            'pending' => [],
            'approved' => [],
            'rejected' => [],
        ];
        
        foreach ($proposals as $proposal) {
            $status = $proposal['status'];
            $proposalsByStatus[$status][] = $proposal;
        }
        
        $data = [
            'title' => 'Proposals',
            'proposalsByStatus' => $proposalsByStatus,
        ];
        
        return view('pages/proposals/index', $data);
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
        
        if (!$this->validate($validationRules)) {
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
        
        if ($proposal && !empty($proposal['file_path'])) {
            $fullPath = WRITEPATH . $proposal['file_path'];
            
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
        $proposal = $this->proposalModel->where('id', $id)->first();
        
        if ($proposal && !empty($proposal['file_path'])) {
            $fullPath = WRITEPATH . $proposal['file_path'];
            
            if (file_exists($fullPath)) {
                return response()->download($fullPath, $proposal['title']);
            }
        }
        
        throw PageNotFoundException::forPageNotFound("File not found.");
    }
    
}
