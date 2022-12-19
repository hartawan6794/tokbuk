<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\RekeningModel;

class Rekening extends BaseController
{

	protected $rekeningModel;
	protected $validation;

	public function __construct()
	{
		$this->rekeningModel = new RekeningModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'rekening',
			'title'     		=> 'rekening'
		];

		if (session()->get('isLogin')) {
			return view('rekening', $data);
		}else{
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->rekeningModel->select()->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_rekening . ')"><i class="fa-solid fa-pen-to-square"></i>   Ubah</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_rekening . ')"><i class="fa-solid fa-trash"></i>   Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no,
				$value->nm_bank,
				$value->an_rekening,
				$value->no_rek,

				$ops
			);
			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_rekening');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->rekeningModel->where('id_rekening', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_rekening'] = $this->request->getPost('id_rekening');
		$fields['nm_bank'] = $this->request->getPost('nm_bank');
		$fields['an_rekening'] = $this->request->getPost('an_rekening');
		$fields['no_rek'] = $this->request->getPost('no_rek');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'nm_bank' => ['label' => 'Nama Bank', 'rules' => 'required|min_length[0]|max_length[50]'],
			'an_rekening' => ['label' => 'Atas Nama', 'rules' => 'required|min_length[0]|max_length[255]'],
			'no_rek' => ['label' => 'No Rekening', 'rules' => 'required|min_length[0]|max_length[25]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->rekeningModel->insert($fields)) {

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

		$fields['id_rekening'] = $this->request->getPost('id_rekening');
		$fields['nm_bank'] = $this->request->getPost('nm_bank');
		$fields['an_rekening'] = $this->request->getPost('an_rekening');
		$fields['no_rek'] = $this->request->getPost('no_rek');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'nm_bank' => ['label' => 'Nama Bank', 'rules' => 'required|min_length[0]|max_length[50]'],
			'an_rekening' => ['label' => 'Atas Nama', 'rules' => 'required|min_length[0]|max_length[255]'],
			'no_rek' => ['label' => 'No Rekening', 'rules' => 'required|min_length[0]|max_length[25]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->rekeningModel->update($fields['id_rekening'], $fields)) {

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

		$id = $this->request->getPost('id_rekening');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->rekeningModel->where('id_rekening', $id)->delete()) {

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
