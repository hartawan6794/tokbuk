<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProductModel;
use App\Models\RekeningModel;

class ProdukApi extends BaseController
{

    public function __construct()
    {
        $this->produk = new ProductModel();
        $this->kategori = new KategoriModel();
        $this->rek = new RekeningModel();
    }

    public function index(){
        $response = array();
        $kategori = $this->request->getPostGet('kategori');
        $id_product = $this->request->getPostGet('id_product');
        if($id_product != ''){
            $data = $this->produk->join('tbl_kategori tk','tk.id_kategori = tbl_product.id_kategori')
            ->join('tbl_toko tok','tok.id_toko = tbl_product.id_toko','left')
            ->where('tbl_product.id_product', $id_product)->findAll();
        }
        else if($kategori == 'semua' || $kategori == ''){
            $data = $this->produk->findAll();
        }else{
            $data = $this->produk->join('tbl_kategori tk','tk.id_kategori = tbl_product.id_kategori')->like('tk.nama_kategori', $kategori)->findAll();
        }
        if($data){
            $response['success'] = true;
            $response['messages'] = "Data berhasil diubah";
            $response['data'] = $data;
        }else{
            $response['success'] = true;
            $response['messages'] = "Data tidak ditemukan";
            $response['data']= $data;
        }
        return $this->response->setJSON($response);
    }

    public function kategori(){
        $response = array();
        $data = $this->kategori->findAll();
        $response['success'] = true;
        $response['messages'] = "Data berhasil diubah";
        $response['data'] = $data;
        return $this->response->setJSON($response);
    }

    public function getRekening()
    {
        $response = array();
        $data = $this->rek->select()->findAll();
        $response['success'] = true;
        $response['messages'] = "Data berhasil diubah";
        $response['data'] = $data;
        return $this->response->setJson($response);
    }


}