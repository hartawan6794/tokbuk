<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PengirimanModel;
use App\Models\ProductModel;
use App\Models\UserbiodataModel;

class Home extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->order = new OrderModel();
        $this->product = new ProductModel();
        $this->user = new UserbiodataModel();
        $this->pengiriman = new PengirimanModel();
    }

    public function index()
    {
        if($this->session->get('isLogin')){

            if(session()->get('username') == 'admin') {
                $jml_product = $this->product->countAll();
            } else{
                $jml_product =$this->product->select()->join('tbl_toko tt', 'tt.id_toko = tbl_product.id_toko', 'inner')->join('tbl_user_biodata tub', 'tub.id_user_bio = tt.id_user_bio', 'inner')->where('tub.id_user_bio', session()->get('id_user_bio'))->countAllResults();
            }
 
            $data = [
                'controller'    	=> 'Dashboard',
                'title'     		=> 'Dashboard',
                'jml_order'         => $this->order->countAll(),
                'jml_product'		=> $jml_product,	
                'jml_user'          => $this->user->countAll(),
                'jml_pengiriman'    => $this->pengiriman->countAll()
            ];
            return view('dashboard',$data);
        }else{
            return view('login');
        }
    }
}
