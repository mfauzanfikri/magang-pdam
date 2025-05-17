<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
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
        
        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();
        
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
}
