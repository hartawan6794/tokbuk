<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\OrderModel;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Order extends BaseController
{

	protected $orderModel;
	protected $validation;

	public function __construct()
	{
		$this->orderModel = new OrderModel();
		$this->validation =  \Config\Services::validation();
		helper('settings');
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'order',
			'title'     		=> 'Menu Pemesanan'
		];

		if (session()->get('isLogin')) {
			return view('order', $data);
		}else{
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->orderModel->select()->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_order.id_user_bio', 'left')->join('tbl_rekening tr', 'tr.id_rekening = tbl_order.id_rekening', 'left')->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="detail(' . $value->id_order . ')"><i class="fa-solid fa-eye"></i>   Detail</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			// $ops .= '<div class="dropdown-divider"></div>';
			// $ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_order . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
			$ops .= '</div></div>';

			$validai = '<div class="btn-group">';
			$validai .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$validai .= '  Validasi</button>';
			$validai .= '<div class="dropdown-menu">';
			$validai .= '<a class="dropdown-item text-info" onClick="valid(' . $value->id_order . ',2)"><i class="fa-solid fa-check"></i>  Terima</a>';
			// $validai .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$validai .= '<div class="dropdown-divider"></div>';
			$validai .= '<a class="dropdown-item text-danger" onClick="valid(' . $value->id_order . ',1)"><i class="fa-solid fa-xmark"></i>   Tolak</a>';
			$validai .= '</div></div>';
			$data['data'][$key] = array(
				$no,
				$value->invoice,
				$value->nm_user,
				$value->nm_bank,
				rupiah($value->total_pembayaran),
				$value->bukti_order ? '<button class="btn btn-sm btn-success text-default center" onClick="lihat(' . $value->id_order . ')">Lihat Bukti</button>' : '<span class="p-2 bg-warning"> Belum Upload File</span>',
				// $value->validasi == 0 ? ($value->bukti_order ? '':'Belum Upload') : '-',
				$value->bukti_order ? ($value->validasi == 0 ? $validai : ($value->validasi == '2' ? '<span class="p-1 bg-success"> Pembayaran Diterima</span>' : ($value->validasi == '3' ? '<span class="p-1 bg-danger"> Sedang Mengirim</span>': ($value->validasi == '4' ? '<span class="p-1 bg-danger"> Pesanan Diterima</span>':'<span class="p-1 bg-danger"> Pembayaran Ditolak</span>')))) : '<span class="p-2 bg-warning"> Belum Upload File</span>',
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

			$data = $this->orderModel->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_order.id_user_bio', 'right')->join('tbl_rekening tr', 'tr.id_rekening = tbl_order.id_rekening', 'left')->join('tbl_order_detail tod', 'tod.id_order = tbl_order.id_order')->join('tbl_product tp', 'tp.id_product = tod.id_product', 'left')->where('tbl_order.id_order', $id)->first();

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
		$fields['created_at'] = date('Y-m-d H:i:s');
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

	public function sendEmail()
	{

		$response = array();

		$data['id_order']          = $this->request->getPost('id_order');
		$data['validasi']          = $this->request->getPost('validasi');

		$dataOrder = $this->orderModel->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_order.id_user_bio', 'left')->where('id_order', $data['id_order'])->first();

		if ($data['validasi'] == '1') {
			$message        = "Pembayaran di tolak
			Invoice : " . $dataOrder->invoice . "

			
			PESAN NO-REPLAY";
		} else {
			$message        = "Pembayaran di terima
			Invoice : " . $dataOrder->invoice . "


			PESAN NO-REPLAY";
		}
		// Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		// try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";                   //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username = "dafapratama231299@gmail.com";
		$mail->Password = "zmwulpmcdncyukdg";                         //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom("dafapratama231299@gmail.com", "Toko Buku");
		$mail->addAddress($dataOrder->email_user, $dataOrder->nm_user);
		$mail->isHTML(true);
		//Content
		$mail->Subject = "Konfirmasi Pembayaran";
		$mail->Body = $message;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if ($mail->send()) {
			$this->orderModel->update($data['id_order'], $data);
			$response['success'] = true;
			$response['messages'] = lang("Berhasil Mengirim Email Validasi");
		} else {

			$response['success'] = false;
			$response['messages'] = lang("Gagal Mengirim Email Validasi");
		}
		// } catch (Exception $e) {
		// 	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// }
		return $this->response->setJSON($response);
	}
}
