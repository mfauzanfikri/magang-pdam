<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\AuthUser;
use App\Libraries\Authz;
use App\Models\ActivityModel;
use App\Models\ProposalModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class ActivitiesController extends BaseController
{
    private ActivityModel $activitiesModel;
    private ProposalModel $proposalModel;
    
    public function __construct()
    {
        $this->activitiesModel = new ActivityModel();
        $this->proposalModel = new ProposalModel();
    }
    
    public function index()
    {
        $query = $this->activitiesModel->withUser()->orderBy('id', 'desc');
        
        if(Authz::is('intern')) {
            $proposal = $this->proposalModel->belongsToUser(AuthUser::id())->active()->first();
            $query->where('user_id', AuthUser::id())->where('proposal_id', $proposal['id']);
        }
        
        $raw = $query->findAll();
        $activities = $this->activitiesModel->processJsonFields($raw);
        
        $data = [
            'title' => 'Activities',
            'activities' => $activities,
        ];
        
        return view('pages/activities/index', $data);
    }
    
    public function getFile($id)
    {
        $activity = $this->activitiesModel->where('id', $id)->first();
        
        if($activity && !empty($activity['photo_path'])) {
            $fullPath = WRITEPATH . $activity['photo_path'];
            
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
    
    public function store()
    {
        $rules = [
            'title' => 'required|max_length[50]',
            'description' => 'required|max_length[300]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'photo_file' => [
                'label' => 'Photo',
                'rules' => 'uploaded[photo_file]'
                    . '|is_image[photo_file]'
                    . '|mime_in[photo_file,image/jpg,image/jpeg,image/png]'
                    . '|max_size[photo_file,2048]', // 2MB max
            ],
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userId = AuthUser::id();
        
        // Get user's active proposal
        $proposal = $this->proposalModel
            ->belongsToUser($userId)
            ->active()
            ->first();
        
        if (!$proposal) {
            return redirect()->back()->withInput()->with('errors', [
                'proposal' => 'No active proposal found for user.'
            ]);
        }
        
        $file = $this->request->getFile('photo_file');
        $photoPath = '';
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $file->move(WRITEPATH . 'uploads/activities', $newName);
            $photoPath = 'uploads/activities/' . $newName;
        }
        
        $validated = $this->validator->getValidated();
        $validated['user_id'] = $userId;
        $validated['proposal_id'] = $proposal['id'];
        $validated['photo_path'] = $photoPath;
        
        $this->activitiesModel->insert($validated);
        
        return redirect()->to('/activities')->with('message', 'Activity created successfully.');
    }
    
    public function update($id)
    {
        $rules = [
            'title' => 'required|max_length[50]',
            'description' => 'required|max_length[300]',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
        ];
        
        $file = $this->request->getFile('photo_file');
        
        // Only apply file rules if file was uploaded
        if($file && $file->isValid() && !$file->hasMoved()) {
            $rules['photo_file'] = [
                'label' => 'Photo',
                'rules' => 'is_image[photo_file]'
                    . '|mime_in[photo_file,image/jpg,image/jpeg,image/png]'
                    . '|max_size[photo_file,2048]' // 2MB
            ];
        }
        
        if(!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $validated = $this->validator->getValidated();
        
        // Handle optional new image upload
        if($file && $file->isValid() && !$file->hasMoved()) {
            $newName = Uuid::uuid4()->toString();
            $file->move(WRITEPATH . 'uploads/activities', $newName);
            $validated['photo_path'] = 'uploads/activities/' . $newName;
        }
        
        $this->activitiesModel->update($id, $validated);
        
        return redirect()->to('/activities')->with('message', 'Activity updated successfully.');
    }
    
    public function delete($id)
    {
        $activity = $this->activitiesModel->find($id);
        
        if(!$activity) {
            return redirect()->back()->withInput()->with('errors', 'Activity not found.');
        }
        
        $this->activitiesModel->delete($id);
        
        return redirect()->to('/activities')->with('message', 'Activity deleted successfully.');
    }
}
