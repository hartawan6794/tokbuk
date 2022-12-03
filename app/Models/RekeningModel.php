<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class RekeningModel extends Model {
    
	protected $table = 'tbl_rekening';
	protected $primaryKey = 'id_rekening';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nm_bank', 'an_rekening', 'no_rek', 'created_at', 'updated_at'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}