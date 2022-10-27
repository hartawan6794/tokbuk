<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UserModel;

class User extends BaseController
{

	protected $userModel;
	protected $validation;

	public function __construct()
	{
		$this->userModel = new UserModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		if (session()->get('isLogin')) {

			$data = [
				'controller'    	=> 'user',
				'title'     		=> 'User'
			];

			return view('user', $data);
		} else {
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->userModel->select()->where('status', '10')->findAll();

		$i = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_user . ')"><i class="fa-solid fa-pen-to-square"></i> Ubah Password</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			// $ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_user . ')"><i class="fa-solid fa-trash"></i> Hapus</a>';
			$ops .= '</div></div>';

			// if($value->status != '9'){
			$data['data'][$key] = array(
				$i,
				$value->username,
				$value->password,
				$value->role == '0' ? 'Admin' : ($value->role == '1' ? 'Penjual' : 'Pembeli'),
				$ops
			);
			// }

			$i++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_user');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->userModel->where('id_user', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_user'] = $this->request->getPost('id_user');
		$fields['nik_user'] = $this->request->getPost('nik_user');
		$fields['status'] = $this->request->getPost('status');
		$fields['role'] = $this->request->getPost('role');
		$fields['password'] = $this->request->getPost('password');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'nik_user' => ['label' => 'Nik user', 'rules' => 'required|min_length[0]|max_length[20]'],
			'status' => ['label' => 'Status', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
			'role' => ['label' => 'Role', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[2]'],
			'password' => ['label' => 'Password', 'rules' => 'permit_empty|min_length[0]|max_length[32]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->userModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.insert-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.insert-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();
		$create = date('Y-m-d H:i:s');
		$fields['id_user'] = $this->request->getPost('id_user');
		$fields['pass'] = $this->request->getPost('pass');
		$fields['confpass'] = $this->request->getPost('confpass');

		$user = array(
			'password' => md5($fields['pass'])
		);

		$this->validation->setRules([
			'pass' => [
				'rules' => 'required|min_length[4]',
				'errors' => [
					'required' => 'Password tidak boleh kosong',
					'min_length' => 'Password harus lebih dari 4 karakter'
				]
			],
			'confpass' => [
				'rules' => 'required|matches[pass]',
				'errors' => [
					'required' => 'Password konfirmasi harus diisi',
					'matches' => 'Password konfirmasi tidak sama'
				]
			],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->userModel->update($fields['id_user'], $user)) {
				$response['success'] = true;
				$response['messages'] = "Berhasil Mengubah Data";
			} else {
				$response['success'] = false;
				$response['messages'] = "Gagal Mengubah Data";
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_user');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->userModel->update($id, ['status' => '9'])) {

				$response['success'] = true;
				$response['messages'] = "Berhasil di Hapus";
			} else {

				$response['success'] = false;
				$response['messages'] = "Gagal di Hapus";
			}
		}

		return $this->response->setJSON($response);
	}
}
