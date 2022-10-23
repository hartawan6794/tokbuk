<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'controller'    	=> 'Dashboard',
            'title'     		=> 'Dashboard'				
        ];
        return view('dashboard',$data);
    }
}
