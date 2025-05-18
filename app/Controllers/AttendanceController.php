<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\AuthUser;
use App\Libraries\Authz;
use App\Models\AttendanceModel;
use App\Models\ProposalModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use DateTimeZone;

class AttendanceController extends BaseController
{
    private $attendanceModel;
    private ProposalModel $proposalModel;
    
    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
        $this->proposalModel = new ProposalModel();
    }
    
    public function index()
    {
        $query = $this->attendanceModel->withRelations()->orderBy('date', 'desc');
        
        if (Authz::is('intern')) {
            $proposal = $this->proposalModel->belongsToUser(AuthUser::id())->active()->first();
            $query->where('user_id', AuthUser::id())->where('proposal_id', $proposal['id']);
        }
        
        $raw = $query->findAll();
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
        
        $data = [
            'title' => 'Presensi',
            'attendanceByStatus' => $attendanceByStatus,
        ];
        
        return view('pages/attendance/index', $data);
    }
    
    public function verification($id)
    {
        $attendance = $this->attendanceModel->where('id', $id)->first();
        
        if (!$attendance) {
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
            'verified_by' => AuthUser::id()
        ]);
        
        return redirect()->back()->with('message', 'Presensi berhasil di-' . ($verification === 'verified' ? 'verifikasi' : 'tolak') . '.');
    }
    
    public function checkIn()
    {
        $userId = AuthUser::id();
        
        $timezone = new DateTimeZone('Asia/Jakarta');
        $now = new DateTime('now', $timezone);
        
        $today = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        
        $proposal = $this->proposalModel
            ->groupStart()
            ->where('leader_id', $userId)
            ->orWhereIn('id', function($builder) use ($userId) {
                return $builder->select('proposal_id')
                    ->from('proposal_members')
                    ->where('user_id', $userId);
            })
            ->groupEnd()
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$proposal) {
            throw new PageNotFoundException('Proposal tidak ditemukan.');
        }
        
        $attendanceToday = $this->attendanceModel
            ->where('proposal_id', $proposal['id'])
            ->where('user_id', $userId)
            ->where('date', $today)
            ->first();
        
        if (!$attendanceToday) {
            $this->attendanceModel->insert([
                'user_id' => $userId,
                'proposal_id' => $proposal['id'],
                'date' => $today,
                'check_in' => $time,
            ]);
        } else {
            $this->attendanceModel
                ->set(['check_in' => $time])
                ->where('id', $attendanceToday['id'])
                ->update();
        }
        
        return redirect()->back()->with('message', 'Check in berhasil dilakukan.');
    }
    
    public function checkOut()
    {
        $userId = AuthUser::id();
        
        $timezone = new DateTimeZone('Asia/Jakarta');
        $now = new DateTime('now', $timezone);
        
        $today = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        
        $proposal = $this->proposalModel
            ->groupStart()
            ->where('leader_id', $userId)
            ->orWhereIn('id', function($builder) use ($userId) {
                return $builder->select('proposal_id')
                    ->from('proposal_members')
                    ->where('user_id', $userId);
            })
            ->groupEnd()
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$proposal) {
            throw new PageNotFoundException('Proposal tidak ditemukan.');
        }
        
        $attendanceToday = $this->attendanceModel
            ->where('proposal_id', $proposal['id'])
            ->where('user_id', $userId)
            ->where('date', $today)
            ->first();
        
        if (!$attendanceToday) {
            $this->attendanceModel->insert([
                'user_id' => $userId,
                'proposal_id' => $proposal['id'],
                'date' => $today,
                'check_out' => $time,
            ]);
        } else {
            $this->attendanceModel
                ->set(['check_out' => $time])
                ->where('id', $attendanceToday['id'])
                ->update();
        }
        
        return redirect()->back()->with('message', 'Check out berhasil dilakukan.');
    }
}