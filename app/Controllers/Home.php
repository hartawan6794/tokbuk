<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if($this->session->get('isLogin')){
            $data = [
                'controller'    	=> 'Dashboard',
                'title'     		=> 'Dashboard'				
            ];
            return view('dashboard',$data);
        }else{
            return view('login');
        }
    }
}
