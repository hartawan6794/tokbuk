<?php

namespace App\Controllers;


class Report extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if($this->session->get('isLogin')){
            $data = [
                'controller'    	=> 'Laporan',
                'title'     		=> 'Laporan',
            ];
            return view('laporan',$data);
        }else{
            return view('login');
        }
    }

    public function cetak(){
        $date = [
            'awal' => $this->request->getPost('date1'),
            'akhir' => $this->request->getPost('date2'),
        ];

        return $this->response->setJSON($date);
    }
}
