<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profile',
        ];
        
        return view('pages/profile/index', $data);
    }
}
