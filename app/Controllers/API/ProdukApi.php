<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProductModel;
use App\Models\RatingModel;
use App\Models\RekeningModel;

class ProdukApi extends BaseController
{

    public function __construct()
    {
        $this->produk = new ProductModel();
        $this->kategori = new KategoriModel();
        $this->rek = new RekeningModel();
        $this->rating = new RatingModel();
    }

    public function index(){
        $response = array();
        $kategori = $this->request->getPostGet('kategori');
        $id_product = $this->request->getPostGet('id_product');
        $rating = '';
        $count = '';
        if($id_product != ''){
            $data = $this->produk->join('tbl_kategori tk','tk.id_kategori = tbl_product.id_kategori')
            ->join('tbl_toko tok','tok.id_toko = tbl_product.id_toko','left')
            ->where('tbl_product.id_product', $id_product)->findAll();

            $rating = $this->rating->select('sum(rating) rating')->where('id_product', $id_product)->findAll();
            $count = $this->rating->where('id_product', $id_product)->countAllResults();
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
            $response['rating'] = ($rating and $count) ? (float) number_format(($rating[0]->rating/$count),1) : "";
            $response['count'] = $count ? $count : "";
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