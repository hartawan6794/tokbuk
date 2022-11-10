<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\UserbiodataModel;
use App\Models\UserModel;

class Userbiodata extends BaseController
{

	protected $userbiodataModel;
	protected $validation;

	public function __construct()
	{
		$this->userbiodataModel = new UserbiodataModel();
		$this->userModel = new UserModel();
		$this->validation =  \Config\Services::validation();
		// $this->session = \Config\Services::session();
		helper('settings');
	}

	public function index()
	{
		if (session()->get('isLogin')) {
			$data = [
				'controller'    	=> 'userbiodata',
				'title'     		=> 'Userbiodata'
			];
			return view('userbiodata', $data);
		} else {
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->userbiodataModel->select()->findAll();
		$i = 1;

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_user_bio . ')"><i class="fa-solid fa-pen-to-square"></i>   Ubah</a>';
			$ops .= '<a class="dropdown-item text-orange" onclick="view(' . $value->id_user_bio . ')" ><i class="fa-solid fa-eye"></i> Detail</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_user_bio . ')"><i class="fa-solid fa-trash"></i>   Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$i,
				$value->nik_user,
				$value->nm_user,
				$value->email_user,
				// $value->gender == '1' ? 'Laki-laki' : 'Perempuan',
				// $value->tempat_lahir . ', ' . tgl_indo($value->tanggal_lahir),
				$value->telpon,
				$value->alamat,

				$ops
			);
			$i++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_user_bio');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->userbiodataModel->where('id_user_bio', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();
		$create = date('Y-m-d H:i:s');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['nik_user'] = $this->request->getPost('nik_user');
		$fields['nm_user'] = $this->request->getPost('nm_user');
		$fields['email_user'] = $this->request->getPost('email_user');
		$fields['gender'] = $this->request->getPost('gender');
		$fields['tanggal_lahir'] = $this->request->getPost('tanggal_lahir');
		$fields['tempat_lahir'] = $this->request->getPost('tempat_lahir');
		$fields['telpon'] = $this->request->getPost('telpon');
		$fields['alamat'] = $this->request->getPost('alamat');
		$fields['imguser'] = $this->request->getFile('imguser');
		$fields['username'] = $this->request->getPost('username');
		$fields['pass'] = $this->request->getPost('pass');
		$fields['confpass'] = $this->request->getPost('confpass');
		$fields['role'] = $this->request->getPost('role');

		// $d = $this->request->getPost();
		// var_dump($fields);
		// die;

		$userbiodata = array(
			'nik_user' => $fields['nik_user'],
			'nm_user' => $fields['nm_user'],
			'email_user' => $fields['email_user'],
			'gender' => $fields['gender'],
			'tanggal_lahir' => $fields['tanggal_lahir'],
			'tempat_lahir' => $fields['tempat_lahir'],
			'telpon' => $fields['telpon'],
			'alamat' => $fields['alamat'],
			'created_at' => $create
		);

		$user = array(
			'username' => str_replace(" ","",$fields['username']),
			'nik_user' => $fields['nik_user'],
			'email_user' => $fields['email_user'],
			'status' => 10,
			'password' => md5($fields['pass']),
			'role' => $fields['role'],
			'created_at' => $create
		);

		$this->validation->setRules([
			'nik_user' => ['label' => 'Nik user', 'rules' => 'required|min_length[0]|max_length[20]|nikExist[nik_user]', 'errors' => [
				'required' => 'Nik Pegawai Masih Kosong',
				'min_length' => 'Panjang nik kurang dari 16 karakter',
				'nikExist' => 'Nik telah digunakan'
			]],
			'nm_user' => ['label' => 'Nm user', 'rules' => 'required', 'errors' => [
				'required' => 'Nama Pegawai Masih Kosong'
			]],
			'email_user' => ['label' => 'Email user', 'rules' => 'required|valid_email', 'errors' => [
				'required' => 'Email Pegawai Masih Kosong',
				'valid_email' => 'Email tidak valid'
			]],
			'gender' => ['label' => 'Gender', 'rules' => 'required', 'errors' => [
				'required' => 'Jenis Kelamin Belum Dipilih'
			]],
			'tanggal_lahir' => [
				'label' => 'Tanggal lahir', 'rules' => 'trim|required',
				'errors' => [
					'required' => 'Tanggal Lahir Masih Kosong'
				]
			],
			'tempat_lahir' => [
				'label' => 'Tempat lahir', 'rules' => 'required',
				'errors' => [
					'required' => 'Tempat Lahir Masih Kosong'
				]
			],
			'telpon' => [
				'label' => 'Telpon', 'rules' => 'trim|required|mobileValidation[telpon]',
				'errors' => [
					'required' => 'No telpon belum di isi',
					'mobileValidation' => 'No telpon harus menggunalan anggka dan angka harus lebih dari 11',
					// 'mobileExist' => ''
				]
			],
			'alamat' => [
				'label' => 'Alamat', 'rules' => 'required',
				'errors' => [
					'required' => 'Alamat Masih Kosong'
				]
			],
			'username' => [
				'rules' => 'required|trim',
				'errors' => [
					'required' => 'Username tidak boleh kosong',
					'trim' => 'Username tidak boleh spasi'
				]
			],
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
			'role' => [
				'rules' => 'required',
				'errors' => [
					'required' => 'Jenis akun harus dipilih'
				]
			]
			// 'imguser' => [
			// 	'label' => 'Imguser', 'rules' => 'mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[imguser,4096]',
			// 	'errors' => [
			// 		'mime_in' => 'Unggah poto harus berupa .jpg, .jpeg, .png',
			// 		'max_size' => 'Unggah photo maksimal 4MB'
			// 	]
			// ]

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {
			if ($fields['imguser']->getName() != '') {
				if (file_exists('img/user/' . $fields['imguser']->getName())) {
					unlink('img/user/' . $fields['imguser']->getName());
				}
				$fileName = 'profile-' . $fields['nik_user'] . '.' . $fields['imguser']->guessExtension();
				$userbiodata['imguser'] = $fileName;
				$fields['imguser']->move(WRITEPATH . '../public/img/user', $fileName);
			}
			if ($this->userbiodataModel->insert($userbiodata)) {

				$this->userModel->insert($user);
				$response['success'] = true;
				$response['messages'] = "Data berhasil ditambah";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data gagal ditambah";
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$create = date('Y-m-d H:i:s');
		$update = array();
		$fields['nik_user'] = $this->request->getPost('nik_user');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['nm_user'] = $this->request->getPost('nm_user');
		$fields['email_user'] = $this->request->getPost('email_user');
		$fields['gender'] = $this->request->getPost('gender');
		$fields['tanggal_lahir'] = $this->request->getPost('tanggal_lahir');
		$fields['tempat_lahir'] = $this->request->getPost('tempat_lahir');
		$fields['telpon'] = $this->request->getPost('telpon');
		$fields['alamat'] = $this->request->getPost('alamat');
		$fields['imguser'] = $this->request->getFile('imguser');
		$fields['updated_at'] = $create;

		$userbiodata = array(
			'nik_user' => $fields['nik_user'],
			'nm_user' => $fields['nm_user'],
			'email_user' => $fields['email_user'],
			'gender' => $fields['gender'],
			'tanggal_lahir' => $fields['tanggal_lahir'],
			'tempat_lahir' => $fields['tempat_lahir'],
			'telpon' => $fields['telpon'],
			'alamat' => $fields['alamat'],
			'updated_at' => $create
		);


		$this->validation->setRules([
			'nik_user' => ['label' => 'Nik user', 'rules' => 'required|min_length[0]|max_length[20]', 'errors' => [
				'required' => 'Nik Pegawai Masih Kosong',
				'min_length' => 'Panjang nik kurang dari 16 karakter',
				// 'nikExist' => 'Nik telah digunakan'
			]],
			'nm_user' => ['label' => 'Nm user', 'rules' => 'required', 'errors' => [
				'required' => 'Nama Pegawai Masih Kosong'
			]],
			'email_user' => ['label' => 'Email user', 'rules' => 'required|valid_email', 'errors' => [
				'required' => 'Email Pegawai Masih Kosong',
				'valid_email' => 'Email tidak valid'
			]],
			'gender' => ['label' => 'Gender', 'rules' => 'required', 'errors' => [
				'required' => 'Jenis Kelamin Belum Dipilih'
			]],
			'tanggal_lahir' => [
				'label' => 'Tanggal lahir', 'rules' => 'trim|required',
				'errors' => [
					'required' => 'Tanggal Lahir Masih Kosong'
				]
			],
			'tempat_lahir' => [
				'label' => 'Tempat lahir', 'rules' => 'required',
				'errors' => [
					'required' => 'Tempat Lahir Masih Kosong'
				]
			],
			'telpon' => [
				'label' => 'Telpon', 'rules' => 'trim|required|mobileValidation[telpon]',
				'errors' => [
					'required' => 'No telpon belum di isi',
					'mobileValidation' => 'No telpon harus menggunalan anggka dan angka harus lebih dari 11',
					// 'mobileExist' => ''
				]
			],
			'alamat' => [
				'label' => 'Alamat', 'rules' => 'required',
				'errors' => [
					'required' => 'Alamat Masih Kosong'
				]
			]
			// 'imguser' => [
			// 	'label' => 'Imguser', 'rules' => 'mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[imguser,4096]',
			// 	'errors' => [
			// 		'mime_in' => 'Unggah poto harus berupa .jpg, .jpeg, .png',
			// 		'max_size' => 'Unggah photo maksimal 4MB'
			// 	]
			// ]

		]);
		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {
			if ($fields['imguser']->getName() != '') {
				$data = $this->userbiodataModel->where('id_user_bio', $fields['id_user_bio'])->first();
				if ($data->imguser) {
					if (file_exists('img/user/' . $data->imguser)) {
						unlink('img/user/' . $data->imguser);
					}
				}
				$fileName = 'profile-' . $fields['nik_user'] . '.' . $fields['imguser']->guessExtension();
				$userbiodata['imguser'] = $fileName;
				$fields['imguser']->move(WRITEPATH . '../public/img/user', $fileName);
			}

			if ($this->userbiodataModel->update($fields['id_user_bio'], $userbiodata)) {

				$response['success'] = true;
				$response['messages'] = "Data berhasil diubah";
			} else {

				$response['success'] = false;
				$response['messages'] = "Data gagak diubah";
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_user_bio');

		$data = $this->userbiodataModel->where('id_user_bio', $id)->first();
		if (!$this->validation->check($id, 'required|numeric')) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {
			if ($data->imguser != '') {
				if (file_exists('img/user/' . $data->imguser)) {
					unlink('img/user/' . $data->imguser);
				}
			}
			if ($this->userbiodataModel->where('id_user_bio', $id)->delete()) {
				$this->userModel->where('nik_user', $data->nik_user)->delete();
				$response['success'] = true;
				$response['messages'] = "Data berhasil dihapus";
			} else {
				$response['success'] = false;
				$response['messages'] = "Data gagal dihapus";
			}
		}
		return $this->response->setJSON($response);
	}
}
