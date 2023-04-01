<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KeranjangModel;
use App\Models\OrderDetailModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\RatingModel;

class OrderApi extends BaseController
{

    public function __construct()
    {
        $this->db = db_connect();
        $this->order = new OrderModel();
        $this->orderDetail = new OrderDetailModel();
        $this->product = new ProductModel();
        $this->cart = new KeranjangModel();
        $this->rating = new RatingModel();
    }

    public function index()
    {

        $response = array();

        $id_product = $this->request->getPostGet('id_product');
        $id_user_bio = $this->request->getPostGet('id_user_bio');
        $key = $this->request->getPostGet('key');
        $sql = 'SELECT dari,sum(ke) ke,SUM(berat)*1000 berat FROM
        (SELECT id_kabupaten AS dari, 0 ke, berat_produk AS berat
        FROM tbl_product tp LEFT JOIN tbl_toko tk ON
        tp.id_toko = tk.id_toko LEFT JOIN tbl_user_biodata tub ON
        tub.id_user_bio = tk.id_user_bio LEFT JOIN tbl_alamat ta ON 
        ta.id_user_bio = tk.id_user_bio
        WHERE id_product = ?
        
        UNION ALL
        
        SELECT 0 dari, id_kabupaten, 0 weight FROM tbl_user_biodata tub
        LEFT JOIN tbl_alamat ta ON tub.id_user_bio = ta.id_user_bio WHERE tub.id_user_bio = ?
        ) a';
        $query = $this->db->query($sql, [$id_product, $id_user_bio])->getResult();
        $data = $this->getOngkir($query[0]->dari, $query[0]->ke, $query[0]->berat, $key);
        if ($data) {
            $response['success'] = true;
            $response['messages'] = 'Data berhasil didapatkan';
            $response['data'] = $data;
        }
        // var_dump($response);
        return $this->response->setJSON($response);
    }

    public function getOngkir($dari, $ke, $berat, $key)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$dari&destination=$ke&weight=$berat&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: $key"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $harga = json_decode($response, true);
        if ($err) {
            return $err;
        } else {
            foreach ($harga['rajaongkir'] as $k) {
                $hargaData = $k;
                // foreach($k as $d){
                // }
            }
        }
        return $hargaData;
    }

    public function input()
    {
        $response = array();
        $create = date('Y-m-d');
        $type = $this->request->getPostGet('type');
        $id_product = $this->request->getPostGet('id_product');
        $qty = $this->request->getPostGet('stok');
        $fields['id_order'] = $this->request->getPostGet('id_order');
        $fields['invoice'] = $this->order->invoice_no();
        $fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
        $fields['id_rekening'] = $this->request->getPostGet('id_rekening');
        $fields['tgl_order'] = $create;
        $fields['sub_total'] = $this->request->getPostGet('sub_total');
        $fields['sub_total_pengiriman'] = $this->request->getPostGet('sub_total_pengiriman');
        $fields['total_pembayaran'] = $this->request->getPostGet('total_pembayaran');
        $fields['jns_pengiriman'] = $this->request->getPostGet('jns_pengiriman');
        $fields['created_at'] = $create;

        if ($type == 'cart') {
            if ($this->order->insert($fields)) {
                $id_order = $this->order->insertID();
                $carts = $this->cart->where('id_user_bio', $fields['id_user_bio'])->findAll();

                $row = [];
                foreach ($carts as $cart) {
                    array_push($row, array(
                        'id_order' => $id_order,
                        'id_product' => $cart->id_product,
                        'harga_product' => $cart->harga_buku,
                        'qty' => $cart->qty,
                        'total' => $cart->harga_buku * $cart->qty
                    ));
                }

                if ($this->orderDetail->insertBatch($row)) {
                    $this->cart->where('id_user_bio', $fields['id_user_bio'])->delete();
                    $response['success'] = true;
                    $response['messages'] = lang("Berhasil Menambahkan Data");
                    $response['data'] = [
                        'invoice' => $fields['invoice']
                    ];
                }
            } else {

                $response['success'] = false;
                $response['messages'] = lang("Gagal Menambahkan Data");
            }
        } else {

            if ($this->order->insert($fields)) {
                $id_order = $this->order->insertID();
                $product = $this->product->where('id_product', $id_product)->first();
                $row = [
                    'id_order' => $id_order,
                    'id_product' => $id_product,
                    'harga_product' => $product->harga_buku,
                    'qty' => $qty,
                    'total' => $fields['sub_total']
                ];
                $this->orderDetail->insert($row);
                $response['success'] = true;
                $response['messages'] = lang("Berhasil Menambahkan Data");
                $response['data'] = [
                    'invoice' => $fields['invoice']
                ];
            } else {

                $response['success'] = false;
                $response['messages'] = lang("Gagal Menambahkan Data");
            }
        }

        return $this->response->setJSON($response);
    }

    public function getPemesanan()
    {

        $response = array();
        $id_user_bio = $this->request->getPostGet('id_user_bio');
        $type = $this->request->getPostGet('type');

        if ($type == 'kirim') {
            $data = $this->order->join('tbl_order_detail tod', 'tod.id_order = tbl_order.id_order', 'right')->join('tbl_product tp', 'tod.id_product = tp.id_product')->join('tbl_kategori tk', 'tp.id_kategori = tk.id_kategori', 'left')->join('tbl_rekening tr', 'tr.id_rekening = tbl_order.id_rekening', 'left')->join('tbl_pengiriman tpir', 'tpir.id_order = tbl_order.id_order', 'left')->where(['id_user_bio' => $id_user_bio])->groupBy('tbl_order.id_order')->findAll();
        } else {
            $data = $this->order->distinct()->join('tbl_order_detail tod', 'tod.id_order = tbl_order.id_order', 'right')->join('tbl_product tp', 'tod.id_product = tp.id_product')->join('tbl_kategori tk', 'tp.id_kategori = tk.id_kategori', 'left')->join('tbl_rekening tr', 'tr.id_rekening = tbl_order.id_rekening', 'left')->where(['id_user_bio' => $id_user_bio])->groupBy('tbl_order.id_order')->findAll();
        }

        if ($data) {

            $response['success'] = true;
            $response['messages'] = lang("Berhasil Mendapatkan Data");
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['messages'] = lang("Gagal Mendapatkan Data");
            $response['data'] = $data;
        }
        return $this->response->setJSON($response);
    }

    public function getPemesananDetail()
    {

        $response = array();

        $id_order = $this->request->getPostGet('id_order');
        $data = $this->order->join('tbl_order_detail tod', 'tod.id_order = tbl_order.id_order', 'right')->join('tbl_product tp', 'tod.id_product = tp.id_product')->join('tbl_toko tt', 'tp.id_toko = tt.id_toko', 'left')->join('tbl_kategori tk', 'tp.id_kategori = tk.id_kategori', 'left')->join('tbl_rekening tr', 'tbl_order.id_rekening = tr.id_rekening', 'left')->where(['tbl_order.id_order' => $id_order])->findAll();

        $response['success'] = true;
        $response['messages'] = lang("Berhasil Mendapatkan Data");
        $response['data'] = $data;
        return $this->response->setJSON($response);
    }


    public function hapusPesanan()
    {
        $response = array();
        $id_order = $this->request->getPostGet('id_order');
        if ($id_order != null) {
            // $qty = $this->orderDetail->join('tbl_product tp', 'tp.id_product = tbl_order_detail.id_product', 'left')->where('id_order', $id_order)->first();
            // $oldStok = $qty->stok + $qty->qty;
            // if ($this->product->set('stok', $oldStok)->where('id_product', $qty->id_product)->update()) {
            if ($this->order->where('id_order', $id_order)->delete()) {
                // $this->orderDetail->where('id_order', $id_order)->delete();
                $response['success'] = true;
                $response['messages'] = lang("Berhasil Menambahkan Data");
            } else {
                $response['success'] = false;
                $response['messages'] = lang("Gagal Menambahkan Data");
            }
        }

        return $this->response->setJSON($response);
    }


    public function upload()
    {

        $response = array();
        $fields['id_order'] = $this->request->getPostGet('id_order');
        $fields['bukti_order'] = $this->request->getFile('bukti_order');

        // $data = $this->userModel->where('id_user_bio', $fields['id_user_bio'])->first();

        if ($fields['bukti_order']->getName() != '') {
            $fileName = 'bukti-' . $fields['bukti_order']->getRandomName();
            $order['bukti_order'] = $fileName;
            $order['kd_file'] = '1';
            $fields['bukti_order']->move(WRITEPATH . '../public/img/bukti', $fileName);
        }
        if ($this->order->update($fields['id_order'], $order)) {

            $response['success'] = true;
            $response['messages'] = "Bukti berhasil di upload";
        } else {

            $response['success'] = false;
            $response['messages'] = "Bukti gagal di upload";
        }

        return $this->response->setJSON($response);
    }

    public function updatePesananDiterima()
    {
        $response = array();
        $update = date('Y-m-d H:i:s');
        $fields['id_order'] = $this->request->getPostGet('id_order');
        $fields['updated_at'] = $update;
        $fields['validasi'] = 4;
        if ($this->order->update($fields['id_order'], $fields)) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Mengupdate Pesanan";
        } else {

            $response['success'] = false;
            $response['messages'] = "Gagal Mengupdate Pesanan";
        }
        return $this->response->setJSON($response);
    }

    public function getOrderSelesai()
    {
        $response = array();

        $id_order = $this->request->getPostGet('id_order');

        $data = $this->orderDetail->select('tp.id_product,tp.imgproduct1,tp.judul_buku')->join('tbl_product tp', 'tbl_order_detail.id_product = tp.id_product')->where('tbl_order_detail.id_order', $id_order)->findAll();

        if ($data) {
            $response['success'] = true;
            $response['messages'] = 'Data berhasil didapatkan';
            $response['data'] = $data;
        } else {

            $response['success'] = false;
            $response['messages'] = 'Data gagal didapatkan';
            $response['data'] = $data;
        }
        // var_dump($response);
        return $this->response->setJSON($response);
    }

    public function kirimRating()
    {
        $response = array();
        $fields['id_user_bio'] = $this->request->getPostGet('id_user');
        $fields['id_product'] = $this->request->getPostGet('id_product');
        $fields['rating'] = $this->request->getPostGet('rating');
        $fields['created_at'] = date('Y-m-d H:i:s');
        if ($this->rating->insert($fields)) {
            $response['success'] = true;
            $response['messages'] = "Berhasil Menambahkan Data";
        } else {

            $response['success'] = false;
            $response['messages'] = "Gagal Menambahkan Data";
        }
        return $this->response->setJSON($response);
    }
}
