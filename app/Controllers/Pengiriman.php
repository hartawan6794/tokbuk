<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PengirimanModel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Pengiriman extends BaseController
{

	protected $pengirimanModel;
	protected $validation;

	public function __construct()
	{
		$this->pengirimanModel = new PengirimanModel();
		$this->order = new OrderModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'pengiriman',
			'title'     		=> 'Menu Pengiriman'
		];

		if (session()->get('isLogin')) {
			return view('pengiriman', $data);
		}else{
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->pengirimanModel->select()->join('tbl_order to', 'to.id_order = tbl_pengiriman.id_order', 'left')->findAll();

		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			// $ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_pengiriman . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("App.edit")  . '</a>';
			// $ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			// $ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_pengiriman . ')"><i class="fa-solid fa-trash"></i>   Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no++,
				$value->invoice,
				$value->layanan,
				$value->no_resi,
				$value->validasi == 3 ? 'Pesanan Sedang Dikirim' : ($value->validasi == 4 ? 'Pesanan Telah Diterima' : ''),

				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_pengiriman');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->pengirimanModel->where('id_pengiriman', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();
		$created = date('Y-m-d H:i:s');

		$fields['id_pengiriman'] = $this->request->getPost('id_pengiriman');
		$fields['id_order'] = $this->request->getPost('invoice');
		$fields['layanan'] = $this->request->getPost('layanan');
		$fields['no_resi'] = $this->request->getPost('no_resi');
		$fields['created_at'] = $created;

		$update['validasi'] = 3;

		$this->validation->setRules([
			'id_order' => ['label' => 'Id order', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'layanan' => ['label' => 'Layanan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'no_resi' => ['label' => 'No resi', 'rules' => 'required|min_length[0]|max_length[25]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pengirimanModel->insert($fields)) {
				$this->order->update($fields['id_order'], $update);
				$this->sendEmail($fields['id_order']);
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

		$fields['id_pengiriman'] = $this->request->getPost('id_pengiriman');
		$fields['id_order'] = $this->request->getPost('id_order');
		$fields['layanan'] = $this->request->getPost('layanan');
		$fields['no_resi'] = $this->request->getPost('no_resi');
		$fields['created_at'] = $this->request->getPost('created_at');
		$fields['updated_at'] = $this->request->getPost('updated_at');


		$this->validation->setRules([
			'id_order' => ['label' => 'Id order', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'layanan' => ['label' => 'Layanan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'no_resi' => ['label' => 'No resi', 'rules' => 'required|min_length[0]|max_length[25]'],
			'created_at' => ['label' => 'Created at', 'rules' => 'permit_empty|valid_date|min_length[0]'],
			'updated_at' => ['label' => 'Updated at', 'rules' => 'permit_empty|valid_date|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pengirimanModel->update($fields['id_pengiriman'], $fields)) {

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

		$id = $this->request->getPost('id_pengiriman');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->pengirimanModel->where('id_pengiriman', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("App.delete-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.delete-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function getInvoice()
	{
		$id_order = $this->request->getPost('id_order');
		if ($id_order) {
			$data = $this->order->select('jns_pengiriman')->where('id_order', $id_order)->findAll();
		} else {
			$data = $this->order->select('id_order,invoice')->where('validasi', 2)->findAll();
		}
		$data['result'] = $data;
		$data['success'] = true;
		return $this->response->setJSON($data);
	}

	public function sendEmail($id_order)
	{
		$dataOrder = $this->order->join('tbl_user_biodata tub', 'tub.id_user_bio = tbl_order.id_user_bio', 'left')->join('tbl_pengiriman tp', 'tp.id_order = tbl_order.id_order', 'left')->where('tbl_order.id_order', $id_order)->first();
		$message        = "Paket Anda Sedang DIkirim
			Invoice : " . $dataOrder->invoice . "
			Nomor Reei : " . $dataOrder->no_resi . "
			PESAN NO-REPLAY";
		// Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		// try {
		//Server settings
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";                   //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username = "dafapratama231299@gmail.com";
		$mail->Password = "zmwulpmcdncyukdg";                  //SMTP password
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

		$mail->send();
		// } catch (Exception $e) {
		// 	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// }
	}
}
