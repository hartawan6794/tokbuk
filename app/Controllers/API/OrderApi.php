<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\ProductModel;

class OrderApi extends BaseController
{

    public function __construct()
    {
        $this->db = db_connect();
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
        $data = $this->getHarga($query[0]->dari,$query[0]->ke,$query[0]->berat,$key);
        if($data){
            $response['success'] = true;
            $response['messages'] = 'Data berhasil didapatkan';
            $response['data'] = $data;
        }
        // var_dump($response);
        return $this->response->setJSON($response);
    }

    public function getHarga($dari, $ke, $berat,$key)
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

        $harga = json_decode($response,true);
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
}
