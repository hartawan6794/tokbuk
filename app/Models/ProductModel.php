<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model {
    
	protected $table = 'tbl_product';
	protected $primaryKey = 'id_product';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['id_toko', 'judul_buku', 'nm_penerbit', 'nm_penulis', 'tahun_terbit', 'jml_halaman', 'deskripsi_buku', 'id_kategori', 'stok', 'harga_buku', 'imgproduct1', 'imgproduct2', 'imgproduct3', 'created_at', 'updated_at'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}