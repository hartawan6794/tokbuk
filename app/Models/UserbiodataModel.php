<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Models;
use CodeIgniter\Model;

class UserbiodataModel extends Model {
    
	protected $table = 'tbl_user_biodata';
	protected $primaryKey = 'id_user_bio';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['nik_user', 'nm_user', 'email_user', 'gender', 'tanggal_lahir', 'tempat_lahir', 'telpon', 'alamat', 'imguser', 'created_at', 'updated_at'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    


	public function nikExist($nik){
        $query = 'SELECT * from tbl_user_biodata where nik_user= ?';
        $result = $this->db->query($query, [$nik])->getResultArray();

        if($result){
            return true;
        }else{
            return false;
        }
    }
	
}