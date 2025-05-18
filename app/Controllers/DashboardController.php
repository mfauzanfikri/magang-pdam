<?php

namespace App\Controllers;

use App\Libraries\AuthUser;
use App\Libraries\Authz;
use App\Models\ActivityModel;
use App\Models\AttendanceModel;
use App\Models\FinalReportModel;
use App\Models\ProposalModel;
use DateTime;
use DateTimeZone;
use function PHPUnit\Framework\any;

class DashboardController extends BaseController
{
    private ProposalModel $proposalModel;
    private FinalReportModel $finalReportModel;
    private ActivityModel $activityModel;
    private AttendanceModel $attendanceModel;
    
    public function __construct()
    {
        $this->proposalModel = new ProposalModel();
        $this->finalReportModel = new FinalReportModel();
        $this->activityModel = new ActivityModel();
        $this->attendanceModel = new AttendanceModel();
    }
    
    public function index(): string
    {
        $userId = AuthUser::id();
        
        $timezone = new DateTimeZone('Asia/Jakarta');
        $now = new DateTime('now', $timezone);
        
        $today = $now->format('Y-m-d');
        $thisMonth = $now->format('m');
        $thisYear = $now->format('Y');
        
        $proposal = null;
        if(Authz::any(['intern', 'candaidate'])) {
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
        }
        
        $finalReport = null;
        $activityCount = null;
        $attendanceToday = null;
        $events = [];
        
        if(Authz::any(['intern'])) {
            if($proposal) {
                $finalReport = $this->finalReportModel->where('proposal_id', $proposal['id'])->first();
            }
            
            $activityCount = $this->activityModel->where('user_id', $userId)->countAllResults();
            
            $attendanceToday = $this->attendanceModel->where('user_id', $userId)->where('date', $today)->first();
            
            $attendanceMonth = $this->attendanceModel
                ->where('user_id', $userId)
                ->where('MONTH(date)', $thisMonth)
                ->where('YEAR(date)', $now->format('Y'))
                ->findAll();
            
            foreach($attendanceMonth as $att) {
                if($att['check_in']) {
                    $events[] = [
                        'title' => 'Check In',
                        'start' => $att['date'] . 'T' . $att['check_in'],
                        'allDay' => false,
                    ];
                }
                
                if($att['check_out']) {
                    $events[] = [
                        'title' => 'Check Out',
                        'start' => $att['date'] . 'T' . $att['check_out'],
                        'allDay' => false,
                    ];
                }
            }
        }
        
        // admin
        $proposalCounts = null;
        $proposalCountsThisMonth = null;
        $proposalsThisYearPerMonthCount = null;
        $proposalsThisYearCount = null;
        
        $finalReportCounts = null;
        $finalReportCountsThisMonth = null;
        $finalReportsThisYearPerMonthCount = null;
        $finalReportsThisYearCount = null;
        
        $labelsMonths = null;
        
        if(Authz::any(['admin', 'supervisor'])) {
            $proposalsThisYearPerMonthCount = [
                'pending' => array_fill(0, 12, 0),
                'approved' => array_fill(0, 12, 0),
                'rejected' => array_fill(0, 12, 0),
            ];
            
            $finalReportsThisYearPerMonthCount = [
                'pending' => array_fill(0, 12, 0),
                'approved' => array_fill(0, 12, 0),
                'rejected' => array_fill(0, 12, 0),
            ];
            
            $proposalCounts = [
                'pending' => $this->proposalModel->where('status', 'pending')->countAllResults(),
                'approved' => $this->proposalModel->where('status', 'approved')->countAllResults(),
                'rejected' => $this->proposalModel->where('status', 'rejected')->countAllResults(),
            ];
            
            foreach (['pending', 'approved', 'rejected'] as $status) {
                $rows = $this->proposalModel
                    ->select('MONTH(created_at) as month')
                    ->where('status', $status)
                    ->where('YEAR(created_at)', $thisYear)
                    ->findAll();
                
                foreach ($rows as $row) {
                    $monthIndex = intval($row['month']) - 1; // index mulai dari 0 (Jan = 0)
                    $proposalsThisYearPerMonthCount[$status][$monthIndex]++;
                }
            }
            
            $proposalCountsThisMonth = [
                'pending' => $proposalsThisYearPerMonthCount['pending'][(int)$thisMonth],
                'approved' => $proposalsThisYearPerMonthCount['approved'][(int)$thisMonth],
                'rejected' => $proposalsThisYearPerMonthCount['rejected'][(int)$thisMonth],
            ];
            
            $finalReportCounts = [
                'pending' => $this->finalReportModel->where('status', 'pending')->countAllResults(),
                'approved' => $this->finalReportModel->where('status', 'approved')->countAllResults(),
                'rejected' => $this->finalReportModel->where('status', 'rejected')->countAllResults(),
            ];
            
            foreach (['pending', 'approved', 'rejected'] as $status) {
                $rows = $this->finalReportModel
                    ->select('MONTH(created_at) as month')
                    ->where('status', $status)
                    ->where('YEAR(created_at)', $thisYear)
                    ->findAll();
                
                foreach ($rows as $row) {
                    $monthIndex = intval($row['month']) - 1;
                    $finalReportsThisYearPerMonthCount[$status][$monthIndex]++;
                }
            }
            
            $finalReportCountsThisMonth = [
                'pending' => $finalReportsThisYearPerMonthCount['pending'][(int)$thisMonth],
                'approved' => $finalReportsThisYearPerMonthCount['approved'][(int)$thisMonth],
                'rejected' => $finalReportsThisYearPerMonthCount['rejected'][(int)$thisMonth],
            ];
            
            $proposalsThisYearCount = [
                'pending' => array_sum($proposalsThisYearPerMonthCount['pending']),
                'approved' => array_sum($proposalsThisYearPerMonthCount['approved']),
                'rejected' => array_sum($proposalsThisYearPerMonthCount['rejected']),
            ];
            
            $finalReportsThisYearCount = [
                'pending' => array_sum($finalReportsThisYearPerMonthCount['pending']),
                'approved' => array_sum($finalReportsThisYearPerMonthCount['approved']),
                'rejected' => array_sum($finalReportsThisYearPerMonthCount['rejected']),
            ];
            
            $labelsMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        
        $data = [
            'title' => 'Dashboard',
            'proposal' => $proposal,
            'finalReport' => $finalReport,
            'activityCount' => $activityCount,
            'attendanceToday' => $attendanceToday,
            'events' => $events,
            'proposalCounts' => $proposalCounts,
            'proposalCountsThisMonth' => $proposalCountsThisMonth,
            'proposalsThisYearCount' => $proposalsThisYearCount,
            'finalReportCounts' => $finalReportCounts,
            'finalReportCountsThisMonth' => $finalReportCountsThisMonth,
            'finalReportsThisYearCount' => $finalReportsThisYearCount,
            'proposalsThisYearPerMonthCount' => $proposalsThisYearPerMonthCount,
            'finalReportsThisYearPerMonthCount' => $finalReportsThisYearPerMonthCount,
            'labelsMonths' => $labelsMonths,
        ];
        
        return view('pages/dashboard', $data);
    }
}
