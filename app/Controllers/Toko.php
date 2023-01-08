<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TokoModel;
use App\Models\UserbiodataModel;
use App\Models\UserModel;

class Toko extends BaseController
{

	protected $tokoModel;
	protected $validation;

	public function __construct()
	{
		$this->tokoModel = new TokoModel();
		$this->validation =  \Config\Services::validation();
		$this->userbio = new UserbiodataModel();
		$this->user = new UserModel();
	}

	public function index()
	{
		// var_dump(session()->get('gambar'));die;
		if (session()->get('isLogin')) {

			if (session()->get('username') == 'admin') {
				$userbio = $this->user->select('*')->join('tbl_user_biodata', 'tbl_user.username = tbl_user_biodata.nik_user')->where('tbl_user.role', '1')->get();
			} else {
				$userbio = $this->user->select('*')->join('tbl_user_biodata', 'tbl_user.nik_user = tbl_user_biodata.nik_user')->where(['tbl_user.role' => '1',
				'tbl_user.username' => session()->get('username')])->get();
			}
			// var_dump($userbio->getResult());die;
			$data = [
				'controller'    	=> 'toko',
				'title'     		=> 'Toko',
				'userbio'			=> $userbio->getResult()
			];
			// var_dump($data);die;
			return view('toko', $data);
		} else {
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		if (session()->get('username') == 'admin') {
			$result = $this->tokoModel->select()->findAll();
		} else {
			$result = $this->tokoModel->select()->where('id_user_bio', session()->get('id_user_bio'))->findAll();
		}
		$i = 1;

		foreach ($result as $key => $value) {
			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_toko . ')"><i class="fa-solid fa-pen-to-square"></i>   Ubah</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_toko . ')"><i class="fa-solid fa-trash"></i>   Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$i,
				$this->userbio->where('id_user_bio', $value->id_user_bio)->first()->nm_user,
				$value->nm_toko,
				$value->alamat_toko,
				$value->telpon,
				$ops
			);
			$i++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_toko');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->tokoModel->where('id_toko', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$create = date('Y-m-d H:i:s');
		$fields['id_toko'] = $this->request->getPost('id_toko');
		$fields['id_user_bio'] = $this->request->getPost('nm_user');
		$fields['nm_toko'] = $this->request->getPost('nm_toko');
		$fields['alamat_toko'] = $this->request->getPost('alamat_toko');
		$fields['telpon'] = $this->request->getPost('telpon');
		$fields['created_at'] = $create;


		$this->validation->setRules([
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'nm_toko' => ['label' => 'Nama toko', 'rules' => 'required|min_length[0]|max_length[20]'],
			'alamat_toko' => ['label' => 'Alamat toko', 'rules' => 'required|min_length[0]|max_length[255]'],
			'telpon' => ['label' => 'Telpon', 'rules' => 'permit_empty|min_length[0]|max_length[15]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->tokoModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = "Data berhasil ditambahkan";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data gagal ditambahkan";
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();
		$update = date('Y-m-d H:i:s');

		$fields['id_toko'] = $this->request->getPost('id_toko');
		$fields['id_user_bio'] = $this->request->getPost('nm_user');
		$fields['nm_toko'] = $this->request->getPost('nm_toko');
		$fields['alamat_toko'] = $this->request->getPost('alamat_toko');
		$fields['telpon'] = $this->request->getPost('telpon');
		$fields['updated_at'] = $update;

		$this->validation->setRules([
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'nm_toko' => ['label' => 'Nama toko', 'rules' => 'required|min_length[0]|max_length[20]'],
			'alamat_toko' => ['label' => 'Alamat toko', 'rules' => 'required|min_length[0]|max_length[255]'],
			'telpon' => ['label' => 'Telpon', 'rules' => 'permit_empty|min_length[0]|max_length[15]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->tokoModel->update($fields['id_toko'], $fields)) {

				$response['success'] = true;
				$response['messages'] = "Data berhasil diubah";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data gagal diubah";
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_toko');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->tokoModel->where('id_toko', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = "Data berhasil dihapus";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data gagal dihapu";
			}
		}

		return $this->response->setJSON($response);
	}
}
