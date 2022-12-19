<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\KeranjangModel;

class Keranjang extends BaseController
{

	protected $keranjangModel;
	protected $validation;

	public function __construct()
	{
		$this->keranjangModel = new KeranjangModel();
		$this->validation =  \Config\Services::validation();
		helper('settings');
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'keranjang',
			'title'     		=> 'Menu Keranjang'
		];

		if (session()->get('isLogin')) {
			return view('keranjang', $data);
		}else{
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->keranjangModel->select()->join('tbl_product tp', 'tp.id_product = tbl_cart.id_product', 'left')->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_cart.id_user_bio', '')->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_cart . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("App.edit")  . '</a>';
			$ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_cart . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no++,
				$value->judul_buku,
				$value->qty,
				$value->harga_buku,
				rupiah($value->qty * $value->harga_buku),
				$value->nm_user,

				// $ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_cart');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->keranjangModel->where('id_cart', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_cart'] = $this->request->getPost('id_cart');
		$fields['id_product'] = $this->request->getPost('id_product');
		$fields['qty'] = $this->request->getPost('qty');
		$fields['harga_buku'] = $this->request->getPost('harga_buku');
		$fields['total_harga'] = $this->request->getPost('total_harga');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'id_product' => ['label' => 'Id product', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'qty' => ['label' => 'Qty', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'harga_buku' => ['label' => 'Harga buku', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],
			'total_harga' => ['label' => 'Total harga', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->keranjangModel->insert($fields)) {

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

		$fields['id_cart'] = $this->request->getPost('id_cart');
		$fields['id_product'] = $this->request->getPost('id_product');
		$fields['qty'] = $this->request->getPost('qty');
		$fields['harga_buku'] = $this->request->getPost('harga_buku');
		$fields['total_harga'] = $this->request->getPost('total_harga');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'id_product' => ['label' => 'Id product', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'qty' => ['label' => 'Qty', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'harga_buku' => ['label' => 'Harga buku', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],
			'total_harga' => ['label' => 'Total harga', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],
			'id_user_bio' => ['label' => 'Id user bio', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->keranjangModel->update($fields['id_cart'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.update-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.update-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_cart');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->keranjangModel->where('id_cart', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("App.delete-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.delete-error");
			}
		}

		return $this->response->setJSON($response);
	}
}
