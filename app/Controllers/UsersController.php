<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class UsersController extends BaseController
{
    private UserModel $usersModel;
    
    public function __construct()
    {
        $this->usersModel = new UserModel();
    }
    
    public function index()
    {
        $users = $this->usersModel->select('id,name,email,role,status,created_at,updated_at')->orderBy('id', 'desc')->findAll();
        
        $data = [
            'title' => 'Users',
            'users' => $users,
        ];
        
        return view('pages/masters/users/index', $data);
    }
    
    public function store()
    {
        $validation = Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'role' => 'required|in_list[supervisor,candidate,intern]',
        ];
        
        if(!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $userModel = new UserModel();
        $userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => 'active',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
        ]);
        
        return redirect()->to('/masters/users')->with('message', 'User created successfully.');
    }
    
    public function update($id)
    {
        $validation = Services::validation();
        
        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,$id]",
            'role'  => 'required|in_list[supervisor,candidate,intern]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $validated = $this->validator->getValidated();
        
        $this->usersModel->update($id, $validated);
        
        return redirect()->to('/masters/users')->with('message', 'User updated successfully.');
    }
    
    public function delete($id)
    {
        $user = $this->usersModel->find($id);
        
        if(!$user) {
            return redirect()->back()->withInput()->with('errors', 'User not found.');
        }
        
        $this->usersModel->delete($id);
        
        return redirect()->to('/masters/users')->with('message', 'User deleted successfully.');
    }
}
