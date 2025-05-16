<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FinalReportModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class FinalReportsController extends BaseController
{
    private $finalReportModel;
    
    public function __construct()
    {
        $this->finalReportModel = new FinalReportModel();
    }
    
    public function index()
    {
        $raw = $this->finalReportModel->withProposal()->findAll();
        $finalReports = $this->finalReportModel->processJsonFields($raw);
       
        $finalReportsByStatus = [
            'pending' => [],
            'approved' => [],
            'rejected' => [],
        ];
        
        foreach ($finalReports as $finalReport) {
            $status = $finalReport['status'];
            $finalReportsByStatus[$status][] = $finalReport;
        }
        // dd($finalReportsByStatus);
        $data = [
            'title' => 'Final Reports',
            'finalReportsByStatus' => $finalReportsByStatus,
        ];
        
        return view('pages/final-reports/index', $data);
    }
    
    public function approval($id)
    {
        $finalReport = $this->finalReportModel->where('id', $id)->first();
        
        if(!$finalReport) {
            throw new PageNotFoundException();
        }
        
        $validationRules = [
            'approval' => 'required|in_list[approved,rejected]',
            'notes' => 'string'
        ];
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $approval = $this->request->getPost('approval');
        
        $this->finalReportModel->update($id, [
            'status' => $approval,
        ]);
        
        return redirect()->back()->with('message', 'FinalReport has been ' . $approval . '.');
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
    
}
