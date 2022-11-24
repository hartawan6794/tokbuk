<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\AlamatKirimModel;
use App\Models\UserbiodataModel;

class AlamatKirim extends BaseController
{

	protected $alamatKirimModel;
	protected $validation;

	public function __construct()
	{
		$this->alamatKirimModel = new AlamatKirimModel();
		$this->user	= new UserbiodataModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = array();
		$provData = $this->getapiprov("https://api.rajaongkir.com/starter/province", "1774d6ce35f0c2b1ca03fcbfa8c98dcf");

		if (session()->get('username') == 'admin') {
			$user = $this->user->select('*')->findAll();
		} else {
			$user = $this->user->select('*')->where('id_user_bio', session()->get('id_user_bio'))->findAll();
		}

		$data = [
			'controller'    	=> 'alamatKirim',
			'title'     		=> 'Alamat Kirim',
			'user'				=> $user,
			'provinsi'			=> $provData
		];

		// var_dump($data);
		return view('alamatKirim', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->alamatKirimModel->select()->findAll();

		$no = 1;

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" id="edit-alamat" onClick="save(' . $value->id_alamat . ')"><i class="fa-solid fa-pen-to-square"></i> Ubah</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_alamat . ')"><i class="fa-solid fa-trash"></i> Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no,
				$value->nm_penerima,
				$value->telp_penerima,
				$value->provinsi,
				$value->kabupaten,
				$value->kecamatan,
				$value->kelurahan,
				$value->alamat_rumah,
				$ops
			);

			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_alamat');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->alamatKirimModel->select('id_alamat,id_user_bio,nm_penerima,telp_penerima,concat(id_provinsi,"-",provinsi) as provinsi,concat(id_kabupaten,"-",kabupaten) as kabupaten, kecamatan, kelurahan, alamat_rumah,postalcode')->where('id_alamat', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();
		$create = date('Y-m-d H:i:s');

		$prov = explode("-",$this->request->getPost('provinsi'));
		$kab = explode("-",$this->request->getPost('kabupaten'));


		$fields['id_alamat'] = $this->request->getPost('id_alamat');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['nm_penerima'] = $this->request->getPost('nm_penerima');
		$fields['telp_penerima'] = $this->request->getPost('telp_penerima');
		$fields['id_provinsi'] = $prov[0];
		$fields['provinsi'] = $prov[1];
		$fields['id_kabupaten'] = $kab[0];
		$fields['kabupaten'] = $kab[1];
		$fields['kecamatan'] = $this->request->getPost('kecamatan');
		$fields['kelurahan'] = $this->request->getPost('kelurahan');
		$fields['alamat_rumah'] = $this->request->getPost('alamat_rumah');
		$fields['postalcode'] = $this->request->getPost('postalcode');
		// $fields['status'] = $this->request->getPost('status');
		$fields['created_at'] = $create;;
		// $fields['updated_at'] = $this->request->getPost('updated_at');

		$this->validation->setRules([
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'nm_penerima' => ['label' => 'Nm penerima', 'rules' => 'required|min_length[0]|max_length[255]'],
			'telp_penerima' => ['label' => 'Telp penerima', 'rules' => 'required|min_length[0]|max_length[15]'],
			'provinsi' => ['label' => 'Provinsi', 'rules' => 'required|min_length[0]|max_length[100]'],
			'kabupaten' => ['label' => 'Kabupaten', 'rules' => 'required|min_length[0]|max_length[100]'],
			'kecamatan' => ['label' => 'Kecamatan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'kelurahan' => ['label' => 'Kelurahan', 'rules' => 'required|min_length[0]|max_length[150]'],
			'alamat_rumah' => ['label' => 'Alamat rumah', 'rules' => 'required|min_length[0]|max_length[200]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->alamatKirimModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = "Data alamat berhasil di tambah";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data alamat gagal di tambah";
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$create = date('Y-m-d H:i:s');

		$prov = explode("-",$this->request->getPost('provinsi'));
		$kabData = $this->request->getPost('kabupaten');
		if($kabData != null  || $kabData != ''){
			$kab = explode("-",$kabData);
			$fields['id_kabupaten'] = $kab[0];
			$fields['kabupaten'] = $kab[1];
		}

		$fields['id_alamat'] = $this->request->getPost('id_alamat');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['nm_penerima'] = $this->request->getPost('nm_penerima');
		$fields['telp_penerima'] = $this->request->getPost('telp_penerima');
		$fields['id_provinsi'] = $prov[0];
		$fields['provinsi'] = $prov[1];
		$fields['kecamatan'] = $this->request->getPost('kecamatan');
		$fields['kelurahan'] = $this->request->getPost('kelurahan');
		$fields['alamat_rumah'] = $this->request->getPost('alamat_rumah');
		$fields['postalcode'] = $this->request->getPost('postalcode');
		$fields['updated_at'] =$create;


		$this->validation->setRules([
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'nm_penerima' => ['label' => 'Nm penerima', 'rules' => 'required|min_length[0]|max_length[255]'],
			'telp_penerima' => ['label' => 'Telp penerima', 'rules' => 'required|min_length[0]|max_length[15]'],
			'id_provinsi' => ['label' => 'Id provinsi', 'rules' => 'required|numeric|min_length[0]|max_length[3]'],
			'provinsi' => ['label' => 'Provinsi', 'rules' => 'required|min_length[0]|max_length[100]'],
			'kecamatan' => ['label' => 'Kecamatan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'kelurahan' => ['label' => 'Kelurahan', 'rules' => 'required|min_length[0]|max_length[150]'],
			'alamat_rumah' => ['label' => 'Alamat rumah', 'rules' => 'required|min_length[0]|max_length[200]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->alamatKirimModel->update($fields['id_alamat'], $fields)) {

				$response['success'] = true;
				$response['messages'] = "Berhasil ubah data alamat";
			} else {

				$response['success'] = false;
				$response['messages'] = "Gagal ubah data alamat";
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_alamat');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->alamatKirimModel->where('id_alamat', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = "Delete data alamat berhasil";
			} else {

				$response['success'] = false;
				$response['messages'] = "Delete data alamat gagal";
			}
		}

		return $this->response->setJSON($response);
	}

	function getapikab()
	{

		$key = $this->request->getPost('key');
		$url = $this->request->getPost('url');
		// $prov = $this->request->getPost('prov');
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				//   "key: your-api-key"
				// "province : $prov",
				"key: $key"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$provinsi = json_decode($response, true);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			foreach ($provinsi['rajaongkir'] as $k) {
				$provData = $k;
				// foreach($k as $d){
				// }
			}
		}

		return  $this->response->setJSON($provData);
	}
	function getapiprov($url = null, $key = null, $provinsi_id = null)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				//   "key: your-api-key"
				"key: $key"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$provinsi = json_decode($response, true);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			foreach ($provinsi['rajaongkir'] as $k) {
				$provData = $k;
				// foreach($k as $d){
				// }
			}
		}

		return  $provData;
	}
}
