<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\OrderModel;

class Order extends BaseController
{

	protected $orderModel;
	protected $validation;

	public function __construct()
	{
		$this->orderModel = new OrderModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'order',
			'title'     		=> 'Menu Pemesanan'
		];

		return view('order', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->orderModel->select()->join('tbl_user_biodata tub','tub.id_user_bio = tbl_order.id_user_bio','left')->join('tbl_rekening tr','tr.id_rekening = tbl_order.id_rekening','left')->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_order . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("App.edit")  . '</a>';
			$ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_order . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
			$ops .= '</div></div>';

			$validasi = '<a href="' . $value->id_order . '" ><i class="fa-solid fa-pen-to-square"></i>   Validasi</a>';
			$validasi .= '<a href="' . $value->id_order . '" ><i class="fa-solid fa-pen-to-square"></i>   Validasi</a>';

			$data['data'][$key] = array(
				$no,
				$value->invoice,
				$value->nm_user,
				$value->nm_bank,
				$value->total_pembayaran,
				$value->bukti_order ? '':'Belum Upload',
				$value->validasi == 0? $validasi:'-',
				$ops
			);
			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_order');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->orderModel->where('id_order', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_order'] = $this->request->getPost('id_order');
		$fields['invoice'] = $this->request->getPost('invoice');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['id_rekening'] = $this->request->getPost('id_rekening');
		$fields['bukti_order'] = $this->request->getPost('bukti_order');
		$fields['noresi'] = $this->request->getPost('noresi');
		$fields['tgl_order'] = $this->request->getPost('tgl_order');
		$fields['sub_total'] = $this->request->getPost('sub_total');
		$fields['sub_total_pengiriman'] = $this->request->getPost('sub_total_pengiriman');
		$fields['total_pembayaran'] = $this->request->getPost('total_pembayaran');
		$fields['validasi'] = $this->request->getPost('validasi');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'invoice' => ['label' => 'Invoice', 'rules' => 'permit_empty|min_length[0]|max_length[20]'],
			'id_user_bio' => ['label' => 'Nama Pelanggan', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'id_rekening' => ['label' => 'Rekening', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
			'bukti_order' => ['label' => 'Bukti order', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'noresi' => ['label' => 'Noresi', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'tgl_order' => ['label' => 'Tgl order', 'rules' => 'required|valid_date|min_length[0]'],
			'sub_total' => ['label' => 'Sub total', 'rules' => 'required|min_length[0]|max_length[10]'],
			'sub_total_pengiriman' => ['label' => 'Sub total pengiriman', 'rules' => 'required|min_length[0]|max_length[10]'],
			'total_pembayaran' => ['label' => 'Total pembayaran', 'rules' => 'required|min_length[0]|max_length[10]'],
			'validasi' => ['label' => 'Validasi', 'rules' => 'required|numeric|min_length[0]|max_length[3]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->orderModel->insert($fields)) {

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

		$fields['id_order'] = $this->request->getPost('id_order');
		$fields['invoice'] = $this->request->getPost('invoice');
		$fields['id_user_bio'] = $this->request->getPost('id_user_bio');
		$fields['id_rekening'] = $this->request->getPost('id_rekening');
		$fields['bukti_order'] = $this->request->getPost('bukti_order');
		$fields['noresi'] = $this->request->getPost('noresi');
		$fields['tgl_order'] = $this->request->getPost('tgl_order');
		$fields['sub_total'] = $this->request->getPost('sub_total');
		$fields['sub_total_pengiriman'] = $this->request->getPost('sub_total_pengiriman');
		$fields['total_pembayaran'] = $this->request->getPost('total_pembayaran');
		$fields['validasi'] = $this->request->getPost('validasi');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'invoice' => ['label' => 'Invoice', 'rules' => 'permit_empty|min_length[0]|max_length[20]'],
			'id_user_bio' => ['label' => 'Nama Pelanggan', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'id_rekening' => ['label' => 'Rekening', 'rules' => 'required|numeric|min_length[0]|max_length[4]'],
			'bukti_order' => ['label' => 'Bukti order', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'noresi' => ['label' => 'Noresi', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'tgl_order' => ['label' => 'Tgl order', 'rules' => 'required|valid_date|min_length[0]'],
			'sub_total' => ['label' => 'Sub total', 'rules' => 'required|min_length[0]|max_length[10]'],
			'sub_total_pengiriman' => ['label' => 'Sub total pengiriman', 'rules' => 'required|min_length[0]|max_length[10]'],
			'total_pembayaran' => ['label' => 'Total pembayaran', 'rules' => 'required|min_length[0]|max_length[10]'],
			'validasi' => ['label' => 'Validasi', 'rules' => 'required|numeric|min_length[0]|max_length[3]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->orderModel->update($fields['id_order'], $fields)) {

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

		$id = $this->request->getPost('id_order');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->orderModel->where('id_order', $id)->delete()) {

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
