<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        return view('user/index');
    }


    public function form(){
        return view('user/form');
    }

    public function prosses(){
        $d = $this->request->getPost();
        var_dump($d);
    }
}
