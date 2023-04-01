<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AlamatKirimModel;

class AlamatKirimApi extends BaseController
{

    public function __construct()
    {
        $this->alamat = new AlamatKirimModel();
    }

    public function add(){
        $response = array();
		$create = date('Y-m-d H:i:s');
		// $fields['id_alamat'] = $this->request->getPost('id_alamat');
		$fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
		$fields['nm_penerima'] = $this->request->getPostGet('nm_penerima');
		$fields['telp_penerima'] = $this->request->getPostGet('telp_penerima');
		$fields['id_provinsi'] = $this->request->getPostGet('id_provinsi');
		$fields['provinsi'] = $this->request->getPostGet('provinsi');
		$fields['id_kabupaten'] = $this->request->getPostGet('id_kabupaten');
		$fields['kabupaten'] = $this->request->getPostGet('kabupaten');
		$fields['kecamatan'] = $this->request->getPostGet('kecamatan');
		$fields['kelurahan'] = $this->request->getPostGet('kelurahan');
		$fields['alamat_rumah'] = $this->request->getPostGet('alamat_rumah');
		$fields['postalcode'] = $this->request->getPostGet('postalcode');
		// $fields['status'] = $this->request->getPostGet('status');
		$fields['created_at'] = $create;
		// $fields['updated_at'] = $this->request->getPost('updated_at');

		if ($this->alamat->insert($fields)) {
			$response['success'] = true;
			$response['messages'] = "Berhasil menambahkan data";
		} else {
			$response['success'] = false;
			$response['messages'] = "Gagal menambahkan data";
		}

        return $this->response->setJSON($response);
        
    }

	public function getOne(){
		$response = array();
		$id_user_bio = $this->request->getPostGet('id_user_bio');
		$data = $this->alamat->where('id_user_bio', $id_user_bio)->findAll();
		if($data){
			$response['success'] = true;
			$response['messages'] = "Berhasil mendapatkan data";
			$response['data'] = $data;
		} else {
			$response['success'] = false;
			$response['messages'] = "Gagal mendapatkan data";
			$response['data'] = $data;
		}

		return $this->response->setJSON($response);

	}

	public function edit(){ 
        $response = array();
		$create = date('Y-m-d H:i:s');
		$fields['id_alamat'] = $this->request->getPostGet('id_alamat');
		$fields['id_user_bio'] = $this->request->getPostGet('id_user_bio');
		$fields['nm_penerima'] = $this->request->getPostGet('nm_penerima');
		$fields['telp_penerima'] = $this->request->getPostGet('telp_penerima');
		$fields['id_provinsi'] = $this->request->getPostGet('id_provinsi');
		$fields['provinsi'] = $this->request->getPostGet('provinsi');
		$fields['id_kabupaten'] = $this->request->getPostGet('id_kabupaten');
		$fields['kabupaten'] = $this->request->getPostGet('kabupaten');
		$fields['kecamatan'] = $this->request->getPostGet('kecamatan');
		$fields['kelurahan'] = $this->request->getPostGet('kelurahan');
		$fields['alamat_rumah'] = $this->request->getPostGet('alamat_rumah');
		$fields['postalcode'] = $this->request->getPostGet('postalcode');
		$fields['updated_at'] = $create;

		if ($this->alamat->update($fields['id_alamat'],$fields)) {
			$response['success'] = true;
			$response['messages'] = "Berhasil menambahkan data";
		} else {
			$response['success'] = false;
			$response['messages'] = "Gagal menambahkan data";
		}

        return $this->response->setJSON($response);
        
    }

}