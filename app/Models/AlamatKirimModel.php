<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class AlamatKirimModel extends Model {
    
	protected $table = 'tbl_alamat';
	protected $primaryKey = 'id_alamat';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['id_user_bio', 'nm_penerima', 'telp_penerima', 'id_provinsi', 'provinsi', 'id_kabupaten', 'kabupaten', 'kecamatan', 'kelurahan', 'alamat_rumah', 'postalcode', 'status', 'created_at', 'updated_at'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}