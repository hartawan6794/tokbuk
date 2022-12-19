<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\KategoriModel;

class Kategori extends BaseController
{

	protected $kategoriModel;
	protected $validation;

	public function __construct()
	{
		$this->kategoriModel = new KategoriModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'		=> 'kategori',
			'title'     		=> 'Kategori'
		];

		if (session()->get('isLogin')) {
			return view('kategori', $data);
		}else{
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();
		$result = $this->kategoriModel->select()->findAll();

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_kategori . ')"><i class="fa-solid fa-pen-to-square"></i> Ubah</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_kategori . ')"><i class="fa-solid fa-trash"></i> Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$value->id_kategori,
				$value->nama_kategori,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_kategori');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->kategoriModel->where('id_kategori', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_kategori'] = $this->request->getPost('id_kategori');
		$fields['nama_kategori'] = $this->request->getPost('nama_kategori');


		$this->validation->setRules([
			'nama_kategori' => ['label' => 'Nama kategori', 'rules' => 'required|min_length[0]|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->kategoriModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Menambahkan Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Menambahkan Data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id_kategori'] = $this->request->getPost('id_kategori');
		$fields['nama_kategori'] = $this->request->getPost('nama_kategori');


		$this->validation->setRules([
			'nama_kategori' => ['label' => 'Nama kategori', 'rules' => 'required|min_length[0]|max_length[255]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->kategoriModel->update($fields['id_kategori'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Mengubah Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Mengubah Data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_kategori');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->kategoriModel->where('id_kategori', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Menghapus Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Menghapus Data");
			}
		}

		return $this->response->setJSON($response);
	}
}
