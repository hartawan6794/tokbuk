<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ProductModel;
use App\Models\TokoModel;
use App\Models\KategoriModel;

class Product extends BaseController
{

	protected $productModel;
	protected $validation;

	public function __construct()
	{
		$this->productModel = new ProductModel();
		$this->validation =  \Config\Services::validation();
		$this->toko = new TokoModel();
		$this->kategori = new KategoriModel();
		helper('settings');
	}

	public function index()
	{
		if (session()->get('username') == 'admin') {
			$toko = $this->toko->select('*')->get();
		} else {
			$toko = $this->toko->select('*')->where('id_user_bio', session()->get('id_user_bio'))->get();
		}
		$kategori = $this->kategori->select('*')->get();
		$data = [
			'controller'    	=> 'product',
			'title'     		=> 'Product',
			'toko'				=> 	$toko->getResult(),
			'kategori'	 		=>  $kategori->getResult(),
		];
		if (session()->get('isLogin')) {
			return view('product', $data);
		} else {
			return view('login');
		}
	}

	public function getAll()
	{
		$response = $data['data'] = array();
		if (session()->get('username') == 'admin') {
			$result = $this->productModel->select()->findAll();
		} else {
			$result = $this->productModel->select()->join('tbl_toko tt', 'tt.id_toko = tbl_product.id_toko', 'inner')->join('tbl_user_biodata tub', 'tub.id_user_bio = tt.id_user_bio', 'inner')->where('tub.id_user_bio', session()->get('id_user_bio'))->findAll();
		}
		$i = 1;

		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_product . ')"><i class="fa-solid fa-pen-to-square"></i>   Ubah</a>';
			$ops .= '<a class="dropdown-item text-orange" onclick="view(' . $value->id_product . ')" ><i class="fa-solid fa-eye"></i> Detail</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_product . ')"><i class="fa-solid fa-trash"></i>   Hapus</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$i,
				$value->judul_buku,
				$value->nm_penerbit,
				$value->nm_penulis,
				$value->tahun_terbit,
				rupiah($value->harga_buku),

				$ops
			);

			$i++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_product');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->productModel
				->join('tbl_toko', 'tbl_toko.id_toko = tbl_product.id_toko')
				->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_product.id_kategori', 'left')
				->where('id_product', $id)->first();
			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();
		$created = date('Y-m-d H:i:s');

		$fields['id_product'] = $this->request->getPost('id_product');
		$fields['id_toko'] = $this->request->getPost('id_toko');
		$fields['judul_buku'] = $this->request->getPost('judul_buku');
		$fields['nm_penerbit'] = $this->request->getPost('nm_penerbit');
		$fields['nm_penulis'] = $this->request->getPost('nm_penulis');
		$fields['tahun_terbit'] = $this->request->getPost('tahun_terbit');
		$fields['jml_halaman'] = $this->request->getPost('jml_halaman');
		$fields['deskripsi_buku'] = $this->request->getPost('deskripsi_buku');
		$fields['id_kategori'] = $this->request->getPost('id_kategori');
		$fields['stok'] = $this->request->getPost('stok');
		$fields['baret_produk'] = $this->request->getPost('berat');
		$fields['harga_buku'] = $this->request->getPost('harga_buku');
		$fields['created_at'] = $created;
		$img = [
			$this->request->getFile('imgproduct1'),
			$this->request->getFile('imgproduct2'),
			$this->request->getFile('imgproduct3')
		];


		$this->validation->setRules([
			'id_toko' => ['label' => 'Id toko', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'judul_buku' => ['label' => 'Judul buku', 'rules' => 'required|min_length[0]|max_length[255]'],
			'nm_penerbit' => ['label' => 'Nama penerbit', 'rules' => 'required|min_length[0]|max_length[255]'],
			'nm_penulis' => ['label' => 'Nama penulis', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'tahun_terbit' => ['label' => 'Tahun', 'rules' => 'permit_empty|min_length[0]|max_length[4]'],
			'jml_halaman' => ['label' => 'Juml halaman', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'deskripsi_buku' => ['label' => 'Deskripsi buku', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'id_kategori' => ['label' => 'id_kategori', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[2]'],
			'stok' => ['label' => 'Stok', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'harga_buku' => ['label' => 'Harga buku', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],
			'imgproduct1' => [
				'label' => 'imgproduct1', 'rules' => 'uploaded[imgproduct1]'
					. '|is_image[imgproduct1]'
					. '|mime_in[imgproduct1,image/jpg,image/jpeg,image/png,image/webp]'
					. '|max_size[imgproduct1,512]', 'errors' => ['max_size' => 'Ukuran file harus di bawah 512Kb', 'mime_in' => 'Harap Masukan File Berupa Gambar', 'is_image' => 'Harap Masukan File Berupa Gambar']
			],
			// 'imgproduct2' => [
			// 	'label' => 'imgproduct2', 'rules' => 'uploaded[imgproduct2]'
			// 		. '|is_image[imgproduct2]'
			// 		. '|mime_in[imgproduct2,image/jpg,image/jpeg,image/png,image/webp]'
			// 		. '|max_size[imgproduct2,512]', 'errors' => ['max_size' => 'Ukuran file harus di bawah 512Kb', 'mime_in' => 'Harap Masukan File Berupa Gambar', 'is_image' => 'Harap Masukan File Berupa Gambar']
			// ],
			// 'imgproduct3' => [
			// 	'label' => 'imgproduct3', 'rules' => 'uploaded[imgproduct3]'
			// 		. '|is_image[imgproduct3]'
			// 		. '|mime_in[imgproduct3,image/jpg,image/jpeg,image/png,image/webp]'
			// 		. '|max_size[imgproduct3,512]', 'errors' => ['max_size' => 'Ukuran file harus di bawah 512Kb', 'mime_in' => 'Harap Masukan File Berupa Gambar', 'is_image' => 'Harap Masukan File Berupa Gambar']
			// ]
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			$d = 1;
			$fileName = array();
			for ($i = 0; $i < count($img); $i++) {
				if ($img[$i]->getName() != '') {

					$fileName[$i] = 'product-' . $img[$i]->getRandomName();
					$fields['imgproduct' . $d] = $fileName[$i];
					$img[$i]->move(WRITEPATH . '../public/img/product', $fileName[$i]);
				}
				$d++;
			}
			// var_dump($fields);die;

			if ($this->productModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Tambah Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Tambah Data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();
		$update = date('Y-m-d H:i:s');
		$fields['id_product'] = $this->request->getPost('id_product');
		$fields['id_toko'] = $this->request->getPost('id_toko');
		$fields['judul_buku'] = $this->request->getPost('judul_buku');
		$fields['nm_penerbit'] = $this->request->getPost('nm_penerbit');
		$fields['nm_penulis'] = $this->request->getPost('nm_penulis');
		$fields['tahun_terbit'] = $this->request->getPost('tahun_terbit');
		$fields['jml_halaman'] = $this->request->getPost('jml_halaman');
		$fields['deskripsi_buku'] = $this->request->getPost('deskripsi_buku');
		$fields['id_kategori'] = $this->request->getPost('id_kategori');
		$fields['stok'] = $this->request->getPost('stok');
		$fields['baret_produk'] = $this->request->getPost('berat');
		$fields['harga_buku'] = $this->request->getPost('harga_buku');
		$fields['updated_at'] = $update;
		$img = [
			$this->request->getFile('imgproduct1'),
			$this->request->getFile('imgproduct2'),
			$this->request->getFile('imgproduct3')
		];
		$data = $this->productModel->select()->where('id_product', $fields['id_product'])->first();
		$imgDelete = [
			$data->imgproduct1,
			$data->imgproduct2,
			$data->imgproduct3,
		];

		$this->validation->setRules([
			'id_toko' => ['label' => 'Id toko', 'rules' => 'required|numeric|min_length[0]|max_length[6]'],
			'judul_buku' => ['label' => 'Judul buku', 'rules' => 'required|min_length[0]|max_length[255]'],
			'nm_penerbit' => ['label' => 'Nama penerbit', 'rules' => 'required|min_length[0]|max_length[255]'],
			'nm_penulis' => ['label' => 'Nama penulis', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'tahun_terbit' => ['label' => 'Tahun', 'rules' => 'permit_empty|min_length[0]|max_length[4]'],
			'jml_halaman' => ['label' => 'Juml halaman', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'deskripsi_buku' => ['label' => 'Deskripsi buku', 'rules' => 'permit_empty|min_length[0]|max_length[255]'],
			'id_kategori' => ['label' => 'id_kategori', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[2]'],
			'stok' => ['label' => 'Stok', 'rules' => 'permit_empty|numeric|min_length[0]|max_length[6]'],
			'harga_buku' => ['label' => 'Harga buku', 'rules' => 'permit_empty|min_length[0]|max_length[10]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form
		} else {
			$d = 1;
			$fileName = array();

			//proses cek gambar
			for ($i = 0; $i < count($img); $i++) {
				if ($img[$i]->getName() != '') {

					//ketika file ada, menghapus file lama
					if (file_exists('img/product/' . $imgDelete[$i])) {
						unlink('img/product/' . $imgDelete[$i]);
					}

					// rename fie gambar
					$fileName[$i] = 'product-' . $img[$i]->getRandomName();
					$fields['imgproduct' . $d] = $fileName[$i];
					//menyimpan gambar ke server
					$img[$i]->move(WRITEPATH . '../public/img/product', $fileName[$i]);
				}
				$d++;
			}

			if ($this->productModel->update($fields['id_product'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Ubah Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Ubah Data");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_product');
		$data = $this->productModel->select()->where('id_product', $id)->first();
		$img = [
			$data->imgproduct1,
			$data->imgproduct2,
			$data->imgproduct3,
		];
		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {
			for ($i = 0; $i < count($img); $i++) {
				if ($img[$i] != '') {
					if (file_exists('img/product/' . $img[$i])) {
						unlink('img/product/' . $img[$i]);
					}
				}
			}
			if ($this->productModel->where('id_product', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("Berhasil Mengahpus Data");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("Gagal Mengahpus Data");
			}
		}

		return $this->response->setJSON($response);
	}
}
