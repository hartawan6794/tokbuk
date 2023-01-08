<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KeranjangModel;
use App\Models\OrderModel;
use App\Models\ProductModel;

class CartApi extends BaseController
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->order = new OrderModel();
        $this->cart = new KeranjangModel();
        $this->product = new ProductModel();
    }

    public function index()
    {
        $data = $this->cart->select()->join('tbl_product tp', 'tp.id_product = tbl_cart.id_product', 'left')->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_cart.id_user_bio', '')->join('tbl_kategori tk', 'tk.id_kategori = tp.id_kategori', 'left')->findAll();
        if ($data) {
            $response['success'] = true;
            $response['messages'] = "Data berhasil diubah";
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['messages'] = "Data tidak ditemukan";
            $response['data'] = $data;
        }
        return $this->response->setJSON($response);
    }

    public function addCart()
    {
        $response = array();

        $fields['id_product'] = $this->request->getPostGet('id_product');
        $fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
        $fields['qty'] = 1;
        $fields['harga_buku'] = $this->request->getPostGet('harga_buku');

        // $query = $this->cart->where(
        //     'id_product',$fields['id_product']
        // )->where('id_user_bio', $fields['id_user_bio']);

        $sql = 'select * from tbl_cart where id_product = ? and id_user_bio = ?';
        $query = $this->db->query($sql, [$fields['id_product'], $fields['id_user_bio']])->getResult();


        if ($query) {

            $fields['qty'] += $query[0]->qty;
            $fields['total_harga'] = $fields['qty'] * $fields['harga_buku'];
            if ($this->cart->update($query[0]->id_cart, $fields)) {
                $response['success'] = true;
                $response['messages'] = lang("Berhasil Menambahkan Data");
            } else {

                $response['success'] = false;
                $response['messages'] = lang("Gagal Menambahkan Data");
            }
        } else {
            if ($this->cart->insert($fields)) {
                $response['success'] = true;
                $response['messages'] = lang("Berhasil Menambahkan Data");
            } else {

                $response['success'] = false;
                $response['messages'] = lang("Gagal Menambahkan Data");
            }
        }

        return $this->response->setJSON($response);
    }

    public function countCart()
    {
        $response = array();
        $id_user_bio = $this->request->getPostGet('id_user_bio');
        $count = $this->cart->where('id_user_bio', $id_user_bio)->countAllResults();
        if ($count) {
            $response['success'] = true;
            $response['messages'] = "Berhasil mendapatkan data";
            $response['data'] = $count;
        } else {
            $response['success'] = false;
            $response['messages'] = "Gagal mendapatkan data";
            $response['data'] = $count;
        }
        return $this->response->setJSON($response);
    }

    public function hapuscart()
    {
        $response = array();
        $id_cart = $this->request->getPostGet('id_cart');

        if ($this->cart->where('id_cart', $id_cart)->delete()) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Menghapus Data";
        } else {
            $response['success'] = false;
            $response['messages'] = "Gagal Menghapus data";
        }
        return $this->response->setJSON($response);
    }

    public function updateQty()
    {
        $response = array();
        // $type = $this->request->getPostGet('type');
        $fields['qty'] = $this->request->getPostGet('qty');
        $fields['id_cart'] = $this->request->getPostGet('id_cart');

        if ($this->cart->update($fields['id_cart'], $fields)) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Mengubah Qty";
        } else {
            $response['success'] = false;
            $response['messages'] = "Gagal Mengubah Qty";
        }
        return $this->response->setJSON($response);
    }

    public function getCart()
    {
        $response = array();
        $id_user_bio = $this->request->getPostGet('id_user_bio');

        $data = $this->cart->join('tbl_product tp', 'tp.id_product = tbl_cart.id_product')->join('tbl_kategori tk', 'tk.id_kategori = tp.id_kategori', 'left')->join('tbl_toko tbk', 'tbk.id_toko = tp.id_toko', 'left')->where('tbl_cart.id_user_bio', $id_user_bio)->findAll();

        if ($data) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Mendapatkan Data";
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['messages'] = "Data Belum Ada";
            $response['data'] = $data;

        }
        return $this->response->setJSON($response);
    }
}
