<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\AuthUser;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    private UserModel $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        return view('pages/auth/login');
    }
    
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if(!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $user = $this->userModel->where('email', $this->request->getPost('email'))->first();
        
        if(!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->with('error', 'Invalid credentials.')->withInput();
        }
        
        session()->set([
            'isLoggedIn' => true,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ]
        ]);
        
        return redirect()->to('/dashboard');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
    
    public function changePassword()
    {
        $rules = [
            'password'        => 'required',
            'new_password'    => 'required|min_length[6]',
            'confirm_password'=> 'required|matches[new_password]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userId = AuthUser::id(); // Assuming you're using an AuthUser utility
        $user = $this->userModel->find($userId);
        
        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('errors', ['password' => 'Old password is incorrect.']);
        }
        
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        $this->userModel->update($userId, ['password' => $newPassword]);
        
        return redirect()->back()->with('message', 'Password changed successfully.');
    }
}
