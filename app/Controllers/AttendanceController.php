<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class AttendanceController extends BaseController
{
    private $attendanceModel;
    
    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
    }
    
    public function index()
    {
        $raw = $this->attendanceModel->withRelations()->orderBy('date', 'desc')->findAll();
        $attendance = $this->attendanceModel->processJsonFields($raw);
        
        $attendanceByStatus = [
            'unverified' => [],
            'verified' => [],
            'rejected' => [],
        ];
        
        foreach ($attendance as $at) {
            $status = $at['status'];
            $attendanceByStatus[$status][] = $at;
        }
        // dd($attendanceByStatus);
        $data = [
            'title' => 'Attendance',
            'attendanceByStatus' => $attendanceByStatus,
        ];
        
        return view('pages/attendance/index', $data);
    }
    
    public function verification($id)
    {
        $attendance = $this->attendanceModel->where('id', $id)->first();
        
        if(!$attendance) {
            throw new PageNotFoundException();
        }
        
        $validationRules = [
            'verification' => 'required|in_list[verified,rejected]',
            'notes' => 'string'
        ];
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        $verification = $this->request->getPost('verification');
        
        $this->attendanceModel->update($id, [
            'status' => $verification,
        ]);
        
        return redirect()->back()->with('message', 'Attendance has been ' . $verification . '.');
    }
}
